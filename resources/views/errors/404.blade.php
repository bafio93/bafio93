@extends('layouts.err404')

@section("page_404")

        <div class="container-fluid">
            <div class="row justify-content-center">
                <section id="errore" class="col-sm-12 col-lg-10 mt-5">
                	<div class="content text-center">
                		<i class="fas fa-exclamation-triangle fa-7x my-3"></i>
                		<h1 class="mb-3">404</h1>
                		<h3 class="my-1">{{__('404_tras.Alert')}}</h3>
                		<a class="btn  btn-lg btn-success my-5 shadow" href="{{ route('home') }}">{{__('404_tras.Button')}}</a>
                	</div>
                </section>
        	</div>
            </div>
        </div>

@endsection
