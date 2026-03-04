<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetectDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detect_device', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_name')->nullable();      // "John's iPhone" or "Chrome (Desktop)"
            $table->string('device_type', 50)->nullable(); // "mobile" / "desktop" / "tablet"
            $table->string('platform')->nullable();        // "iOS" / "Android" / "Windows"
            $table->string('browser')->nullable();         // "Chrome" / "Firefox"
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('fingerprint')->nullable()->index(); // optional hashed fingerprint
            $table->string('refresh_token_hash')->nullable();   // hashed refresh token (if used)
            $table->boolean('revoked')->default(false);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detect_device');
    }
}
