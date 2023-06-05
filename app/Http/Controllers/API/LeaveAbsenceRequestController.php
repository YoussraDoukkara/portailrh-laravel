<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LeaveAbsenceRequest;
use App\Notifications\LeaveAbsenceRequestApprovedNotification;
use App\Notifications\LeaveAbsenceRequestRejectedNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveAbsenceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;

        if (Auth::user()->role == 'admin') {
            if ($request->has('page')) {
                $leaveAbsenceRequests = LeaveAbsenceRequest::with('employee')->with('employee.user')->paginate(15);
            } else {
                $leaveAbsenceRequests['data'] = LeaveAbsenceRequest::with('employee')->with('employee.user')->get();
            }
        } else if (optional($employee)->is_department_head) {
            if ($request->has('page')) {
                $leaveAbsenceRequests = LeaveAbsenceRequest::join('employees', 'leave_absence_requests.employee_id', '=', 'employees.id')->where('employees.department_id', optional($employee)->department_id)->with('employee')->with('employee.user')->select('leave_absence_requests.*')->paginate(15);
            } else {
                $leaveAbsenceRequests['data'] = LeaveAbsenceRequest::join('employees', 'leave_absence_requests.employee_id', '=', 'employees.id')->where('employees.department_id', optional($employee)->department_id)->with('employee')->with('employee.user')->select('leave_absence_requests.*')->get();
            }
        } else if (optional($employee)->is_team_head) {
            if ($request->has('page')) {
                $leaveAbsenceRequests = LeaveAbsenceRequest::join('employees', 'leave_absence_requests.employee_id', '=', 'employees.id')->where(function ($query) use ($employee) {
                    $query->orWhere('employees.id', optional($employee)->id)->orWhere('is_department_head', '0', '!=');
                })->where('employees.team_id', optional($employee)->team_id)->with('employee')->with('employee.user')->select('leave_absence_requests.*')->paginate(15);
            } else {
                $leaveAbsenceRequests['data'] = LeaveAbsenceRequest::join('employees', 'leave_absence_requests.employee_id', '=', 'employees.id')->where('employees.team_id', optional($employee)->team_id)->where(function ($query) use ($employee) {
                    $query->orWhere('employees.id', optional($employee)->id)->orWhere('is_department_head', '0', '!=');
                })->with('employee')->with('employee.user')->select('leave_absence_requests.*')->get();
            }
        } else {
            if ($request->has('page')) {
                $leaveAbsenceRequests = Auth::user()->employee->leaveAbsenceRequests()->with('employee')->with('employee.user')->paginate(15);
            } else {
                $leaveAbsenceRequests['data'] = Auth::user()->employee->leaveAbsenceRequests->with('employee')->with('employee.user')->get();
            }
        }

        $responseData['success'] = true;
        $responseData['data']['leave_absence_requests'] = $leaveAbsenceRequests;

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
            'body' => 'required|string',
            'reason' => 'required|string|in:illness,mission,other',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'attachments' => [
                'array',
                'nullable'
            ],
            'attachments.*' => [
                'file',
            ],
        ]);

        $employee = Auth::user()->employee;
        
        if (!$employee) {
            $responseData['success'] = false;
            $responseData['message'] = 'Employé non trouvé';

            return response()->json($responseData, 404);
        }

        if (!$validator->fails()) {
            $leaveAbsenceRequest = new LeaveAbsenceRequest;

            $leaveAbsenceRequest->employee_id = optional($employee)->id;
            $leaveAbsenceRequest->body = $request->body;
            $leaveAbsenceRequest->reason = $request->reason;
            $leaveAbsenceRequest->starts_at = $request->starts_at;
            $leaveAbsenceRequest->ends_at = $request->ends_at;

            $leaveAbsenceRequest->save();

            if ($request->attachments) {
                $leaveAbsenceRequest->addMultipleMediaFromRequest(['attachments'])->each(function ($fileAdder) {
                    $fileAdder
                        ->toMediaCollection('leave-absence-requests');
                });
            }

            $responseData['success'] = true;
            $responseData['data']['leave_absence_request'] = $leaveAbsenceRequest;
            $responseData['message'] = 'Demande de congé créée avec succès';

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
        $responseData = [];

        $leaveAbsenceRequest = LeaveAbsenceRequest::where('id', $id)->with('employee')->with('employee.user')->first();
        
        if (!$leaveAbsenceRequest) {
            $responseData['success'] = false;
            $responseData['message'] = 'Demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['leave_absence_request'] = $leaveAbsenceRequest;

        return response()->json($responseData, 200);
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
        $responseData = [];

        $leaveAbsenceRequest = LeaveAbsenceRequest::find($id);
        
        if (!$leaveAbsenceRequest) {
            $responseData['success'] = false;
            $responseData['message'] = 'Demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'reason' => 'required|string|in:illness,mission,other',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
        ]);

        if (!$validator->fails()) {
            $leaveAbsenceRequest->employee_id = Auth::id();
            $leaveAbsenceRequest->body = $request->body;
            $leaveAbsenceRequest->reason = $request->reason;
            $leaveAbsenceRequest->starts_at = $request->starts_at;
            $leaveAbsenceRequest->ends_at = $request->ends_at;

            $leaveAbsenceRequest->save();

            $responseData['success'] = true;
            $responseData['data']['leave_absence_request'] = $leaveAbsenceRequest;
            $responseData['message'] = 'Demande de congé mise à jour avec succès';

            return response()->json($responseData, 200);
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $responseData = [];

        $leaveAbsenceRequest = LeaveAbsenceRequest::find($id);
        
        if (!$leaveAbsenceRequest) {
            $responseData['success'] = false;
            $responseData['message'] = 'Demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $leaveAbsenceRequest->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Demande de congé supprimée avec succès';

        return response()->json($responseData, 200);
    }

    public function approve(Request $request, $id)
    {
        $responseData = [];

        $leaveAbsenceRequest = LeaveAbsenceRequest::where('id', $id)->whereNull('approved_at')->whereNull('rejected_at')->first();
        
        if (!$leaveAbsenceRequest) {
            $responseData['success'] = false;
            $responseData['message'] = 'Demande de congé non trouvé';

            return response()->json($responseData, 404);
        }
        
        $leaveAbsenceRequest->approved_at = Carbon::now();

        $leaveAbsenceRequest->save();

        $responseData['success'] = true;
        $responseData['data']['leave_absence_request'] = $leaveAbsenceRequest;
        $responseData['message'] = 'Demande de congé approuvée avec succès';

        $user = $leaveAbsenceRequest->employee->user;

        $user->notify(new LeaveAbsenceRequestApprovedNotification($user, $leaveAbsenceRequest));

        return response()->json($responseData, 200);
    }

    public function reject(Request $request, $id)
    {
        $responseData = [];

        $leaveAbsenceRequest = LeaveAbsenceRequest::where('id', $id)->whereNull('approved_at')->whereNull('rejected_at')->first();
        
        if (!$leaveAbsenceRequest) {
            $responseData['success'] = false;
            $responseData['message'] = 'Demande de congé non trouvé';

            return response()->json($responseData, 404);
        }
        
        $leaveAbsenceRequest->rejected_at = Carbon::now();

        $leaveAbsenceRequest->save();

        $responseData['success'] = true;
        $responseData['data']['leave_absence_request'] = $leaveAbsenceRequest;
        $responseData['message'] = 'Demande de congé rejetée avec succès';

        $user = $leaveAbsenceRequest->employee->user;

        $user->notify(new LeaveAbsenceRequestRejectedNotification($user, $leaveAbsenceRequest));

        return response()->json($responseData, 200);
    }
}
