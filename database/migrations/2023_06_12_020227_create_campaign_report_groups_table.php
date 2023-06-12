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
        Schema::create('campaign_report_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_campaign_report')->nullable();
            $table->uuid('id_campaign_report_detail')->nullable();
            $table->string('created_by')->default('system');
            $table->string('updated_by')->default('system');
            $table->boolean('is_deleted')->default(false);
            $table->integer('version')->default(1);
            $table->timestamps();

            $table->foreign('id_campaign_report')->references('id')->on('campaign_reports');
            $table->foreign('id_campaign_report_detail')->references('id')->on('campaign_report_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_report_groups');
    }
};
