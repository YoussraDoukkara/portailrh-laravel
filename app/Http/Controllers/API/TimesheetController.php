<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Timesheet;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $responseData = [];

        if ($request->has('week')) {
            $week = $request->week;
        } else {
            $week = date('W');
        }

        $timesheets = [];

        $employees = Employee::with('user')->get();

        foreach ($employees as $employee) {
            $times = [];

            for ($day=1; $day <= 7; $day++) {
                $timesheet = $employee->timesheets()->where('week', $week)->where('day', $day)->first();

                $times[] = optional($timesheet)->time;
            }

            $timesheets[] = [
                'employee' => $employee,
                'times' => $times,
            ];
        }

        $responseData['success'] = true;
        $responseData['data']['timesheets'] = $timesheets;

        return response()->json($responseData, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $responseData = [];

        $validator = Validator::make($request->all(), [
            'timesheets' => 'required|array',
            'timesheets.*.time_id' => 'nullable|numeric|exists:times,id',
            'timesheets.*.employee_id' => 'required|numeric|exists:employees,id',
            'timesheets.*.week' => 'required|numeric',
            'timesheets.*.day' => 'required|numeric',
        ]);

        if (!$validator->fails()) {
            foreach ($request->timesheets as $input) {
                $employee = Employee::find($input['employee_id']);

                $timesheet = $employee->timesheets()->where('week', $input['week'])->where('day', $input['day'])->first() ?? new Timesheet;

                $timesheet->time_id = $input['time_id'];
                $timesheet->employee_id = $input['employee_id'];
                $timesheet->week = $input['week'];
                $timesheet->day = $input['day'];

                $timesheet->save();
            }

            $responseData['success'] = true;
            $responseData['message'] = 'Feuilles de temps enregistrées avec succès';

            return response()->json($responseData, 200);
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
