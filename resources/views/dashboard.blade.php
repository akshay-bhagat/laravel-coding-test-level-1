<x-app-layout>
    <style>
    .btn {
        border: none;
        border-top: 3px solid #333;
        border-bottom: 3px solid #333;
        border-left: 3px solid #fff;
        border-right: 3px solid  #fff;
        color: #333;
        font-family: inherit;
        font-size: inherit;
        color: inherit;
        background: none;
        cursor: pointer;
        padding: 15px;
        display: inline-block;
        margin: 15px 30px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        outline: none;
        position: relative;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
        }
        
        .btn:hover, .btn:active {
        color: #2072d7;
        border: 3px solid #333;
        }
        
        .btn:hover:after {
        height: 84%;
        opacity: 1;
        }
        .btn:after {
        width: 95%;
        height: 0;
        top: 50%;
        left: 50%;
        border: 1px solid #fff;
        background: #333;
        opacity: 0;
        -webkit-transform: translateX(-50%) translateY(-50%);
        -moz-transform: translateX(-50%) translateY(-50%);
        -ms-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
        
        content: '';
        position: absolute;
        z-index: -1;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
        }
    </style>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>

        <div style="text-align: right">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    ## This Project is created by <b> Akshay Bhagat </b>. <a class="underline text-sm text-gray-600 hover:text-gray-900" href="https://akshaybhagat.in/" target="_blank">  Click here to connect with me</a>

                    <div class="pull-right">
                        <a class="btn btn-success" href="{{ route('events.index') }}"> {{ __('View Events') }}</a>
                    </div>

                    <hr/>
                    <hr/>
                    <br/>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">## Calling of an external API(s) and display the data in the UI</h2>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <form action="{{ route('api.search') }}" method="POST">
                                @csrf
                
                                <div class="mb-3">
                                    <label for="">Search</label>
                                    <input type="text" class="form-control" name="search">
                                    Search meals name such as: Moussaka, Corba, Wontons, Pancakes, etc.
                                </div>
                
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
