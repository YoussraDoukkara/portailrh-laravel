<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $responseData['success'] = true;

        $employee = Auth::user()->employee;

        if (optional($employee)->is_department_head) {
            $employees = Employee::where('department_id', optional($employee)->department_id)->with('user')->with('designation')->with('department')->with('team')->paginate(15);
        } else if (optional($employee)->is_team_head) {
            $employees = Employee::where('team_id', optional($employee)->team_id)->with('user')->with('designation')->with('department')->with('team')->paginate(15);
        } else {
            $employees = Employee::with('user')->with('designation')->with('department')->with('team')->paginate(15);
        }

        $responseData['data']['employees'] = $employees;

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
            'user_id' => 'required|numeric|exists:users,id',
            'designation_id' => 'required|numeric|exists:designations,id',
            'department_id' => 'required|numeric|exists:departments,id',
            'team_id' => 'required|numeric|exists:teams,id',
            'id_number' => 'required|string|max:255',
            'payroll_number' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'is_department_head' => 'required|boolean|max:255',
            'is_team_head' => 'required|boolean|max:255',
        ]);

        if (!$validator->fails()) {
            if ($request->is_department_head) {
                $employees = Employee::where('department_id', $request->department_id)->where('is_department_head', '1')->get();

                foreach ($employees as $employee) {
                    $employee->is_department_head = 0;

                    $employee->save();
                }
            }

            if ($request->is_team_head) {
                $employees = Employee::where('team_id', $request->team_id)->where('is_team_head', '1')->get();

                foreach ($employees as $employee) {
                    $employee->is_team_head = 0;

                    $employee->save();
                }
            }

            $employee = new Employee;

            $employee->user_id = $request->user_id;
            $employee->designation_id = $request->designation_id;
            $employee->department_id = $request->department_id;
            $employee->team_id = $request->team_id;
            $employee->id_number = $request->id_number;
            $employee->payroll_number = $request->payroll_number;
            $employee->registration_number = $request->registration_number;
            $employee->is_department_head = $request->is_department_head;
            $employee->is_team_head = $request->is_team_head;

            $employee->save();

            $responseData['success'] = true;
            $responseData['data']['employee'] = $employee;
            $responseData['message'] = 'Employé créé avec succès';

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

        $employee = Employee::find($id);
        
        if (!$employee) {
            $responseData['success'] = false;
            $responseData['message'] = 'Employé non trouvé';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['employee'] = $employee;

        return response()->json($responseData, 404);
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

        $employee = Employee::find($id);
        
        if (!$employee) {
            $responseData['success'] = false;
            $responseData['message'] = 'Employé non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'designation_id' => 'required|numeric|exists:designations,id',
            'department_id' => 'required|numeric|exists:departments,id',
            'team_id' => 'required|numeric|exists:teams,id',
            'id_number' => 'required|string|max:255',
            'payroll_number' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'is_department_head' => 'required|boolean',
            'is_team_head' => 'required|boolean',
        ]);

        if (!$validator->fails()) {
            if ($request->is_department_head) {
                $employees = Employee::where('department_id', $request->department_id)->where('is_department_head', '1')->get();

                foreach ($employees as $model) {
                    $model->is_department_head = 0;

                    $model->save();
                }
            }

            if ($request->is_team_head) {
                $employees = Employee::where('team_id', $request->team_id)->where('is_team_head', '1')->get();

                foreach ($employees as $model) {
                    $model->is_team_head = 0;

                    $model->save();
                }
            }

            $employee->user_id = $request->user_id;
            $employee->designation_id = $request->designation_id;
            $employee->department_id = $request->department_id;
            $employee->team_id = $request->team_id;
            $employee->id_number = $request->id_number;
            $employee->payroll_number = $request->payroll_number;
            $employee->registration_number = $request->registration_number;
            $employee->is_department_head = $request->is_department_head;
            $employee->is_team_head = $request->is_team_head;

            $employee->save();

            $responseData['success'] = true;
            $responseData['data']['employee'] = $employee;
            $responseData['message'] = 'Employé mis à jour avec succès';

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

        $employee = Employee::find($id);
        
        if (!$employee) {
            $responseData['success'] = false;
            $responseData['message'] = 'Employé non trouvé';

            return response()->json($responseData, 404);
        }

        $employee->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'Employé supprimé avec succès';

        return response()->json($responseData, 200);
    }
}
