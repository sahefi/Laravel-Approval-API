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
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('driver')->constrained();
            $table->foreignUuid('id_vehicle')->references('id')->on('vehicles');
            $table->string('applicant');
            $table->foreignUuid('id_approver')->references('id')->on('users');
            $table->boolean('is_approved')->default(0);
            $table->boolean('need_approval')->default(1);
            $table->timestampTz('start_book');
            $table->timestampTz('end_book');
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
        Schema::dropIfExists('bookings');
    }
};
