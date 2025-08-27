<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('packing_product', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->decimal('per_rate', 10, 2); // Rate per unit
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('packing_product');
    }
};
