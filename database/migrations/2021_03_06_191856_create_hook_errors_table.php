<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHookErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hook_errors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hook_id');
            $table->integer("status_code")->nullable();
            $table->text("response_body")->nullable();
            $table->timestamp("created_at");
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hook_errors');
    }
}
