<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveAbsenceRequestCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_absence_request_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_absence_request_id')->constrained('leave_absence_requests');
            $table->foreignId('employee_id')->constrained('employees');
            $table->longText('body');
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
        Schema::dropIfExists('leave_absence_request_comments');
    }
}
