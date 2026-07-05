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
        if (!Schema::hasTable('booth_sessions')) {
            Schema::create('booth_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photobooth_event_id')->constrained()->cascadeOnDelete();
                $table->foreignId('client_device_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('photobooth_template_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_code')->unique();
                $table->string('public_token')->unique();
                $table->string('mode_type')->default('photo'); // photo, gif, boomerang, video
                $table->string('status')->default('completed'); // started, processing, completed, failed
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('qr_code_path')->nullable();
                $table->json('metadata_json')->nullable();
                $table->timestamps();

                $table->index('photobooth_event_id');
                $table->index('public_token');
            });
        }

        if (Schema::hasTable('event_photos')) {
            Schema::table('event_photos', function (Blueprint $table) {
                if (!Schema::hasColumn('event_photos', 'booth_session_id')) {
                    $table->foreignId('booth_session_id')->nullable()->after('client_device_id')->constrained('booth_sessions')->nullOnDelete();
                }

                if (!Schema::hasColumn('event_photos', 'photo_type')) {
                    $table->string('photo_type')->default('final')->after('file_path'); // raw, final, print, thumbnail
                }

                if (!Schema::hasColumn('event_photos', 'step_number')) {
                    $table->unsignedInteger('step_number')->nullable()->after('photo_type');
                }

                if (!Schema::hasColumn('event_photos', 'public_visibility')) {
                    $table->string('public_visibility')->default('visible')->after('metadata_json'); // visible, hidden
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('event_photos')) {
            Schema::table('event_photos', function (Blueprint $table) {
                if (Schema::hasColumn('event_photos', 'booth_session_id')) {
                    $table->dropForeign(['booth_session_id']);
                    $table->dropColumn('booth_session_id');
                }

                if (Schema::hasColumn('event_photos', 'photo_type')) {
                    $table->dropColumn('photo_type');
                }

                if (Schema::hasColumn('event_photos', 'step_number')) {
                    $table->dropColumn('step_number');
                }

                if (Schema::hasColumn('event_photos', 'public_visibility')) {
                    $table->dropColumn('public_visibility');
                }
            });
        }

        Schema::dropIfExists('booth_sessions');
    }
};
