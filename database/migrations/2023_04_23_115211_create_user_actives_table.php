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
        Schema::create('user_actives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('phone_number')->default('0');
            $table->boolean('email')->default('0');
            $table->boolean('id_card')->default('0');
            $table->boolean('tax_registration_number')->default('0');
            $table->boolean('user_bank')->default('0');
            $table->boolean('user_business')->default('0');
            $table->string('updated_by')->default('system');
            $table->string('created_by')->default('system');
            $table->boolean('is_deleted')->default('0');
            $table->integer('version')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_actives');
    }
};
