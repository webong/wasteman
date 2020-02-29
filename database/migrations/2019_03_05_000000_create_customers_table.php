<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->string('first_name', 25);
            $table->string('last_name', 25);
            $table->string('email', 50);
            $table->string('phone', 50)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('paystack_customer_code', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
