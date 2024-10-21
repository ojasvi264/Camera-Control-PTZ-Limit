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
        Schema::create('camera_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->string('camera_id');
            $table->integer('zoom_level');
            $table->float('pan_limit_max');
            $table->float('pan_limit_min');
            $table->float('tilt_limit_min');
            $table->float('tilt_limit_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camera_settings');
    }
};
