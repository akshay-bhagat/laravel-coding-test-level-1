
    @extends('events.layout')
 
    @section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>External API for Meals: Search result</h2>
            </div>
            <br/>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('dashboard') }}"> Back</a>
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!is_null($mealsData))
                        
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white border-b border-gray-200">
                
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Area</th>
                                            <th width="280px">Youtube</th>
                                        </tr>
                                        @foreach ($mealsData as $meal)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $meal->strMeal }}</td>
                                            <td>{{ $meal->strArea }}</td>
                                            <td> 
                                                <iframe width="420" height="315"
                                                src="https://www.youtube.com/embed/{{ substr($meal->strYoutube, strpos($meal->strYoutube, "=") + 1)}}">
                                                </iframe>
                                                {{ substr($meal->strYoutube, strpos($meal->strYoutube, "=") + 1)}} </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
    @endsection
