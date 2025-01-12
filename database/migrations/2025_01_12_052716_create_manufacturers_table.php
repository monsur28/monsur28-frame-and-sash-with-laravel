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
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            //basic
            $table->string('user_id')->unique();
            $table->string('user_name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('country_region')->nullable();
            $table->string('language')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            //company
            $table->string('company_name')->nullable();
            $table->string('company_image')->nullable();
            $table->string('nid')->nullable();
            $table->string('company_email')->nullable();
            //confirm details
            $table->boolean('approved')->default(false);
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
    }
};
