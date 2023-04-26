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
            $table->uuid('id_user')->primary();
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->text('address');
            $table->string('id_card');
            $table->string('tax_registration_number');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('employment_status');
            $table->foreignUuid('id_user_active')->references('id_user_active')->on('user_actives');
            $table->foreignUuid('id_user_bank')->references('id_user_bank')->on('user_banks');
            $table->enum('authorization_level', ['admin', 'investor', 'penerbit']);
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
