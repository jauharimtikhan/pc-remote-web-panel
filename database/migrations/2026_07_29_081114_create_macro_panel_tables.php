<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Devices (Disesuaikan dengan Cloudflared Hash & Heartbeat Ping)
        Schema::create('devices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id')->unique()->nullable(); // UUID dari Python App
            $table->string('device_name')->default('My PC');

            // Auth & Tokens
            $table->string('web_token')->unique(); // Token pendek/hash yg diinput user di GUI Desktop App
            $table->text('cf_token')->nullable(); // Token asli cloudflared dari web panel
            $table->string('security_pin', 6)->nullable(); // PIN 6 digit untuk koneksi Android

            // Status & Settings dari Desktop App
            $table->string('mode')->default('Lokal (LAN)');
            $table->integer('port')->default(8765);
            $table->string('local_ip')->nullable();
            $table->integer('active_clients')->default(0); // Jumlah HP yg lg konek ke PC

            $table->string('tunnel_url')->nullable(); // Cth: user1-tunnel.pramaxx.biz.id
            $table->string('cf_tunnel_id')->nullable();

            // Heartbeat Monitoring
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
