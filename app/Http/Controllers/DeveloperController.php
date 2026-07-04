<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\PricingPlan;
use App\Models\NavigationMenu;
use App\Models\ClientDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeveloperController extends Controller
{
    public function checkPermission($permission)
    {
        if (!auth()->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Super-admin escape hatch
        if ($user->email === 'developer@bothcorner.com') {
            return true;
        }

        // If the user has a custom role, check its JSON permissions
        if ($user->customRole) {
            $perms = $user->customRole->permissions ?? [];
            if (in_array($permission, $perms)) {
                return true;
            }
        }

        // Backward compatibility legacys
        if ($user->role === 'admin') {
            return true;
        }
        
        if ($user->role === 'team' && in_array($permission, ['view_summary', 'manage_articles', 'manage_chats'])) {
            return true;
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki izin: ' . str_replace('_', ' ', $permission));
    }

    public function index()
    {
        // Must be able to view summary to see main dashboard page
        $this->checkPermission('view_summary');

        $stats = [
            'total_users' => User::where('role', 'user')->count() + 45, // Add mock offset
            'total_devices' => ClientDevice::count(),
            'online_devices' => ClientDevice::where('is_online', true)->count(),
            'total_articles' => Article::count(),
            'total_revenue' => \App\Models\Transaction::where('status', 'success')->sum('amount'),
            'active_subscribers' => User::where('role', 'user')->count() + 18, // Mock active subscribers offset
            
            // Support Tickets Summary Stats
            'tickets_total' => \App\Models\SupportTicket::count(),
            'tickets_open' => \App\Models\SupportTicket::where('status', 'open')->count(),
            'tickets_ongoing' => \App\Models\SupportTicket::where('status', 'on_going')->count(),
            'tickets_closed' => \App\Models\SupportTicket::where('status', 'closed')->count(),
        ];

        $articles = Article::latest()->get();
        $plans = PricingPlan::all();
        $menus = NavigationMenu::orderBy('type')->orderBy('order')->get();
        $devices = ClientDevice::latest()->get();
        $categories = \App\Models\BlogCategory::orderBy('name')->get();
        $staticPages = \App\Models\StaticPage::orderBy('title')->get();
        $landingPage = \App\Models\StaticPage::where('slug', 'landing')->first();
        $gateways = \App\Models\PaymentGateway::all();
        $transactions = \App\Models\Transaction::latest()->get();
        $users = User::with('customRole')->orderBy('role')->orderBy('name')->get();
        $tickets = \App\Models\SupportTicket::with('user', 'messages.sender')->latest()->get();
        $roles = \App\Models\Role::orderBy('name')->get();

        return view('developer.dashboard', compact('stats', 'articles', 'plans', 'menus', 'devices', 'categories', 'staticPages', 'landingPage', 'gateways', 'transactions', 'users', 'tickets', 'roles'));
    }

    // Article management
    public function createArticle()
    {
        $this->checkPermission('manage_articles');
        $categories = \App\Models\BlogCategory::orderBy('name')->get();
        return view('developer.articles.create', compact('categories'));
    }

    public function editArticle($id)
    {
        $this->checkPermission('manage_articles');
        $article = Article::findOrFail($id);
        $categories = \App\Models\BlogCategory::orderBy('name')->get();
        return view('developer.articles.edit', compact('article', 'categories'));
    }

    public function storeArticle(Request $request)
    {
        $this->checkPermission('manage_articles');

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug',
            'category' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imageUrl = 'login_slide_1.png';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $imageUrl = 'storage/' . $path;
        }

        Article::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'category' => $request->category,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'seo_title' => $request->seo_title,
            'focus_keyword' => $request->focus_keyword,
            'meta_description' => $request->meta_description,
            'tags' => $request->tags,
        ]);

        return redirect()->route('developer.dashboard')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function updateArticle(Request $request, $id)
    {
        $this->checkPermission('manage_articles');

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug,' . $id,
            'category' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $article = Article::findOrFail($id);
        $imageUrl = $article->image_url;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $imageUrl = 'storage/' . $path;
        }

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'category' => $request->category,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'seo_title' => $request->seo_title,
            'focus_keyword' => $request->focus_keyword,
            'meta_description' => $request->meta_description,
            'tags' => $request->tags,
        ]);

        return redirect()->route('developer.dashboard')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function uploadInlineImage(Request $request)
    {
        $this->checkPermission('manage_articles');

        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $url = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunggah gambar.',
        ], 400);
    }

    public function destroyArticle($id)
    {
        $this->checkPermission('manage_articles');

        Article::findOrFail($id)->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    // Pricing Plan CRUD management
    public function storePricing(Request $request)
    {
        $this->checkPermission('manage_pricing');

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|string',
            'is_internal' => 'required|string',
            'payment_method' => 'required|string',
            'features' => 'required|string',
        ]);

        $features = json_encode(array_filter(explode("\n", str_replace("\r", "", $request->features))));

        PricingPlan::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'billing_period' => $request->billing_period,
            'is_internal' => $request->is_internal,
            'payment_method' => $request->payment_method,
            'features' => $features,
        ]);

        return back()->with('success', 'Paket harga baru berhasil ditambahkan.');
    }

    public function updatePricing(Request $request, $id)
    {
        $this->checkPermission('manage_pricing');

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|string',
            'is_internal' => 'required|string',
            'payment_method' => 'required|string',
            'features' => 'required|string',
        ]);

        $plan = PricingPlan::findOrFail($id);
        $features = json_encode(array_filter(explode("\n", str_replace("\r", "", $request->features))));

        $plan->update([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'billing_period' => $request->billing_period,
            'is_internal' => $request->is_internal,
            'payment_method' => $request->payment_method,
            'features' => $features,
        ]);

        return back()->with('success', 'Harga paket berhasil diperbarui.');
    }

    public function destroyPricing($id)
    {
        $this->checkPermission('manage_pricing');

        PricingPlan::findOrFail($id)->delete();
        return back()->with('success', 'Paket harga berhasil dihapus.');
    }

    // Menu Management
    public function storeMenu(Request $request)
    {
        $this->checkPermission('manage_navigation_menus');

        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string',
            'type' => 'required|string',
            'order' => 'required|integer',
        ]);

        NavigationMenu::create($request->only('title', 'url', 'type', 'order'));

        return back()->with('success', 'Menu navigasi berhasil ditambahkan.');
    }

    public function destroyMenu($id)
    {
        $this->checkPermission('manage_navigation_menus');

        NavigationMenu::findOrFail($id)->delete();
        return back()->with('success', 'Menu navigasi berhasil dihapus.');
    }

    // Category management
    public function storeCategory(Request $request)
    {
        $this->checkPermission('manage_articles');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_categories,slug',
        ]);

        \App\Models\BlogCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        return back()->with('success', 'Kategori blog berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, $id)
    {
        $this->checkPermission('manage_articles');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:blog_categories,slug,' . $id,
        ]);

        $cat = \App\Models\BlogCategory::findOrFail($id);
        $cat->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
        ]);

        return back()->with('success', 'Kategori blog berhasil diperbarui.');
    }

    public function destroyCategory($id)
    {
        $this->checkPermission('manage_articles');

        \App\Models\BlogCategory::findOrFail($id)->delete();
        return back()->with('success', 'Kategori blog berhasil dihapus.');
    }

    // Static Page Settings management
    public function createStaticPage()
    {
        $this->checkPermission('manage_static_pages');
        return view('developer.pages.create');
    }

    public function storeStaticPage(Request $request)
    {
        $this->checkPermission('manage_static_pages');

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:static_pages,slug',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'cta_text' => 'required|string|max:255',
        ]);

        \App\Models\StaticPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'hero_title' => $request->hero_title,
            'hero_subtitle' => $request->hero_subtitle,
            'cta_text' => $request->cta_text,
        ]);

        return redirect()->route('developer.dashboard')->with('success', 'Halaman statis baru berhasil dibuat.');
    }

    public function editStaticPage($id)
    {
        $this->checkPermission('manage_static_pages');
        $page = \App\Models\StaticPage::findOrFail($id);
        return view('developer.pages.edit', compact('page'));
    }

    public function updateStaticPage(Request $request, $id)
    {
        $this->checkPermission('manage_static_pages');

        $request->validate([
            'title' => 'required|string|max:255',
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'required|string',
            'cta_text' => 'required|string|max:255',
        ]);

        $page = \App\Models\StaticPage::findOrFail($id);
        $page->update([
            'title' => $request->title,
            'hero_title' => $request->hero_title,
            'hero_subtitle' => $request->hero_subtitle,
            'cta_text' => $request->cta_text,
        ]);

        return redirect()->route('developer.dashboard')->with('success', 'Konten halaman statis berhasil diperbarui.');
    }

    public function destroyStaticPage($id)
    {
        $this->checkPermission('manage_static_pages');

        \App\Models\StaticPage::findOrFail($id)->delete();
        return redirect()->route('developer.dashboard')->with('success', 'Halaman statis berhasil dihapus.');
    }

    // Payment Gateway management
    public function updatePaymentGateway(Request $request, $id)
    {
        $this->checkPermission('manage_payment_gateways');

        $request->validate([
            'is_active' => 'required|boolean',
            'is_sandbox' => 'required|boolean',
        ]);

        $gateway = \App\Models\PaymentGateway::findOrFail($id);
        $gateway->update([
            'is_active' => $request->is_active,
            'is_sandbox' => $request->is_sandbox,
            'client_id' => $request->client_id,
            'server_key' => $request->server_key,
            'api_key' => $request->api_key,
        ]);

        return back()->with('success', 'Pengaturan payment gateway ' . $gateway->name . ' berhasil diperbarui.');
    }

    // Team Users Management
    public function storeUser(Request $request)
    {
        $this->checkPermission('manage_users');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $roleRecord = \App\Models\Role::findOrFail($request->role_id);
        $legacyRole = 'user';
        if (Str::contains(strtolower($roleRecord->name), 'admin')) {
            $legacyRole = 'admin';
        } elseif (Str::contains(strtolower($roleRecord->name), 'team') || Str::contains(strtolower($roleRecord->name), 'monitor') || Str::contains(strtolower($roleRecord->name), 'support')) {
            $legacyRole = 'team';
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $legacyRole,
            'role_id' => $roleRecord->id,
        ]);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $this->checkPermission('manage_users');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $roleRecord = \App\Models\Role::findOrFail($request->role_id);
        $legacyRole = 'user';
        if (Str::contains(strtolower($roleRecord->name), 'admin')) {
            $legacyRole = 'admin';
        } elseif (Str::contains(strtolower($roleRecord->name), 'team') || Str::contains(strtolower($roleRecord->name), 'monitor') || Str::contains(strtolower($roleRecord->name), 'support')) {
            $legacyRole = 'team';
        }

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $legacyRole,
            'role_id' => $roleRecord->id,
        ]);

        if ($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return back()->with('success', 'Informasi pengguna berhasil diperbarui.');
    }

    public function destroyUser($id)
    {
        $this->checkPermission('manage_users');
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    // Support Tickets (Dev/Team Side)
    public function replyTicket(Request $request, $id)
    {
        $this->checkPermission('manage_chats');
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = \App\Models\SupportTicket::findOrFail($id);
        $ticket->update(['status' => 'on_going']);

        \App\Models\SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function updateTicketStatus(Request $request, $id)
    {
        $this->checkPermission('manage_chats');
        $request->validate([
            'status' => 'required|string|in:open,on_going,closed',
        ]);

        $ticket = \App\Models\SupportTicket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Status tiket berhasil diubah.');
    }

    // Client Tickets (Client Side APIs)
    public function clientTickets()
    {
        $tickets = \App\Models\SupportTicket::where('user_id', auth()->id())
            ->with(['messages.sender'])
            ->latest()
            ->get();
        return response()->json($tickets);
    }

    public function storeClientTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = \App\Models\SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        \App\Models\SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil dibuat.',
            'ticket' => $ticket->load('messages.sender'),
        ]);
    }

    public function storeClientMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = \App\Models\SupportTicket::where('user_id', auth()->id())->findOrFail($id);
        
        // Reopen ticket if closed
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        $message = \App\Models\SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim.',
            'message_data' => $message->load('sender'),
        ]);
    }

    // Custom Roles & Permissions Management
    public function storeRole(Request $request)
    {
        $this->checkPermission('manage_users');
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
        ]);

        \App\Models\Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions,
        ]);

        return back()->with('success', 'Peran (Role) baru berhasil dibuat.');
    }

    public function updateRole(Request $request, $id)
    {
        $this->checkPermission('manage_users');
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
        ]);

        $role = \App\Models\Role::findOrFail($id);
        
        if (in_array($role->name, ['Admin Utama', 'Team Monitor', 'Client / Member'])) {
            $role->update([
                'description' => $request->description,
                'permissions' => $request->permissions,
            ]);
        } else {
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
                'permissions' => $request->permissions,
            ]);
        }

        return back()->with('success', 'Peran (Role) berhasil diperbarui.');
    }

    public function destroyRole($id)
    {
        $this->checkPermission('manage_users');
        $role = \App\Models\Role::findOrFail($id);

        if (in_array($role->name, ['Admin Utama', 'Team Monitor', 'Client / Member'])) {
            return back()->withErrors(['error' => 'Peran bawaan sistem tidak boleh dihapus.']);
        }

        $role->delete();
        return back()->with('success', 'Peran berhasil dihapus.');
    }
}
