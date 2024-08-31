<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wovosoft\LaravelCashier\Enums\PaymentStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            /**
             * Payment initiator's id
             */
            $table->foreignId('created_by_id')->nullable();

            /**
             * The id of the slip
             */
            $table->foreignId('slip_id')->nullable();

            /**
             * The main amount of the payment, which is sent to wafid.com
             */
            $table->decimal('payment_amount')->default(0)->nullable();

            /**
             * The amount of admin_fee, which is considered as company's income
             */
            $table->decimal('admin_fee')->default(0)->nullable();

            /**
             * If the customer belongs to an agent, this is the amount of agent_fee,
             * otherwise it is always zero
             */
            $table->decimal('agent_fee')->default(0)->nullable();

            /**
             * The status of the payment, this a kind of redundant field,
             * but it is kept for quick access
             */
            $table->string('status')->default(PaymentStatus::Pending->value);

            /**
             * Time when the payment is completed, from wafid.com
             * and after that a process is triggered, which sends the amounts of admin_fee
             * and agent_fee to the respective accounts of admins and agents
             */
            $table->dateTime('completed_at')->nullable();
            //$table->foreignId('completed_by_id')->nullable();

            /**
             * Time when the payment is failed, from wafid.com,
             * when it is failed, the payment amount is returned to the user's account,
             * maybe by another process
             */
            $table->dateTime('failed_at')->nullable();

            /**
             * Time when a failed payment amount (payment_amount+admin_fee+agent_fee)
             * is returned to the user's account
             */
            $table->dateTime('returned_at')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
