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
        Schema::create('dues_payment_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dues_payment_id')->index();
            $table->foreign('dues_payment_id')->references('id')->on('dues_payments')->onDelete('cascade');
            $table->uuid('contribution_id')->index();
            $table->foreign('contribution_id')->references('id')->on('contributions')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dues_payment_details');
    }
};
