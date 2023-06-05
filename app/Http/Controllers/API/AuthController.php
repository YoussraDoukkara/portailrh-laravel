<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $responseData = [];

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if (!$validator->fails()) {
            if (Auth::attempt($request->only(['email', 'password']))) {
                $accessToken = Auth::user()->createToken('AuthToken')->accessToken;

                $responseData['success'] = true;
                $responseData['data']['access_token'] = $accessToken;
                $responseData['data']['user'] = User::where('id', Auth::id())->with('employee')->first();

                return response()->json($responseData, 200);
            } else {
                $responseData['success'] = false;
                $responseData['message'] = 'les informations d\'identification invalides';

                return response()->json($responseData, 401);
            }
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }
    }

    public function logout(Request $request)
    {
        $responseData = [];

        // Validate that the access token exists in the request headers
        if (!$request->bearerToken()) {
            $responseData['success'] = false;
            $responseData['message'] = 'Non autorisé';

            return response()->json($responseData, 401);
        }

        $user = Auth::user();

        $accessToken = Token::where('id', $request->user()->token()->id)->first();

        if (!$accessToken || $accessToken->user_id !== $user->id) {
            $responseData['success'] = false;
            $responseData['message'] = 'Non autorisé';

            return response()->json($responseData, 401);
        }

        $accessToken->revoke();

        $responseData['success'] = true;
        $responseData['message'] = 'Déconnexion réussie';

        return response()->json($responseData, 200);
    }

    public function send(Request $request)
    {
        $responseData = [];

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
        ]);

        if (!$validator->fails()) {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }

        if ($status === Password::RESET_LINK_SENT) {
            $responseData['success'] = true;
            $responseData['message'] = 'Nous vous avons envoyé par mail le lien de réinitialisation du mot de passe !';

            return response()->json($responseData, 200);
        } else {
            $responseData['success'] = false;
            $responseData['message'] = null;

            return response()->json($responseData, 500);
        }
    }

    public function reset(Request $request)
    {
        $responseData = [];

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if (!$validator->fails()) {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
         
                    $user->save();
                }
            );
        } else {
            $responseData['success'] = false;
            $responseData['errors'] = $validator->errors();

            return response()->json($responseData, 422);
        }

        if ($status === Password::PASSWORD_RESET) {
            $user = User::where('email', '=', $request->email)->first();

            if (!$user) {
                $responseData['success'] = false;
                $responseData['message'] = 'Utilisateur non trouvé';
    
                return response()->json($responseData, 404);
            }

            if (Auth::loginUsingId($user->id)) {
                $accessToken = Auth::user()->createToken('AuthToken')->accessToken;

                $responseData['success'] = true;
                $responseData['data']['access_token'] = $accessToken;
                $responseData['data']['user'] = User::where('id', Auth::id())->with('employee')->first();

                return response()->json($responseData, 200);
            }

            $responseData['success'] = true;
            $responseData['message'] = null;

            return response()->json($responseData, 200);
        } else {
            $responseData['success'] = false;
            $responseData['message'] = null;

            return response()->json($responseData, 500);
        }
    }
}
