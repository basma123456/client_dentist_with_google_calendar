<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MyEvent;
use App\Models\User;
use Carbon\Carbon;
use Google\Service\AnalyticsData\BetweenFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\GoogleCalendar\Event;

class AuthinticationController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError(422, null, $validator->errors(), 'Validation Error.');
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse(201, 'you registered successfully', $success);
    }


    /********************************************/


    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /*******************************************/


    public function logout()
    {
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
            return $this->sendResponse(200, 'you signed out successfully', null);
        }
        return $this->sendError(500, null, null, ' you are not signed in.');
    }



}
