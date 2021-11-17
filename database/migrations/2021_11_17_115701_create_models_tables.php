<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop auto-created table
        Schema::dropIfExists('personal_access_tokens');

        Schema::create('upload_list', function (Blueprint $table) {
            $table->id();
            $table->timestamp('upload_time')->useCurrent();
            $table->string('name', 45)->nullable();
        });

        Schema::create('domain', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name');
        });

        Schema::create('upload_domain', function (Blueprint $table) {
            $table->id();
            $table->integer('upload_list_id');
            $table->integer('domain_id');
        });

        Schema::create('domain_contact', function (Blueprint $table) {
            $table->id();
            $table->integer('domain_id');
            $table->string('first_name', 45)->nullable();
            $table->string('last_name', 45)->nullable();
            $table->string('email', 100)->nullable();
            $table->tinyInteger('confidence')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain_contact');
        Schema::dropIfExists('upload_domain');
        Schema::dropIfExists('domain');
        Schema::dropIfExists('upload_list');
    }
}
