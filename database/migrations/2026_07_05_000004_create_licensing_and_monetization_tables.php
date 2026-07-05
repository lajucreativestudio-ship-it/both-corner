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
        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price_monthly', 12, 2)->nullable();
                $table->decimal('price_yearly', 12, 2)->nullable();
                $table->string('currency')->default('IDR');
                $table->integer('max_events')->nullable();
                $table->integer('max_devices')->nullable();
                $table->integer('max_templates')->nullable();
                $table->boolean('custom_template_upload')->default(false);
                $table->boolean('watermark_enabled')->default(true);
                $table->boolean('ads_enabled')->default(true);
                $table->boolean('admob_enabled')->default(true);
                $table->boolean('adsense_enabled')->default(true);
                $table->boolean('custom_branding')->default(false);
                $table->boolean('raw_download_enabled')->default(true);
                $table->boolean('public_gallery_enabled')->default(true);
                $table->string('status')->default('active'); // active, inactive
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_subscriptions')) {
            Schema::create('user_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('subscription_plan_id')->nullable()->constrained()->nullOnDelete();
                $table->string('source')->default('web'); // web, google_play, manual, free
                $table->string('status')->default('active'); // free, trial, active, expired, cancelled
                $table->timestamp('started_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('trial_ends_at')->nullable();
                $table->string('external_reference')->nullable();
                $table->json('metadata_json')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('status');
            });
        }

        if (!Schema::hasTable('feature_flags')) {
            Schema::create('feature_flags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('subscription_plan_id')->nullable()->constrained()->cascadeOnDelete();
                $table->string('feature_key');
                $table->json('feature_value')->nullable();
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();

                $table->index('feature_key');
            });
        }

        if (!Schema::hasTable('monetization_settings')) {
            Schema::create('monetization_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('group')->default('general');
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'current_plan_id')) {
                    $table->foreignId('current_plan_id')->nullable()->after('remember_token')->constrained('subscription_plans')->nullOnDelete();
                }

                if (!Schema::hasColumn('users', 'subscription_status')) {
                    $table->string('subscription_status')->default('free')->after('current_plan_id'); // free, trial, active, expired, cancelled
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'current_plan_id')) {
                    $table->dropForeign(['current_plan_id']);
                    $table->dropColumn('current_plan_id');
                }

                if (Schema::hasColumn('users', 'subscription_status')) {
                    $table->dropColumn('subscription_status');
                }
            });
        }

        Schema::dropIfExists('monetization_settings');
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
