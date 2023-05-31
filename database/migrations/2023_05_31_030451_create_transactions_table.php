<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_campaign')->nullable();
            $table->uuid('id_user')->nullable();
            $table->uuid('id_receipt')->nullable()->nullable();
            $table->bigInteger('investor_amount')->nullable();
            $table->integer('sukuk')->nullable();
            $table->bigInteger('service_fee')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('profit')->nullable();
            $table->string('created_by')->default('system');
            $table->string('updated_by')->default('system');
            $table->boolean('is_deleted')->default(false);
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('id_campaign')->references('id')->on('campaigns');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_receipt')->references('id')->on('receipts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
