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
        if (!Schema::hasTable('photobooth_templates')) {
            Schema::create('photobooth_templates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('template_type')->default('photo_4x6_portrait');
                $table->string('orientation')->default('portrait');
                $table->unsignedInteger('canvas_width')->default(1200);
                $table->unsignedInteger('canvas_height')->default(1800);
                $table->unsignedSmallInteger('capture_count')->default(1);
                $table->string('overlay_path')->nullable();
                $table->string('background_path')->nullable();
                $table->json('photo_slots_json')->nullable();
                $table->json('timing_json')->nullable();
                $table->boolean('is_global')->default(false);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('event_templates')) {
            Schema::create('event_templates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photobooth_event_id')->constrained()->cascadeOnDelete();
                $table->foreignId('photobooth_template_id')->constrained()->cascadeOnDelete();
                $table->string('mode_type')->default('photo');
                $table->boolean('is_default')->default(false);
                $table->integer('sort_order')->default(0);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('event_capture_modes')) {
            Schema::create('event_capture_modes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photobooth_event_id')->constrained()->cascadeOnDelete();
                $table->string('mode_type');
                $table->boolean('is_enabled')->default(true);
                $table->json('config_json')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('template_steps')) {
            Schema::create('template_steps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('photobooth_template_id')->constrained()->cascadeOnDelete();
                $table->unsignedSmallInteger('step_number');
                $table->unsignedSmallInteger('slot_number')->nullable();
                $table->unsignedSmallInteger('countdown_seconds')->nullable();
                $table->unsignedSmallInteger('preview_seconds')->nullable();
                $table->string('overlay_path')->nullable();
                $table->string('instruction_text')->nullable();
                $table->json('config_json')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('event_settings')) {
            Schema::table('event_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('event_settings', 'template_id')) {
                    $table->foreignId('template_id')->nullable()->after('photobooth_event_id')->constrained('photobooth_templates')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('event_settings')) {
            Schema::table('event_settings', function (Blueprint $table) {
                if (Schema::hasColumn('event_settings', 'template_id')) {
                    $table->dropColumn('template_id');
                }
            });
        }

        Schema::dropIfExists('template_steps');
        Schema::dropIfExists('event_capture_modes');
        Schema::dropIfExists('event_templates');
        Schema::dropIfExists('photobooth_templates');
    }
};
