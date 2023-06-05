<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('designation_id')->constrained('designations');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('team_id')->constrained('teams')->nullable();
            $table->string('id_number');
            $table->string('registration_number');
            $table->string('payroll_number');
            $table->boolean('is_department_head')->default(false);
            $table->boolean('is_team_head')->default(false);
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
        Schema::dropIfExists('employees');
    }
}
