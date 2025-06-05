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
        // name, name_lang_key, slug, icon, menu_group_id, sort
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('menu_group_id')->index();
            $table->foreign('menu_group_id')->references('id')->on('menu_groups')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('name_lang_key', 100);
            $table->string('route_name', 255);
            $table->string('slug', 255);
            $table->string('icon', 25);
            $table->integer('sort')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
