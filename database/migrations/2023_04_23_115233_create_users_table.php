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
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('full_name');
            $table->enum('gender', ['M', 'F']);
            $table->string('address');
            $table->string('id_card');
            $table->string('tax_registration_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('employment_status');
            $table->foreignUuid('id_user_active')->references('id')->on('user_actives');
            $table->foreignUuid('id_user_bank')->references('id')->on('user_banks');
            $table->enum('authorization_level', ['1', '2', '3']);
            $table->string('business_certificate')->nullable();
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
        Schema::dropIfExists('users');
    }
};
