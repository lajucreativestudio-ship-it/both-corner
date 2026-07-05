<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('photobooth_events')) {
            Schema::create('photobooth_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('slug');
                $table->date('event_date')->nullable();
                $table->string('location')->nullable();
                $table->string('status')->default('draft');
                $table->string('cover_photo_path')->nullable();
                $table->string('gallery_visibility')->default('private');
                $table->timestamps();

                $table->unique(['user_id', 'slug']);
                $table->index(['user_id', 'status']);
                $table->index('event_date');
            });
        }

        if (!Schema::hasTable('event_settings')) {
            Schema::create('event_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photobooth_event_id')->constrained()->cascadeOnDelete();
                $table->string('layout_type')->default('classic');
                $table->unsignedSmallInteger('countdown_seconds')->default(5);
                $table->unsignedSmallInteger('capture_count')->default(3);
                $table->boolean('retake_enabled')->default(true);
                $table->boolean('print_enabled')->default(false);
                $table->boolean('watermark_enabled')->default(false);
                $table->string('overlay_path')->nullable();
                $table->string('background_path')->nullable();
                $table->json('config_json')->nullable();
                $table->timestamps();

                $table->unique('photobooth_event_id');
            });
        }

        if (!Schema::hasTable('device_pairing_codes')) {
            Schema::create('device_pairing_codes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('code')->unique();
                $table->string('device_name')->nullable();
                $table->string('platform')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('used_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'used_at']);
                $table->index('expires_at');
            });
        }

        if (!Schema::hasTable('event_photos')) {
            Schema::create('event_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photobooth_event_id')->constrained()->cascadeOnDelete();
                $table->foreignId('client_device_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('file_path');
                $table->string('thumbnail_path')->nullable();
                $table->string('original_filename')->nullable();
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->json('metadata_json')->nullable();
                $table->timestamp('uploaded_at')->nullable();
                $table->timestamps();

                $table->index(['photobooth_event_id', 'uploaded_at']);
                $table->index(['user_id', 'uploaded_at']);
                $table->index('client_device_id');
            });
        }

        if (Schema::hasTable('client_devices')) {
            Schema::table('client_devices', function (Blueprint $table) {
                if (!Schema::hasColumn('client_devices', 'device_uuid')) {
                    $table->string('device_uuid')->nullable()->unique()->after('id');
                }

                if (!Schema::hasColumn('client_devices', 'api_token_hash')) {
                    $table->string('api_token_hash', 64)->nullable()->unique()->after('device_uuid');
                }

                if (!Schema::hasColumn('client_devices', 'pairing_code_id')) {
                    $table->foreignId('pairing_code_id')->nullable()->after('api_token_hash')->constrained('device_pairing_codes')->nullOnDelete();
                }

                if (!Schema::hasColumn('client_devices', 'current_event_id')) {
                    $table->foreignId('current_event_id')->nullable()->after('pairing_code_id')->constrained('photobooth_events')->nullOnDelete();
                }

                if (!Schema::hasColumn('client_devices', 'app_version')) {
                    $table->string('app_version')->nullable()->after('current_event_id');
                }

                if (!Schema::hasColumn('client_devices', 'os_version')) {
                    $table->string('os_version')->nullable()->after('app_version');
                }

                if (!Schema::hasColumn('client_devices', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable()->after('os_version');
                }

                if (!Schema::hasColumn('client_devices', 'last_heartbeat_at')) {
                    $table->timestamp('last_heartbeat_at')->nullable()->index()->after('last_active_at');
                }

                if (!Schema::hasColumn('client_devices', 'revoked_at')) {
                    $table->timestamp('revoked_at')->nullable()->index()->after('last_heartbeat_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('client_devices')) {
            if (Schema::hasColumn('client_devices', 'current_event_id')) {
                $this->dropForeignSafely('client_devices_current_event_id_foreign');
            }

            if (Schema::hasColumn('client_devices', 'pairing_code_id')) {
                $this->dropForeignSafely('client_devices_pairing_code_id_foreign');
            }

            Schema::table('client_devices', function (Blueprint $table) {
                $columns = [
                    'device_uuid',
                    'api_token_hash',
                    'pairing_code_id',
                    'current_event_id',
                    'app_version',
                    'os_version',
                    'ip_address',
                    'last_heartbeat_at',
                    'revoked_at',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('client_devices', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('event_photos');
        Schema::dropIfExists('event_settings');
        Schema::dropIfExists('device_pairing_codes');
        Schema::dropIfExists('photobooth_events');
    }

    private function dropForeignSafely(string $index): void
    {
        try {
            DB::statement("ALTER TABLE client_devices DROP FOREIGN KEY {$index}");
        } catch (Throwable) {
            //
        }
    }
};
