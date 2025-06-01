<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_balance', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('total_balance', 15, 2)->default(0.00);
            $table->unsignedInteger('total_point')->default(0);
            $table->decimal('final_balance', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_balance');
    }
};
