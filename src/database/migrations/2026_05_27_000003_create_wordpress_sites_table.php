<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wordpress_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('url');
            $table->string('admin_url')->nullable();
            $table->string('status')->default('pending');
            $table->string('wordpress_version')->nullable();
            $table->unsignedInteger('plugins_count')->default(0);
            $table->unsignedInteger('outdated_plugins_count')->default(0);
            $table->unsignedInteger('themes_count')->default(0);
            $table->unsignedInteger('outdated_themes_count')->default(0);
            $table->json('plugins')->nullable();
            $table->json('themes')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wordpress_sites');
    }
};
