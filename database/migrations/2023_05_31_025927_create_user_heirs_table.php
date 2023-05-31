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
        Schema::create('user_heirs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable();;
            $table->string('relationship')->nullable();;
            $table->string('phone_number')->nullable();;
            $table->text('address')->nullable();;
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
        Schema::dropIfExists('user_heirs');
    }
};
