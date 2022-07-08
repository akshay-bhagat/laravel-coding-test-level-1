<?php

namespace App\Http\Controllers;
use App\Models\Event;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Mail\Event as EventNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Client\ConnectionException;
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $event = Event::orderBy('createdAt', 'desc')->paginate(5); 
        return view('events.index', ['events' => $event, 'i' => 0]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $emailTo['email'] = 'anc-test@yopmail.com';
        $request = Request::create('/api/v1/events', 'POST',[
            'name'=> $request->name,
            'slug'=> $request->slug,
            'startAt'=> date("Y-m-d H:i:s", strtotime($request->startAt)),
            'endAt'  => date("Y-m-d H:i:s", strtotime($request->endAt)),
            ]);
      
          $response = Route::dispatch($request);
          if ($response->status() == 200 && $response->getData()->success) {
            
            // Send Email notification
            $this->sendMail($emailTo, $request);
            return redirect()->route('events.index')->with('success', $response->getData()->message);
           }

          return redirect()->back()->withErrors($response->getData()->message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id); 
        return view('events.show',compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id); 
        return view('events.edit',compact('event'));
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
        $request = Request::create("/api/v1/events/$id", 'PUT',[
            'name'=> $request->name,
            'slug'=> $request->slug,
            'startAt'=> date("Y-m-d H:i:s", strtotime($request->startAt)),
            'endAt'  => date("Y-m-d H:i:s", strtotime($request->endAt)),
            ]);
      
            $response = Route::dispatch($request);

           if ($response->status() == 200 && $response->getData()->success) {
            return redirect()->route('events.index')->with('success', $response->getData()->message);
           }

          return redirect()->back()->withErrors($response->getData()->message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = Request::create("/api/v1/events/$id", 'DELETE', ['id'=> $id]);
      
            $response = Route::dispatch($request);

           if ($response->status() == 200 && $response->getData()->success) {
            return redirect()->route('events.index')->with('success', $response->getData()->message);
           }

          return redirect()->back()->withErrors($response->getData()->message);
    }

    public function sendMail($emailTo, $emailData) 
    {
    $validator = Validator::make($emailTo, [
         'email' => 'required|email'
    ]);

    if ($validator->fails()) {
        return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
    }  

        Mail::to($emailTo)->send(new EventNotification($emailTo, $emailData));
        return new JsonResponse(
            [
                'success' => true, 
                'message' => "A new Event was created."
            ], 
            200
        );
    
    }

   /**
    * Used a Public external API to fetch the data,
    * Also implemented search feature on the API
    */
    public function search(Request $request)
    {
        $url = "https://www.themealdb.com/api/json/v1/1/search.php?s=";
        $search_query = $url . $request->search;
        $client = new Client();
        $response = $client->request('GET', $search_query);
        if ($response->getStatusCode()== 200 && $response->getBody()) {
            $result = json_decode($response->getBody())->meals;
            return view('events.search', ['mealsData' => $result, 'i' => 0]);
        }

          return redirect()->back()->withErrors($response->getBody());
    }
}
