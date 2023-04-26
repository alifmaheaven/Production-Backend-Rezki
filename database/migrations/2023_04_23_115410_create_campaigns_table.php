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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description');
            $table->string('type');
            $table->string('target_funding_amount');
            $table->string('current_funding_amount');
            $table->string('start_date');
            $table->string('closing_date');
            $table->string('return_investment_period');
            $table->string('status');
            $table->string('document_name');
            $table->string('document_url');
            $table->string('category');
            $table->foreignUuid('id_campaign_period')->references('id')->on('campaign_periods')->onDelete('cascade');
            $table->foreignUuid('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('is_approved');
            $table->string('max_sukuk');
            $table->foreignUuid('id_campaign_banner')->references('id')->on('campaign_banners')->onDelete('cascade');
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
        Schema::dropIfExists('campaigns');
    }
};
