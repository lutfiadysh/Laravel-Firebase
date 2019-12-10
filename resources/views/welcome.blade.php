@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="text-white">Selamat Datang</h3>
                    </div>
                    <div class="card-body">
                        <span>{{Auth::user()->name}}</span>
                    </div>
                </div>
            </div>
        </div>    
    </div>

        @include('layouts.footers.auth')
    </div>
@endsection
