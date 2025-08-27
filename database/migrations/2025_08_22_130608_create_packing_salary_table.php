<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('packing_salary', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');  // FK to employees table
            $table->date('date_packed');
            $table->unsignedBigInteger('product_id');   // FK to packing_product table
            $table->integer('quentity');                // Typo in your request, should it be 'quantity'?
            $table->string('session')->nullable();      // Morning / Evening etc.
            $table->decimal('salary', 10, 2);
            $table->decimal('adjusment', 10, 2)->default(0);
            $table->decimal('total_salary', 10, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('employee_id')->references('emp_id')->on('employees')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('packing_product')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('packing_salary');
    }
};
