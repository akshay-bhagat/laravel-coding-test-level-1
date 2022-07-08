<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Event as EventResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends BaseController
{
    /**
     * Display a listing of the Event.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = Event::orderBy('createdAt', 'desc')->paginate(15); 
        return $this->handleResponse(new EventResource($event), 'All events retrieved successfully.');
    }

    /**
     * Return all events that are active = current datetime is within startAt and endAt
     */
    public function getActiveEvents()
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');

        $events = Event::where('startAt', '<', $now)
                        ->where('endAt', '>', $now)->paginate(15);

        if(is_null($events) || $events->total() < 1 ){
            return $this->handleError('There are no active events!', 402);
        }

        return $this->handleResponse(new EventResource($events), 'All active events retrieved successfully.');
    }

    /**
     * Create new Event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['startAt'] = date("Y-m-d H:i:s", strtotime($request->startAt));
        $input['endAt'] = date("Y-m-d H:i:s", strtotime($request->endAt));
        $validator = Validator::make($input, [
            'name' => 'required',
            'slug' => 'required|unique:events|max:255',
            'startAt' => 'date_format:Y-m-d H:i:s',
            'endAt' => 'date_format:Y-m-d H:i:s',
        ]);

        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }
        $uuid = Str::uuid()->toString();
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $input['id'] = $uuid;
        $input['createdAt'] = $now;

        $event = Event::create($input);
        return $this->handleResponse(new EventResource($event), 'Event created!');
    }

    /**
     * Display the Event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $event = Event::find($id);
        
       if (is_null($event)) {
            return $this->handleError('Event not found!');
        }
        
        return $this->handleResponse(new EventResource($event), 'Event retrieved.');
    }

    /**
     * Partially update the event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Request
     */
    public function partialUpdate(Request $request, $id)
    {
        $event = Event::find($id);
        $now = Carbon::now()->format('Y-m-d H:i:s');

        if(is_null($event)){
            return $this->handleError('Event not found!');
        }

        if($request->name){
            $event->name = $request->name;
        }
        if($request->slug){
            $event->slug = $request->slug;
        }
        if($request->startAt){
            $event->startAt = $request->startAt;
        }
        if($request->endAt){
            $event->endAt = $request->endAt;
        }

        if($request){
            $event->updatedAt = $now;
        }
        $event->save();
        $event->refresh();

        return $this->handleResponse(new EventResource($event), 'Event successfully updated!');
    }

    /**
     * Update or Create new Event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'slug' => 'required|max:255'
        ]);

        $now = Carbon::now()->format('Y-m-d H:i:s');
        $uuid = Str::uuid()->toString();
        
        
        if($validator->fails()){
            return $this->handleError($validator->errors());       
        }

        $event = Event::UpdateOrCreate([
            'id' => $id
         ],
         [
            'id' => $uuid,
            'name' => $request->name,
            'slug' => $request->slug,
            'startAt' => $request->startAt,
            'endAt' => $request->endAt,
            'updatedAt' => $now,
        ]);

        return $this->handleResponse(new EventResource($event), 'Event successfully updated!');
    }
        
    /**
     * Soft delete event.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();

        if(is_null($event)){
            return $this->handleError('Event not found!');
        }
        return $this->handleResponse([], 'Event deleted!');
    }
}
