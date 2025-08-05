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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('emp_id');
            $table->string('empCode')->unique();
            $table->string('fingerprint_id')->nullable();
            $table->string('photo')->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('gender');
            $table->date('dob');
            $table->string('nic');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('department');
            $table->string('designation');
            $table->enum('salary_type', ['daily', 'monthly']);
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->date('joining_date');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
