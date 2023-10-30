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

    /**************************************/

//    public function makeAppointment(Request $request)
//    {
//        $event = new Event;
//        $event->name = 'pay for basma app';
//        $event->startDateTime = \Carbon\Carbon::now();
//        $event->endDateTime = \Carbon\Carbon::now()->addHours(1);
//        $event->save();
//        $e =Event::get();
//        dd($e);
//
//    }
    public function makeAppointment(Request $request)
    {
        try {
            $startTime = Carbon::parse($request->input('my_date') . ' ' . $request->input('start_time'));
            $endTime = (clone $startTime)->addHour();
            $oldEvent = MyEvent::orWhereBetween('start', [$startTime, $endTime])->orWhereBetween('end', [$startTime, $endTime])->get(['event_id', 'id']);

            if ($oldEvent && $oldEvent->count()) {
                return $this->sendError(404, 'it is not empty , please choose another time or delete this appointment first', $oldEvent, 'it is not empty , please choose another time or delete this appointment first');
            }

            $n = Event::create([
                'name' => $request->name,
                'startDateTime' => $startTime,
                'endDateTime' => $endTime,
            ]);
            $myEvent = MyEvent::create([
                'user_id' => \auth()->id() ?? null,
                'start' => $n->startDateTime,
                'end' => $n->endDateTime,
                'status' => 1,
                'is_all_day' => null,
                'title' => $n->name,
                'description' => null,
                'event_id' => $n->id,
            ]);
            return $this->sendResponse(200, 'you booked this appointment  successfully', ['my_event' => $myEvent, 'event' => $n]);
        } catch (\Exception $e) {
            return $this->sendError(404, 'failed', null, 'failed');
        }

    }

    /***************************************/

    public function deleteAppointment($eventId)
    {
        try {
            $event = Event::find($eventId);
            $event->delete();
            $myEvent = MyEvent::where('event_id', $eventId)->delete();
            return $this->sendResponse(200, 'you deleted appontment  successfully', $myEvent);
        } catch (\Exception $e) {
            return $this->sendError(404, 'failed', null, 'failed');
        }
    }

    /***************************************/


}
