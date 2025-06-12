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
        Schema::create('dues_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('resident_id')->nullable()->index();
            $table->foreign('resident_id')->references('id')->on('residents')->onDelete('cascade');
            $table->uuid('dues_month_id')->nullable()->index();
            $table->foreign('dues_month_id')->references('id')->on('dues_months')->onDelete('cascade');
            $table->uuid('parent_id')->nullable()->index();
            $table->foreign('parent_id')->references('id')->on('dues_payments')->onDelete('cascade');
            $table->decimal('base_amount', 10, 2)->default(0);
            $table->integer('unique_code')->index();
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->integer('is_merge')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dues_payments');
    }
};
