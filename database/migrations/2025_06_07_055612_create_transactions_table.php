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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('parent_id')->nullable()->index();
            $table->foreign('parent_id')->references('id')->on('transactions')->onDelete('cascade');
            
            $table->uuid('transaction_method_id')->index();
            $table->foreign('transaction_method_id')->references('id')->on('transaction_methods')->onDelete('cascade');
            
            $table->uuid('transaction_type_id')->index();
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('cascade');
            
            $table->uuid('transaction_status_id')->index();
            $table->foreign('transaction_status_id')->references('id')->on('transaction_statuses')->onDelete(action: 'cascade');
            
            $table->string('name')->nullable();
            $table->uuid('dues_payment_id')->nullable()->index();
            $table->foreign('dues_payment_id')->references('id')->on('dues_payments')->onDelete('set null');
            $table->uuid('email_id')->nullable()->index();
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('set null');
            // $table->string('account_name')->nullable();
            $table->decimal('base_amount', 10, 2)->default(0);
            $table->integer('point')->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->decimal('system_balance', 10, 2)->nullable()->default(0);
            $table->dateTime('date')->nullable();
            $table->json('info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
