<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LeaveAbsenceRequest;
use App\Models\leaveAbsenceRequestComment;
use App\Notifications\NewLeaveAbsenceRequestCommentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveAbsenceRequestCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, LeaveAbsenceRequest $leaveAbsenceRequest)
    {
        $leaveAbsenceRequestComments = $leaveAbsenceRequest->comments()->with('employee')->with('employee.user')->paginate(15);

        $responseData['success'] = true;
        $responseData['data']['leave_absence_request_comments'] = $leaveAbsenceRequestComments;

        return response()->json($responseData, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, LeaveAbsenceRequest $leaveAbsenceRequest)
    {
        $responseData = [];

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
            'attachments' => [
                'array',
                'nullable'
            ],
            'attachments.*' => [
                'file',
            ],
        ]);
        
        if (!$leaveAbsenceRequest) {
            $responseData['success'] = false;
            $responseData['message'] = 'Demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $employee = Auth::user()->employee;
        
        if (!$employee) {
            $responseData['success'] = false;
            $responseData['message'] = 'Employé non trouvé';

            return response()->json($responseData, 404);
        }

        if (!$validator->fails()) {
            $leaveAbsenceRequestComment = new leaveAbsenceRequestComment;

            $leaveAbsenceRequestComment->leave_absence_request_id = $leaveAbsenceRequest->id;
            $leaveAbsenceRequestComment->employee_id = optional($employee)->id;
            $leaveAbsenceRequestComment->body = $request->body;

            $leaveAbsenceRequestComment->save();

            if ($request->attachments) {
                $leaveAbsenceRequestComment->addMultipleMediaFromRequest(['attachments'])->each(function ($fileAdder) {
                    $fileAdder
                        ->toMediaCollection('leave-absence-request-comments');
                });
            }

            $responseData['success'] = true;
            $responseData['data']['leave_absence_request_comment'] = $leaveAbsenceRequestComment;
            $responseData['message'] = 'Commentaire de demande de congé créée avec succès';

            $user = $leaveAbsenceRequest->employee->user;

            $user->notify(new NewLeaveAbsenceRequestCommentNotification($user, $leaveAbsenceRequestComment));

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
    public function show(LeaveAbsenceRequest $leaveAbsenceRequest, $id)
    {
        $responseData = [];

        $leaveAbsenceRequestComment = $leaveAbsenceRequest->comments()->where('id', $id)->first();
        
        if (!$leaveAbsenceRequestComment) {
            $responseData['success'] = false;
            $responseData['message'] = 'Commentaire de demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['leave_absence_request_comment'] = $leaveAbsenceRequestComment;

        return response()->json($responseData, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveAbsenceRequest $leaveAbsenceRequest, $id)
    {
        $responseData = [];

        $leaveAbsenceRequestComment = $leaveAbsenceRequest->comments()->where('id', $id)->first();
        
        if (!$leaveAbsenceRequestComment) {
            $responseData['success'] = false;
            $responseData['message'] = 'Commentaire de demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);

        if (!$validator->fails()) {
            $leaveAbsenceRequestComment->employee_id = Auth::id();
            $leaveAbsenceRequestComment->body = $request->body;

            $leaveAbsenceRequestComment->save();

            $responseData['success'] = true;
            $responseData['data']['leave_absence_request_comment'] = $leaveAbsenceRequestComment;
            $responseData['message'] = 'Commentaire de demande de congé mise à jour avec succès';

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
    public function destroy(LeaveAbsenceRequest $leaveAbsenceRequest, $id)
    {
        $responseData = [];

        $leaveAbsenceRequestComment = $leaveAbsenceRequest->comments()->where('id', $id)->first();
        
        if (!$leaveAbsenceRequestComment) {
            $responseData['success'] = false;
            $responseData['message'] = 'Commentaire de demande de congé non trouvé';

            return response()->json($responseData, 404);
        }

        $leaveAbsenceRequestComment->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Demande de congé supprimée avec succès';

        return response()->json($responseData, 200);
    }
}
