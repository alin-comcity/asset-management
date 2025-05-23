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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name');
            $table->string('asset_cat_id');
            $table->string('asset_desc')->nullable();
            $table->string('asset_tag')->unique();
            $table->string('asset_serial')->unique();
            $table->string('asset_type')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('asset_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
