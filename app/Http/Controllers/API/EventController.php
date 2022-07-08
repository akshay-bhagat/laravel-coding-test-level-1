<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Event as EventResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class EventController extends BaseController
{

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // only authenticated users can create, update and delete events
        $this->middleware('auth');
    }
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

    /**
     * Server side Events data caching with redis
     * 
     * Retrieve all events from the cache or, if they don't exist,
     * Retrieve them from the database and add them to the cache.
     */
    public function cacheEvents()
    {
        try {
            $events = Cache::remember('events', now()->addMinutes(300), function ()
            {   
                $data = array();
                $eventData = Event::orderBy('createdAt', 'desc')->take(30)->get();

                foreach ($eventData as $event) {
                    $data[] = array(
                        'id' => $event->id,
                        'name' => $event->name,
                        'slug' => $event->slug,
                        'startAt' => $event->startAt,
                        'endAt' => $event->endAt,
                        'updatedAt' => $event->updatedAt,
                        'createdAt' => $event->createdAt,
                    );
                }
                return $data;
            });

            if ($events) {

               return $this->handleResponse(new EventResource($events), 'Event retrived successfully');
            }
            return $this->handleError('Event not found!');
            
        } catch (\Throwable $th) {
            // throw $th;
            return $this->handleError($th, 'Event not found!', 501);
        }
    }

}
