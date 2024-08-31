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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();

            /**
             * Payment id
             */
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();

            /**
             * Income for the person
             * For Agent it should be required, for admin it can be null, which will be categorized by type field
             */
            $table->foreignId('owner_id')->nullable();

            /**
             * For agent or admin to quickly categorize
             */
            $table->string('type');
            $table->decimal('amount')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
