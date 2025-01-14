<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('site_infos', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->text('short_description')->nullable(); // Short description of the site
            $table->string('copy_right')->nullable(); // Copyright text
            $table->string('address')->nullable(); // Address
            $table->text('map_link')->nullable(); // Map link (Google Maps, etc.)
            $table->string('email')->nullable(); // Email address
            $table->string('phone')->nullable(); // Phone number
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_infos');
    }
};
