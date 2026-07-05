<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\Developer\DeviceManagementController;
use App\Http\Controllers\Developer\EventManagementController;
use App\Http\Controllers\Developer\TemplateManagementController;
use App\Http\Controllers\Developer\LicenseManagementController;
use App\Http\Controllers\Client\GalleryController;
use App\Http\Controllers\Client\EventManagementController as ClientEventManagementController;
use App\Http\Controllers\PublicGalleryController;
use App\Models\NavigationMenu;
use App\Models\PricingPlan;
use App\Models\Article;
use App\Models\ClientDevice;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    $menus = NavigationMenu::where('type', 'landing_page')->orderBy('order')->get();
    $plans = PricingPlan::all();
    $latestArticles = Article::latest()->take(3)->get();
    $landingPage = \App\Models\StaticPage::where('slug', 'landing')->first();
    return view('landing', compact('menus', 'plans', 'latestArticles', 'landingPage'));
})->name('landing');

// Public Gallery & Session Sharing Routes
Route::get('/e/{event}', [PublicGalleryController::class, 'eventGallery'])->name('public.event-gallery');
Route::get('/s/{public_token}', [PublicGalleryController::class, 'sessionResult'])->name('public.session-result');

Route::get('/login', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('developer.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('developer');
        }
        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password yang dimasukkan salah.',
    ])->onlyInput('email');
});

Route::get('/dashboard', function (Request $request) {
    if (!$request->has('panel')) {
        return redirect()->route('client.events.index');
    }
    $menus = NavigationMenu::where('type', 'user_dashboard')->orderBy('order')->get();
    $plans = PricingPlan::all();
    $devices = ClientDevice::where('user_id', Auth::id())
        ->orWhereNull('user_id')
        ->latest('last_active_at')
        ->get();
    $storagePath = storage_path('app/public');
    $storageBytes = File::isDirectory($storagePath)
        ? collect(File::allFiles($storagePath))->sum(fn ($file) => $file->getSize())
        : 0;
    $storageQuotaBytes = 2 * 1024 * 1024 * 1024;
    $formatStorage = fn (int|float $bytes) => $bytes >= 1024 * 1024 * 1024
        ? number_format($bytes / (1024 * 1024 * 1024), 2) . ' GB'
        : number_format($bytes / (1024 * 1024), 1) . ' MB';

    $storageUsage = [
        'used' => $formatStorage($storageBytes),
        'quota' => '2 GB',
        'remaining' => $formatStorage(max($storageQuotaBytes - $storageBytes, 0)),
        'percentage' => min(100, round(($storageBytes / $storageQuotaBytes) * 100, 1)),
    ];

    $license = [
        'plan' => 'Online Starter Plan Internal',
        'status' => 'Aktif',
        'license_key' => 'BC-LS-2026-STARTER-001',
        'device_limit' => 3,
        'active_devices' => $devices->where('is_online', true)->count(),
        'registered_devices' => $devices->count(),
        'renewal_date' => now()->addMonth()->format('d M Y'),
        'storage' => $storageUsage,
    ];

    return view('dashboard', compact('menus', 'plans', 'devices', 'license'));
})->name('dashboard')->middleware('auth');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Developer Routes (Admin Panel)
Route::middleware(['auth'])->prefix('developer')->group(function () {
    Route::get('/', [DeveloperController::class, 'index'])->name('developer.dashboard');
    Route::get('/devices', [DeviceManagementController::class, 'index'])->name('developer.devices.index');
    Route::post('/devices/pairing-code', [DeviceManagementController::class, 'storePairingCode'])->name('developer.devices.pairing-code.store');
    Route::post('/devices/{device}/revoke', [DeviceManagementController::class, 'revoke'])->name('developer.devices.revoke');
    Route::post('/devices/{device}/reactivate', [DeviceManagementController::class, 'reactivate'])->name('developer.devices.reactivate');

    // Event Monitoring
    Route::get('/events', [EventManagementController::class, 'index'])->name('developer.events.index');
    Route::get('/events/{event}/manage', [EventManagementController::class, 'manage'])->name('developer.events.manage');
    Route::post('/events/{event}/assign-device', [EventManagementController::class, 'assignDevice'])->name('developer.events.assign-device');
    Route::post('/events/{event}/unassign-device', [EventManagementController::class, 'unassignDevice'])->name('developer.events.unassign-device');

    // Template Management
    Route::get('/templates', [TemplateManagementController::class, 'index'])->name('developer.templates.index');
    Route::get('/templates/create', [TemplateManagementController::class, 'create'])->name('developer.templates.create');
    Route::post('/templates', [TemplateManagementController::class, 'store'])->name('developer.templates.store');
    Route::get('/templates/{template}/edit', [TemplateManagementController::class, 'edit'])->name('developer.templates.edit');
    Route::put('/templates/{template}', [TemplateManagementController::class, 'update'])->name('developer.templates.update');

    // License & Subscription Control
    Route::get('/licenses', [LicenseManagementController::class, 'index'])->name('developer.licenses.index');
    Route::get('/licenses/plans', [LicenseManagementController::class, 'plans'])->name('developer.licenses.plans');
    Route::get('/licenses/users', [LicenseManagementController::class, 'users'])->name('developer.licenses.users');
    Route::post('/licenses/users/{user}/assign-plan', [LicenseManagementController::class, 'assignPlan'])->name('developer.licenses.users.assign-plan');
    Route::get('/monetization', [LicenseManagementController::class, 'monetization'])->name('developer.monetization.index');
    Route::post('/monetization', [LicenseManagementController::class, 'saveMonetization'])->name('developer.monetization.store');
    
    // Articles CRUD
    Route::get('/articles/create', [DeveloperController::class, 'createArticle'])->name('developer.articles.create');
    Route::get('/articles/{id}/edit', [DeveloperController::class, 'editArticle'])->name('developer.articles.edit');
    Route::post('/articles', [DeveloperController::class, 'storeArticle'])->name('developer.articles.store');
    Route::post('/articles/{id}', [DeveloperController::class, 'updateArticle'])->name('developer.articles.update');
    Route::delete('/articles/{id}', [DeveloperController::class, 'destroyArticle'])->name('developer.articles.destroy');
    Route::post('/articles/upload-image', [DeveloperController::class, 'uploadInlineImage'])->name('developer.articles.upload-image');
    
    // Pricing CRUD
    Route::post('/pricing', [DeveloperController::class, 'storePricing'])->name('developer.pricing.store');
    Route::post('/pricing/{id}', [DeveloperController::class, 'updatePricing'])->name('developer.pricing.update');
    Route::delete('/pricing/{id}', [DeveloperController::class, 'destroyPricing'])->name('developer.pricing.destroy');
    
    // Menus Manager
    Route::post('/menus', [DeveloperController::class, 'storeMenu'])->name('developer.menus.store');
    Route::delete('/menus/{id}', [DeveloperController::class, 'destroyMenu'])->name('developer.menus.destroy');

    // Categories Manager (CRUD)
    Route::post('/categories', [DeveloperController::class, 'storeCategory'])->name('developer.categories.store');
    Route::post('/categories/{id}', [DeveloperController::class, 'updateCategory'])->name('developer.categories.update');
    Route::delete('/categories/{id}', [DeveloperController::class, 'destroyCategory'])->name('developer.categories.destroy');

    // Static Pages Manager (CRUD Style)
    Route::get('/static-pages/create', [DeveloperController::class, 'createStaticPage'])->name('developer.static-pages.create');
    Route::post('/static-pages', [DeveloperController::class, 'storeStaticPage'])->name('developer.static-pages.store');
    Route::get('/static-pages/{id}/edit', [DeveloperController::class, 'editStaticPage'])->name('developer.static-pages.edit');
    Route::post('/static-pages/{id}', [DeveloperController::class, 'updateStaticPage'])->name('developer.static-pages.update');
    Route::delete('/static-pages/{id}', [DeveloperController::class, 'destroyStaticPage'])->name('developer.static-pages.destroy');

    // Payment Gateways settings
    Route::post('/payment-gateways/{id}', [DeveloperController::class, 'updatePaymentGateway'])->name('developer.payment-gateways.update');

    // Support Tickets (Dev/Team Side)
    Route::post('/tickets/{id}/reply', [DeveloperController::class, 'replyTicket'])->name('developer.tickets.reply');
    Route::post('/tickets/{id}/status', [DeveloperController::class, 'updateTicketStatus'])->name('developer.tickets.status');

    // Team Users Management
    Route::post('/users', [DeveloperController::class, 'storeUser'])->name('developer.users.store');
    Route::post('/users/{id}', [DeveloperController::class, 'updateUser'])->name('developer.users.update');
    Route::delete('/users/{id}', [DeveloperController::class, 'destroyUser'])->name('developer.users.destroy');

    // Custom Roles & Permissions Management
    Route::post('/roles', [DeveloperController::class, 'storeRole'])->name('developer.roles.store');
    Route::post('/roles/{id}', [DeveloperController::class, 'updateRole'])->name('developer.roles.update');
    Route::delete('/roles/{id}', [DeveloperController::class, 'destroyRole'])->name('developer.roles.destroy');
});

// Client tickets routes
Route::middleware('auth')->group(function () {
    Route::get('/client/tickets', [DeveloperController::class, 'clientTickets'])->name('client.tickets');
    Route::post('/client/tickets', [DeveloperController::class, 'storeClientTicket'])->name('client.tickets.store');
    Route::post('/client/tickets/{id}/messages', [DeveloperController::class, 'storeClientMessage'])->name('client.messages.store');

    // Client photobooth events & gallery routes
    Route::get('/dashboard/events', [GalleryController::class, 'index'])->name('client.events.index');
    Route::get('/dashboard/events/create', [ClientEventManagementController::class, 'create'])->name('client.events.create');
    Route::post('/dashboard/events', [ClientEventManagementController::class, 'store'])->name('client.events.store');
    Route::get('/dashboard/events/{event}/edit', [ClientEventManagementController::class, 'edit'])->name('client.events.edit');
    Route::put('/dashboard/events/{event}', [ClientEventManagementController::class, 'update'])->name('client.events.update');
    Route::get('/dashboard/events/{event}/manage', [GalleryController::class, 'manage'])->name('client.events.manage');
    Route::put('/dashboard/events/{event}/cloud-settings', [GalleryController::class, 'updateCloudSettings'])->name('client.events.cloud-settings.update');
    Route::get('/dashboard/events/{event}', [GalleryController::class, 'show'])->name('client.events.show');
    Route::get('/dashboard/events/{event}/gallery', [GalleryController::class, 'gallery'])->name('client.events.gallery');
});
