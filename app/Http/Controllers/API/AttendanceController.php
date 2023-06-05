<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attendances = [];

        $employees = Employee::with('user')->with('team')->where(function ($query) use ($request) {
            if ($request->has('department_id')) {
                $query->whereHas('department', function ($query) use ($request) {
                    return $query->where('department.id', $request->department_id);
                });
            }
    
            if ($request->has('department_id')) {
                $query->whereHas('team', function ($query) use ($request) {
                    return $query->where('team.id', $request->team_id);
                });
            }
    
            return $query;
        })->get();

        if ($request->has('date')) {
            $date = $request->date;
        } else {
            $date = Carbon::today();
        }

        foreach ($employees as $employee) {
            $isPresent = false;
            $isAbsent = false;
            $isLate = false;
            $isLeave = false;
            $isRest = false;

            $timesheet = $employee->timesheets()->where('week', ltrim(date("W", strtotime($date)), '0'))->where('day', date('N', strtotime($date)))->first();

            $attendance = Attendance::where('employee_id', $employee->id)->whereDate('created_at', $date)->first();

            if ($timesheet) {
                $time = $timesheet->time;

                if ($time) {
                    $isLeave = $time->is_leave ?? false;
                    $isRest = $time->is_rest ?? false;
                }

                if ($attendance) {
                    $isPresent = !$isLeave && !$isRest ? true : false;

                    if ($isPresent && $time->check_in < $attendance->check_in) {
                        $isLate = true;
                    }
                } else {
                    $isAbsent = true;
                }
            }

            $attendances[] = [
                'employee' => $employee,
                'time' => $time ?? null,
                'check_in' => optional($attendance)->check_in,
                'check_out' => optional($attendance)->check_out,
                'break_in' => optional($attendance)->break_in,
                'break_out' => optional($attendance)->break_out,
                'is_present' => $isPresent,
                'is_absent' => $isAbsent,
                'is_late' => $isLate,
                'is_leave' => $isLeave,
                'is_rest' => $isRest,
            ];
        }

        $responseData['success'] = true;
        $responseData['data']['attendances'] = $attendances;

        return response()->json($responseData, 200);
    }

    public function checkIn(Request $request)
    {
        $responseData = [];

        $user = Auth::user();

        if ($user->employee) {
            $attendance = $user->employee->attendances()->whereDate('created_at', Carbon::today())->first();

            $user->employee->attendances()->updateOrCreate(
                ['id' => $attendance ? $attendance->id : null],
                ['check_in' => Carbon::now()->toTimeString()]
            );
        }

        $responseData['success'] = true;

        return response()->json($responseData, 200);
    }

    public function checkOut(Request $request)
    {
        $responseData = [];

        $user = Auth::user();

        if ($user->employee) {
            $attendance = $user->employee->attendances()->whereDate('created_at', Carbon::today())->first();

            $user->employee->attendances()->updateOrCreate(
                ['id' => $attendance ? $attendance->id : null],
                ['check_out' => Carbon::now()->toTimeString()]
            );
        }

        $responseData['success'] = true;

        return response()->json($responseData, 200);
    }

    public function breakIn(Request $request)
    {
        $responseData = [];

        $user = Auth::user();

        if ($user->employee) {
            $attendance = $user->employee->attendances()->whereDate('created_at', Carbon::today())->first();

            $user->employee->attendances()->updateOrCreate(
                ['id' => $attendance ? $attendance->id : null],
                ['break_in' => Carbon::now()->toTimeString()]
            );
        }

        $responseData['success'] = true;

        return response()->json($responseData, 200);
    }

    public function breakOut(Request $request)
    {
        $responseData = [];

        $user = Auth::user();

        if ($user->employee) {
            $attendance = $user->employee->attendances()->whereDate('created_at', Carbon::today())->first();

            $user->employee->attendances()->updateOrCreate(
                ['id' => $attendance ? $attendance->id : null],
                ['break_out' => Carbon::now()->toTimeString()]
            );
        }

        $responseData['success'] = true;

        return response()->json($responseData, 200);
    }
}
