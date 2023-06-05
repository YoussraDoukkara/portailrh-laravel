<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('page')) {
            $users = User::with('employee')->paginate(15);
        } else {
            $users['data'] = User::with('employee')->get();
        }

        $responseData['success'] = true;
        $responseData['data']['users'] = $users;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if (!$validator->fails()) {
            $user = new User;

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();

            $responseData['success'] = true;
            $responseData['data']['user'] = $user;
            $responseData['message'] = 'Utilisateur créé avec succès';

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

        $user = User::find($id);
        
        if (!$user) {
            $responseData['success'] = false;
            $responseData['message'] = 'Utilisateur non trouvé';

            return response()->json($responseData, 404);
        }

        $responseData['success'] = true;
        $responseData['data']['user'] = $user;

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

        $user = User::find($id);
        
        if (!$user) {
            $responseData['success'] = false;
            $responseData['message'] = 'Utilisateur non trouvé';

            return response()->json($responseData, 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        if (!$validator->fails()) {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $responseData['success'] = true;
            $responseData['data']['user'] = $user;
            $responseData['message'] = 'Utilisateur mis à jour avec succès';

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

        $user = User::find($id);
        
        if (!$user) {
            $responseData['success'] = false;
            $responseData['message'] = 'Utilisateur non trouvé';

            return response()->json($responseData, 404);
        }

        if (optional($user->employee)->timesheets) {
            $user->employee->timesheets()->delete();
        }

        if ($user->employee) {
            $user->employee()->delete();
        }

        $user->delete();

        $responseData['success'] = true;
        $responseData['message'] = 'User deleted successfully';

        return response()->json($responseData, 200);
    }
}
