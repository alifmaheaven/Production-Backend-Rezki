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
        Schema::create('campaign_report_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_campaign_report')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->text('description')->nullable();
            $table->string('evidence')->nullable();
            $table->string('type')->nullable();
            $table->string('created_by')->default('system');
            $table->string('updated_by')->default('system');
            $table->boolean('is_deleted')->default(false);
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('id_campaign_report')->references('id')->on('campaign_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_report_details');
    }
};
