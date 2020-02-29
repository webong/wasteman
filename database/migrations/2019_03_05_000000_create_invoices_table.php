<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->integer('customer_id')->nullable()->index();
            $table->decimal('amount');
            $table->decimal('pending_amount')->nullable();
            $table->string('currency');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->date('paid_at')->nullable();
            $table->string('status');
            $table->boolean('paid');
            $table->string('paystack_invoice_id');
            $table->string('paystack_invoice_code');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
