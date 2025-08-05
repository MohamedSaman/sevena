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
        Schema::create('production_salaries', function (Blueprint $table) {
            $table->id('production_id');
            $table->foreignId('employee_id')->constrained('employees', 'emp_id')->onDelete('cascade');
            $table->string('work_type');
            $table->string('category');
            $table->integer('quantity');
            $table->decimal('per_rate', 10, 2);
            $table->decimal('additional_salary', 10, 2)->nullable();
            $table->decimal('bonus', 10, 2)->nullable();
            $table->decimal('allowance', 10, 2)->nullable();
            $table->decimal('total_salary', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_salaries');
    }
};
