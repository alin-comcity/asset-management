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
            $table->string('asset_photo')->nullable();
            $table->string('asset_tag')->nullable();
            $table->string('asset_name')->nullable();
            $table->string('serial')->nullable();
            $table->string('model_name')->nullable();
            $table->string('status_label')->nullable();
            $table->string('condition_of_asset')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('assigned_to')->nullable();
            $table->string('depertment')->nullable();
            $table->string('location')->nullable();
            $table->string('company')->nullable();
            $table->string('received_date')->nullable();
            $table->integer('quantity');
            $table->string('notes')->nullable();
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
