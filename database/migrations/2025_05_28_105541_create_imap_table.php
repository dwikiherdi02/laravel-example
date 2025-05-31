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
        Schema::create('imap', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('host')->nullable();
            $table->integer('port')->default(0);
            $table->string('protocol')->nullable();
            $table->string('encryption')->nullable();
            $table->boolean('validate_cert')->default(true);
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('authentication')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imap');
    }
};
