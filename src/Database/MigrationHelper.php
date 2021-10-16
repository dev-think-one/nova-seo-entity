<?php

namespace NovaSeoEntity\Database;

use Illuminate\Database\Schema\Blueprint;

class MigrationHelper
{
    public static function defaultColumns(Blueprint $table)
    {
        $table->id();
        $table->morphs('seoptimisable');
        $table->string('title')->nullable();
        $table->string('description')->nullable();
        $table->string('canonical')->nullable();
        $table->string('image')->nullable();

        // Future implementation
        // $table->json('meta')->nullable();
        // $table->json('open_graph')->nullable();
        // $table->json('twitter_card')->nullable();
        // $table->json('json_ld')->nullable();
        // $table->json('json_ld_multi')->nullable();

        $table->timestamps();
    }
}
