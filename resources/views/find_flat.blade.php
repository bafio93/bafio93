@extends('layouts.short')

@section('content')
    <div class="container">
        <div class="row my-5">
            <div id="flat_search" class="col-sm-12 col-md-12 col-lg-6" >
                <div class="card bg-light mb-sm-4 mb-lg-0">
                    <h2 class="card-body">{{__('search.Advanced_search')}}</h2>
                    <form id="form_find">
                        <div class="col">
                            <div class="form-group">
                                <div class="fuzzy-find form-group">
                                </div>
                            </div>
                            <h4 class="text-left">{{__('search.Filter_results')}}</h4>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <!-- Inserimento numero di stanze -->
                                    <label for="room_qty" class="text-md-right">{{__('search.Number_of_rooms')}}</label>
                                    <input id="room_qty" type="number" name="room_qty" class="form-control" max= 20>
                                </div>
                                <div class="form-group col-md-4">
                                    <!-- Inserimento numero di letti -->
                                    <label for="bed_qty" class="text-md-right">{{__('search.Number_of_beds')}}</label>
                                    <input id="bed_qty" type="number" name="bed_qty" class="form-control" max= 20>
                                </div>
                                <div class="form-group col-md-4">
                                    <!-- Inserimento distanza in km -->
                                    <label for="km_radius" class="text-md-right">{{__('search.Search_radius')}}</label>
                                    <input id="km_radius" type="range" name="km_radius" min="20" max="100" value="20" onchange="distance.value = this.value" class="form-control">
                                    <output id="distance"></output>Km<br>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <h5>{{__('search.Available_services')}}</h5> <br>
                            <div class="form-group form-check">
                                <!-- Servizi Aggiuntivi -->
                                @foreach ($services as $service)
                                <input type="checkbox" id="{{$service->id}}" class="form-check-input" name="check_services" value="{{$service->id}}">
                                <label class="form-check-label" for="{{$service->name}}"></label><i class="{{$service->fa_icon}} mx-3"></i>{{$service->name}}<br>
                                @endforeach
                            </div>
                            <!-- campi nascosti necessari -->
                            <input id="searchFindMap" type="text" name="address_search" value="andratuttobene" hidden>
                            <input id="searchFind" type="text" name="address_search" value="{{ $address }}" hidden>
                            <input id="latNumberFind" type="text" name="lat" value="{{ $lat }}" hidden>
                            <input id="lonNumberFind" type="text" name="lon" value="{{ $lon }}" hidden>
                            <div class="form-group col-md-6">
                                <button id="btn_find" class="btn btn-success btn-lg" type="submit" name="btn_find">{{__('search.Search')}}</button>
                                <br>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6">
                <div id="map" class="full-map card bg-light">
                </div>
            </div>
        </div>
        <div id="find-results" class="my-5">
            <!-- Card package -->
            <div id="card_container" class="row">
            <!-- Appendo il contenuto del template handlebars -->
            </div>
        </div>
    </div>

    <!-- script handlebars -->
    <script id="card_template" type="text/x-handlebars-template">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
            <div class="card bg-light shadow mb-5">
                <img class="card-img" src="{{asset('storage/')}}/@{{ img_uri }}" alt="">
                <div class="card-img-overlay">
                    <a href="#" class="btn btn-light btn-sm"></a>
                </div>
                <div class="card-body">
                    <h4 class="card-title">@{{ title }}</h4>
                    <a class="btn btn-success stretched-link" style="position: relative;" href="{{ url('flats/details/')}}/@{{ flat_details }}">{{__('search.Details')}}</a>
                </div>
            </div>
        </div>
    </script>
@endsection
