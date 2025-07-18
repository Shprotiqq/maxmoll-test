<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('customer', 255);
            $table->timestamp('created_at')
                ->useCurrent();
            $table->timestamp('completed_at')
                ->nullable();
            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();
            $table->string('status', 255)
                ->default('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
