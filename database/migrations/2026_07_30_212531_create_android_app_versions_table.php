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
        Schema::create('android_app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version')->nullable();
            $table->bigInteger('version_code')->nullable();
            $table->string('bundle_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('android_app_versions');
    }
};
