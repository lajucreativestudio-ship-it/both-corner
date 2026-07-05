<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\PricingPlan;
use App\Models\NavigationMenu;
use App\Models\ClientDevice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Initialize default subscription plans
        app(\App\Services\LicenseService::class)->initializeDefaultPlans();

        // Seed default roles
        $adminRole = \App\Models\Role::create([
            'name' => 'Admin Utama',
            'description' => 'Akses penuh seluruh konfigurasi cloud SaaS.',
            'permissions' => ['view_summary', 'manage_articles', 'manage_static_pages', 'manage_navigation_menus', 'manage_payment_gateways', 'manage_revenue', 'manage_pricing', 'manage_users', 'manage_chats'],
        ]);

        $teamRole = \App\Models\Role::create([
            'name' => 'Team Monitor',
            'description' => 'Akses terbatas untuk monitoring device, artikel, dan chat bantuan.',
            'permissions' => ['view_summary', 'manage_articles', 'manage_chats'],
        ]);

        $userRole = \App\Models\Role::create([
            'name' => 'Client / Member',
            'description' => 'Akses standar dashboard pengguna.',
            'permissions' => [],
        ]);

        // 1. Users
        User::create([
            'name' => 'Laju Studio',
            'email' => 'lajucreativestudio@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'role_id' => $userRole->id,
        ]);

        User::create([
            'name' => 'Developer Pengelola',
            'email' => 'developer@bothcorner.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'role_id' => $adminRole->id,
        ]);

        // 2. Pricing Plans
        $plans = [
            [
                'name' => 'Online Starter Plan Internal',
                'price' => 448500.00,
                'billing_period' => 'monthly',
                'is_internal' => 'Internal',
                'payment_method' => 'Online',
                'features' => json_encode([
                    'Kelola hingga 1 perangkat',
                    'Operasional DSLRBooth otomatis (buka & tutup)',
                    'Hemat 6% untuk pembayaran tahunan',
                    'Setting PIN, pengaturan harga, custom branding',
                    'Penyimpanan hingga 2.0 GB',
                    'Konfirmasi pembayaran otomatis secara online',
                    'Indikator timeout untuk sesi foto'
                ])
            ],
            [
                'name' => 'Starter Plan Internal',
                'price' => 448500.00,
                'billing_period' => 'monthly',
                'is_internal' => 'Internal',
                'payment_method' => 'Voucher',
                'features' => json_encode([
                    'Konfirmasi pembayaran otomatis secara offline',
                    'Hemat 6% untuk pembayaran tahunan',
                    'Setting PIN, pengaturan harga, custom branding',
                    'Penyimpanan hingga 2.0 GB',
                    'Kelola hingga 1 perangkat',
                    'Operasional DSLRBooth otomatis (buka & tutup)',
                    'Indikator timeout untuk sesi foto'
                ])
            ],
            [
                'name' => 'Advance Plan Internal',
                'price' => 1290000.00,
                'billing_period' => 'monthly',
                'is_internal' => 'Internal',
                'payment_method' => 'Hybrid',
                'features' => json_encode([
                    'Kelola hingga 4 perangkat',
                    'Konfirmasi otomatis untuk offline & online',
                    'Timeout & notifikasi sesi, pengaturan timer',
                    'Operasional DSLRBooth otomatis (buka & tutup)',
                    'Penyimpanan hingga 12.0 GB',
                    'Hemat 8% untuk pembayaran tahunan',
                    'Mendukung cetak foto lebih dari satu (Multi Print)',
                    'Setting PIN, harga di aplikasi, custom branding'
                ])
            ],
            [
                'name' => 'Partner Plan Internal',
                'price' => 15000.00,
                'billing_period' => 'monthly',
                'is_internal' => 'Internal',
                'payment_method' => 'Online',
                'features' => json_encode([
                    'Penyimpanan hingga 2.0 GB',
                    'Konfirmasi pembayaran otomatis secara online',
                    'Operasional DSLRBooth otomatis (buka & tutup)',
                    'Kelola hingga 3 perangkat',
                    'Hemat 6% untuk pembayaran tahunan',
                    'Setting PIN, pengaturan harga, custom branding',
                    'Mendukung cetak foto lebih dari satu (Multi Print)',
                    'Indikator timeout untuk sesi foto'
                ])
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::create($plan);
        }

        // 3. Navigation Menus
        $menus = [
            // Landing Page
            ['title' => 'Fitur Utama', 'url' => '#fitur', 'type' => 'landing_page', 'order' => 1],
            ['title' => 'Mode & Solusi', 'url' => '#use-cases', 'type' => 'landing_page', 'order' => 2],
            ['title' => 'Kustomisasi', 'url' => '#kustomisasi', 'type' => 'landing_page', 'order' => 3],
            ['title' => 'Cara Kerja', 'url' => '#cara-kerja', 'type' => 'landing_page', 'order' => 4],
            ['title' => 'Harga Paket', 'url' => '#pricing', 'type' => 'landing_page', 'order' => 5],
            ['title' => 'Blog', 'url' => '#blog', 'type' => 'landing_page', 'order' => 6],

            // User Dashboard
            ['title' => 'Events', 'url' => '#panel-events', 'type' => 'user_dashboard', 'order' => 1],
            ['title' => 'Settings', 'url' => '#panel-settings', 'type' => 'user_dashboard', 'order' => 2],
            ['title' => 'Subscriptions', 'url' => '#panel-subscriptions', 'type' => 'user_dashboard', 'order' => 3],
            ['title' => 'Refer & Earn', 'url' => '#panel-refer-earn', 'type' => 'user_dashboard', 'order' => 4],
            ['title' => 'Booth Copilot', 'url' => '#panel-copilot', 'type' => 'user_dashboard', 'order' => 5],
            ['title' => 'Help', 'url' => '#panel-help', 'type' => 'user_dashboard', 'order' => 6],
        ];

        foreach ($menus as $menu) {
            NavigationMenu::create($menu);
        }

        // 4. Articles (Blog)
        $articles = [
            [
                'title' => 'Daftar Kamera DSLR & Mirrorless yang Kompatibel dengan Both Corner Client',
                'slug' => 'daftar-kamera-kompatibel-both-corner',
                'category' => 'Compatible Devices',
                'content' => 'Untuk memastikan kelancaran operasional photobooth Anda di lapangan, aplikasi client Both Corner mendukung berbagai tipe kamera DSLR dan Mirrorless dari brand terkemuka seperti Canon, Nikon, dan Sony. Hubungkan kamera Anda menggunakan kabel USB berkecepatan tinggi langsung ke laptop Windows atau tablet Android Anda. Brand Canon tipe EOS (seperti EOS 1500D, 3000D, 80D, M50 Mark II, dll.) memiliki kompatibilitas plug-and-play terbaik dengan SDK bawaan kami.',
                'image_url' => 'login_slide_2.png'
            ],
            [
                'title' => 'Catatan Perubahan Versi (Changelog v2.4.0) - Peningkatan Sistem Sinkronisasi Offline-First',
                'slug' => 'changelog-v240-offline-first',
                'category' => 'Version Upgrade',
                'content' => 'Pada rilis versi terbaru v2.4.0, Both Corner memperkenalkan optimasi antrean sinkronisasi foto lokal (Offline-First Queue). Ketika jaringan internet di lokasi event mengalami gangguan, hasil jepretan kamera DSLR akan secara aman disimpan di penyimpanan internal perangkat client. Sistem akan secara otomatis mengunggah file tersebut ke Galeri Digital Online saat koneksi terdeteksi aktif kembali, memastikan kelancaran alur pemindaian barcode unduhan bagi para tamu.',
                'image_url' => 'login_slide_1.png'
            ],
            [
                'title' => 'Cara Melakukan Setup Printer Cetak Foto 4x6 untuk Kecepatan Tinggi di Lapangan',
                'slug' => 'setup-printer-foto-kecepatan-tinggi',
                'category' => 'Guides',
                'content' => 'Kecepatan cetak kertas 4x6 strip photobooth merupakan kunci sukses kepuasan tamu di event Anda. Panduan ini menjelaskan langkah demi langkah untuk mengonfigurasi driver printer DNP atau Epson L805 dengan aplikasi Windows Both Corner. Pastikan resolusi default diatur pada 300 DPI dan fitur multi-page printing aktif untuk meminimalkan waktu pemrosesan cetak per sesi foto.',
                'image_url' => 'photobooth_strip.png'
            ]
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }

        // 5. Client Devices (Active monitoring)
        $devices = [
            [
                'device_name' => 'LAPTOP-LAJU-01 (Windows 11)',
                'platform' => 'Windows',
                'camera_status' => 'Connected',
                'is_online' => true,
            ],
            [
                'device_name' => 'TABLET-BOOTH-02 (Android 13)',
                'platform' => 'Android',
                'camera_status' => 'Connected',
                'is_online' => true,
            ],
            [
                'device_name' => 'LAPTOP-BACKUP-03 (Windows 10)',
                'platform' => 'Windows',
                'camera_status' => 'Disconnected',
                'is_online' => false,
            ]
        ];

        foreach ($devices as $dev) {
            ClientDevice::create($dev);
        }

        // 6. Blog Categories
        $categories = [
            ['name' => 'Compatible Devices', 'slug' => 'compatible-devices'],
            ['name' => 'Version Upgrade', 'slug' => 'version-upgrade'],
            ['name' => 'Guides', 'slug' => 'guides'],
            ['name' => 'General', 'slug' => 'general'],
        ];
        foreach ($categories as $cat) {
            \App\Models\BlogCategory::create($cat);
        }

        // 7. Static Pages
        \App\Models\StaticPage::create([
            'slug' => 'landing',
            'title' => 'Beranda',
            'hero_title' => 'Sistem Photobooth Cloud Otomatis Tanpa Ribet',
            'hero_subtitle' => 'Jalankan bisnis photobooth Anda secara mandiri dengan alur pembayaran otomatis QRIS, cetak foto instan, sinkronisasi offline-first, dan unduhan barcode online yang super cepat.',
            'cta_text' => 'Coba Gratis Sekarang',
        ]);

        // 8. Payment Gateways
        $gateways = [
            [
                'name' => 'Midtrans',
                'client_id' => 'SB-Mid-client-abc123XYZ',
                'server_key' => 'SB-Mid-server-7890defGHI',
                'is_active' => true,
                'is_sandbox' => true,
            ],
            [
                'name' => 'Xendit',
                'api_key' => 'xnd_development_jk1289ahskashas19',
                'is_active' => false,
                'is_sandbox' => true,
            ],
            [
                'name' => 'Tripay',
                'api_key' => 'TP-api-key-9999-xxxx',
                'is_active' => false,
                'is_sandbox' => true,
            ],
        ];
        foreach ($gateways as $gw) {
            \App\Models\PaymentGateway::create($gw);
        }

        // 9. Transactions (Revenue)
        $txs = [
            ['user_name' => 'Andi Wijaya', 'plan_name' => 'Online Starter Plan Internal', 'amount' => 448500.00, 'gateway' => 'Midtrans', 'status' => 'success', 'created_at' => now()->subDays(2)],
            ['user_name' => 'Studio Foto Keren', 'plan_name' => 'Advance Plan Internal', 'amount' => 1290000.00, 'gateway' => 'Midtrans', 'status' => 'success', 'created_at' => now()->subDays(4)],
            ['user_name' => 'Budi Santoso', 'plan_name' => 'Partner Plan Internal', 'amount' => 15000.00, 'gateway' => 'Xendit', 'status' => 'success', 'created_at' => now()->subDays(5)],
            ['user_name' => 'Laju Creative', 'plan_name' => 'Advance Plan Internal', 'amount' => 1290000.00, 'gateway' => 'Tripay', 'status' => 'success', 'created_at' => now()->subDays(10)],
            ['user_name' => 'Rina Amalia', 'plan_name' => 'Online Starter Plan Internal', 'amount' => 448500.00, 'gateway' => 'Midtrans', 'status' => 'success', 'created_at' => now()->subDays(15)],
        ];
        foreach ($txs as $tx) {
            \App\Models\Transaction::create($tx);
        }

        // 10. Team Member User
        $teamUser = User::create([
            'name' => 'Ferry Monitor',
            'email' => 'team@bothcorner.com',
            'password' => bcrypt('password123'),
            'role' => 'team',
            'role_id' => \App\Models\Role::where('name', 'Team Monitor')->first()->id,
        ]);

        // Find standard user for tickets
        $standardUser = User::where('role', 'user')->first();

        if ($standardUser) {
            // Support Tickets and Messages
            $t1 = \App\Models\SupportTicket::create([
                'user_id' => $standardUser->id,
                'subject' => 'Masalah Kamera DSLR Tidak Terdeteksi di Windows PC',
                'status' => 'open',
            ]);
            \App\Models\SupportMessage::create([
                'support_ticket_id' => $t1->id,
                'sender_id' => $standardUser->id,
                'message' => 'Halo tim support, saya mengalami masalah di mana kamera Canon EOS 1500D saya tidak terbaca oleh aplikasi client di PC Windows 10. Apakah ada driver khusus yang perlu dipasang?',
            ]);

            $t2 = \App\Models\SupportTicket::create([
                'user_id' => $standardUser->id,
                'subject' => 'Tanya Seputar Metode Pembayaran QRIS Otomatis',
                'status' => 'on_going',
            ]);
            \App\Models\SupportMessage::create([
                'support_ticket_id' => $t2->id,
                'sender_id' => $standardUser->id,
                'message' => 'Untuk integrasi QRIS Midtrans, apakah status pembayarannya langsung instan terdeteksi oleh Both Corner atau ada delay?',
            ]);
            \App\Models\SupportMessage::create([
                'support_ticket_id' => $t2->id,
                'sender_id' => User::where('role', 'admin')->first()->id,
                'message' => 'Halo! Benar sekali, status transaksi via QRIS Midtrans terdeteksi secara real-time instan berkat callback webhook dari server Midtrans ke cloud dashboard Both Corner.',
            ]);

            $t3 = \App\Models\SupportTicket::create([
                'user_id' => $standardUser->id,
                'subject' => 'Pertanyaan Upgrade ke Paket Advance Plan',
                'status' => 'closed',
            ]);
            \App\Models\SupportMessage::create([
                'support_ticket_id' => $t3->id,
                'sender_id' => $standardUser->id,
                'message' => 'Saya ingin upgrade dari Starter Plan ke Advance Plan. Apakah harga dikurangi secara prorata?',
            ]);
            \App\Models\SupportMessage::create([
                'support_ticket_id' => $t3->id,
                'sender_id' => User::where('role', 'admin')->first()->id,
                'message' => 'Halo, betul. Sistem kami akan menghitung sisa hari pada paket aktif Anda saat ini dan memotong harga upgrade secara otomatis pada tagihan baru Anda.',
            ]);
        }

        // 11. Photobooth Global Templates Presets
        $presets = [
            [
                'name' => '4x6 Portrait Single',
                'template_type' => 'photo_4x6_portrait',
                'orientation' => 'portrait',
                'canvas_width' => 1200,
                'canvas_height' => 1800,
                'capture_count' => 1,
                'is_global' => true,
                'status' => 'active',
                'timing_json' => [
                    'initial_countdown' => 5,
                    'between_capture_delay' => 2,
                    'preview_duration' => 3,
                    'retake_timeout' => 10,
                    'final_preview_duration' => 8,
                    'idle_timeout' => 30
                ],
                'photo_slots_json' => [
                    ['slot_number' => 1, 'x' => 50, 'y' => 50, 'width' => 1100, 'height' => 1466]
                ]
            ],
            [
                'name' => '4x6 Portrait 3 Photos',
                'template_type' => 'photo_4x6_portrait',
                'orientation' => 'portrait',
                'canvas_width' => 1200,
                'canvas_height' => 1800,
                'capture_count' => 3,
                'is_global' => true,
                'status' => 'active',
                'timing_json' => [
                    'initial_countdown' => 5,
                    'between_capture_delay' => 2,
                    'preview_duration' => 3,
                    'retake_timeout' => 10,
                    'final_preview_duration' => 8,
                    'idle_timeout' => 30
                ],
                'photo_slots_json' => [
                    ['slot_number' => 1, 'x' => 100, 'y' => 80, 'width' => 1000, 'height' => 450],
                    ['slot_number' => 2, 'x' => 100, 'y' => 580, 'width' => 1000, 'height' => 450],
                    ['slot_number' => 3, 'x' => 100, 'y' => 1080, 'width' => 1000, 'height' => 450]
                ]
            ],
            [
                'name' => '2x6 Strip 3 Photos',
                'template_type' => 'strip_2x6',
                'orientation' => 'portrait',
                'canvas_width' => 600,
                'canvas_height' => 1800,
                'capture_count' => 3,
                'is_global' => true,
                'status' => 'active',
                'timing_json' => [
                    'initial_countdown' => 5,
                    'between_capture_delay' => 2,
                    'preview_duration' => 3,
                    'retake_timeout' => 10,
                    'final_preview_duration' => 8,
                    'idle_timeout' => 30
                ],
                'photo_slots_json' => [
                    ['slot_number' => 1, 'x' => 50, 'y' => 80, 'width' => 500, 'height' => 375],
                    ['slot_number' => 2, 'x' => 50, 'y' => 500, 'width' => 500, 'height' => 375],
                    ['slot_number' => 3, 'x' => 50, 'y' => 920, 'width' => 500, 'height' => 375]
                ]
            ],
            [
                'name' => '2x6 Strip 4 Photos',
                'template_type' => 'strip_2x6',
                'orientation' => 'portrait',
                'canvas_width' => 600,
                'canvas_height' => 1800,
                'capture_count' => 4,
                'is_global' => true,
                'status' => 'active',
                'timing_json' => [
                    'initial_countdown' => 5,
                    'between_capture_delay' => 2,
                    'preview_duration' => 3,
                    'retake_timeout' => 10,
                    'final_preview_duration' => 8,
                    'idle_timeout' => 30
                ],
                'photo_slots_json' => [
                    ['slot_number' => 1, 'x' => 50, 'y' => 60, 'width' => 500, 'height' => 333],
                    ['slot_number' => 2, 'x' => 50, 'y' => 430, 'width' => 500, 'height' => 333],
                    ['slot_number' => 3, 'x' => 50, 'y' => 800, 'width' => 500, 'height' => 333],
                    ['slot_number' => 4, 'x' => 50, 'y' => 1170, 'width' => 500, 'height' => 333]
                ]
            ]
        ];

        foreach ($presets as $preset) {
            \App\Models\PhotoboothTemplate::create($preset);
        }
    }
}
