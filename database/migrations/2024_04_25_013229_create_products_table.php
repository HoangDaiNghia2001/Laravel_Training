<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('title');
            $table->double('price');
            $table->integer('discounted_percent');
            $table->double('discounted_price');
            $table->integer('quantity');
            $table->string('main_image');
            $table->string('color');
            $table->integer('brand_id')->unsigned()->index();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('brand_name');
            $table->integer('parent_category_id')->unsigned()->index();
            $table->foreign('parent_category_id')->references('id')->on('parent_categories')->onDelete('cascade');
            $table->string('parent_category_name');
            $table->integer('child_category_id')->unsigned()->index();
            $table->foreign('child_category_id')->references('id')->on('child_categories')->onDelete('cascade');
            $table->string('child_category_name');
            $table->integer('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->unsigned()->index();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
        });

        // soft delete
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
