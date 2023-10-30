<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MyEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class AppointmentController extends BaseController
{
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
