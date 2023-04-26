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
            $table->uuid('id_user_active')->primary();
            $table->string('phone_number');
            $table->string('email');
            $table->string('id_card');
            $table->string('tax_registration_number');
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
        Schema::dropIfExists('user__actives');
    }
};
