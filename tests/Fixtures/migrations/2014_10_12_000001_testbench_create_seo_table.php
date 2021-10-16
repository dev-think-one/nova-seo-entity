<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestbenchCreateSeoTable extends Migration
{
    public function up()
    {
        Schema::create(config('nova-seo-entity.table'), function (Blueprint $table) {
            \NovaSeoEntity\Database\MigrationHelper::defaultColumns($table);
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('nova-seo-entity.table'));
    }
}
