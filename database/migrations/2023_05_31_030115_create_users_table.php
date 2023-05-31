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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_user_active');
            $table->uuid('id_user_bank')->nullable();
            $table->uuid('id_user_business')->nullable();
            $table->uuid('id_user_heir')->nullable();
            $table->uuid('id_user_image')->nullable();
            $table->string('full_name')->nullable();;
            $table->date('date_of_birth')->nullable();;
            $table->enum('gender', ['M', 'F'])->nullable();;
            $table->text('address')->nullable();;
            $table->char('id_card', 16)->nullable();;
            $table->char('tax_registration_number', 15)->nullable();;
            $table->string('email')->unique();
            $table->string('password');
            $table->string('employment_status')->nullable();
            $table->enum('authorization_level', ['1', '2', '3']);
            $table->string('phone_number')->nullable();;
            $table->string('marital_status')->nullable();
            $table->string('updated_by')->default('system');
            $table->string('created_by')->default('system');
            $table->boolean('is_deleted')->default('0');
            $table->integer('version')->default('1');
            $table->timestamps();

            $table->foreign('id_user_active')->references('id')->on('user_actives');
            $table->foreign('id_user_bank')->references('id')->on('user_banks');
            $table->foreign('id_user_business')->references('id')->on('user_business');
            $table->foreign('id_user_heir')->references('id')->on('user_heirs');
            $table->foreign('id_user_image')->references('id')->on('user_images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
