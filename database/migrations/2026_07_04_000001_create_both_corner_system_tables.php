<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role to users table if it doesn't exist
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user'); // 'admin' or 'user'
            });
        }

        // Articles Table
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('General');
            $table->text('content');
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // Pricing Plans Table
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->string('billing_period')->default('monthly'); // 'monthly', 'annual'
            $table->string('is_internal')->default('Internal'); // 'Internal', 'DSLRBooth'
            $table->string('payment_method')->default('Online'); // 'Online', 'Voucher', 'Hybrid'
            $table->text('features'); // JSON string of features
            $table->timestamps();
        });

        // Navigation Menus Table
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url');
            $table->string('type')->default('landing_page'); // 'landing_page', 'user_dashboard'
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Client Devices Table
        Schema::create('client_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('device_name');
            $table->string('platform')->default('Windows'); // 'Windows', 'Android'
            $table->string('camera_status')->default('Connected'); // 'Connected', 'Disconnected'
            $table->boolean('is_online')->default(true);
            $table->timestamp('last_active_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
        Schema::dropIfExists('client_devices');
        Schema::dropIfExists('navigation_menus');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('articles');
    }
};
