
    @extends('events.layout')
 
    @section('content')
    <div class="row"  name="header">
        <div class="col-lg-12 margin-20">
            <div class="pull-left">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight"> {{ __('Event List') }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('events.create') }}"> Create New Event</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th width="280px">Action</th>
                        </tr>
                        @foreach ($events as $event)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->slug }}</td>
                            <td>
                                <form action="{{ route('events.destroy',$event->id) }}" method="POST">
                   
                                    <a class="btn btn-info" href="{{ route('events.show',$event->id) }}">Show</a>
                    
                                    <a class="btn btn-primary" href="{{ route('events.edit',$event->id) }}">Edit</a>
                   
                                    @csrf
                                    @method('DELETE')
                      
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                  
                    {{-- Pagination --}}
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
    @endsection
