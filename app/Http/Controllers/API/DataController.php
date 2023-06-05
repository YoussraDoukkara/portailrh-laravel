<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $responseData = [];

        $user = Auth::user();

        $employee = $user->employee;

        $responseData['success'] = true;

        $responseData['data']['check_in'] = null;
        $responseData['data']['check_out'] = null;
        $responseData['data']['break_in'] = null;
        $responseData['data']['break_out'] = null;

        if ($employee) {
            $attendance = $employee->attendances()->whereDate('created_at', Carbon::today())->first();

            if ($attendance) {
                $responseData['data']['check_in'] = $attendance->check_in;
                $responseData['data']['check_out'] = $attendance->check_out;
                $responseData['data']['break_in'] = $attendance->break_in;
                $responseData['data']['break_out'] = $attendance->break_out;
            }
        }

        return response()->json($responseData, 200);
    }
}
