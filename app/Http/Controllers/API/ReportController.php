<?php

namespace App\Http\Controllers\API;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reports = [];

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

            $reports[] = [
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

        $reports = array_values(array_filter($reports, function($report) use ($request) {
            switch ($request->status) {
                case 'present':
                    return $report['is_present'] == true && $report['is_late'] == false && $report['is_leave'] == false && $report['is_rest'] == false;
                    break;
                case 'absence':
                    return $report['is_absent'] == true && $report['is_leave'] == false && $report['is_rest'] == false;
                    break;
                case 'late':
                    return $report['is_late'] == true;
                    break;
                case 'leave':
                    return $report['is_leave'] == true && $report['is_rest'] == false;
                    break;
                case 'rest':
                    return $report['is_rest'] == true && $report['is_leave'] == false;
                    break;
                
                default:
                    return false;
                    break;
            }
        }));

        $responseData['success'] = true;
        $responseData['data']['reports'] = $reports;

        return response()->json($responseData, 200);
    }

    public function download(Request $request)
    {
        $responseData = [];

        $date = $request->date ?? Carbon::today();

        $fileName = 'Rapport ' . Carbon::parse($request->date)->locale('fr')->format('d-m-Y_H-i-s') . '.xlsx';
        $filePath = 'reports/' . $fileName;

        Excel::store(new ReportExport($date), 'public/' . $filePath);

        $downloadUrl = url('storage/' . $filePath);

        $responseData['success'] = true;
        $responseData['data']['report']['download_url'] = $downloadUrl;

        return response()->json($responseData, 200);
    }
}
