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
             Schema::table('attendances', function (Blueprint $table) {
                 $table->time('check_in')->nullable()->after('date');
                 $table->time('break_start')->nullable()->after('check_in');
                 $table->time('break_end')->nullable()->after('break_start');
                 $table->time('check_out')->nullable()->after('break_end');
                 $table->decimal('time_worked', 5, 2)->nullable()->after('check_out');
                 $table->string('fingerprint_id')->nullable()->after('employee_id');
             });
         }

         /**
          * Reverse the migrations.
          */
         public function down(): void
         {
             Schema::table('attendances', function (Blueprint $table) {
                 $table->dropColumn(['check_in', 'break_start', 'break_end', 'check_out', 'time_worked', 'fingerprint_id']);
             });
         }
     };