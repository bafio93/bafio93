@extends("layouts.upr")
@section("content")
<div id="upr-create" class="container mb-5">
    <div class="row my-5">
        <div class="col-lg-12">
            <h1>{{__('create.Insert_flat')}}</h1><br>
            <a class="btn btn-info float-right" href="{{ route('upr.flats.index') }}">{{__('create.Come_back')}}</a>
            <p>{{__('create.Filling')}}</p>
        </div>
    </div>
    <div class="card col-lg-10 bg-light">
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-lg-12">
                    <form id="create" method="POST" action="{{ route('upr.flats.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Inserimento titolo(descrizione) -->
                        <div class="form-group row">
                            <label for="title" class="col-md-3 col-form-label text-md-right">{{ __('create.Description') }}<i class="fas fa-home ml-3"></i></label>
                            <div class="col-md-9">
                                <input id="title" class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title')}}" required>
                                <div class="title invalid-tooltip">
                                    {{__('create.Invalid_title')}}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento numero di stanze -->
                        <div class="form-group row">
                            <label for="room_qty" class="col-md-3 col-form-label text-md-right">{{ __('create.Number_of_rooms') }}<i class="fas fa-door-open ml-3"></i></label>
                            <div class="col-md-2">
                                <input id="room_qty" class="form-control @error('room_qty') is-invalid @enderror" type="number" name="room_qty" value="{{ old('room_qty')}}" required>
                                <div class="room_qty invalid-tooltip">
                                    {{__('create.Invalid_element')}}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento numero di letti -->
                        <div class="form-group row">
                            <label for="bed_qty" class="col-md-3 col-form-label text-md-right">{{ __('create.Number_of_beds') }}<i class="fas fa-bed ml-3"></i></label>
                            <div class="col-md-2">
                                <input id="bed_qty" class="form-control @error('bed_qty') is-invalid @enderror" type="number" name="bed_qty" value="{{ old('bed_qty')}}" required>
                                <div class="bed_qnty invalid-tooltip">
                                    {{__('create.Invalid_element')}}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento numero di bagni -->
                        <div class="form-group row">
                            <label for="bath_qty" class="col-md-3 col-form-label text-md-right">{{ __('create.Number_of_baths') }}<i class="fas fa-bath ml-3"></i></label>
                            <div class="col-md-2">
                                <input id="bath_qty" class="form-control @error('bath_qty') is-invalid @enderror" type="number" name="bath_qty" value="{{ old('bath_qty')}}" required>
                                <div class="bath_qty invalid-tooltip">
                                    {{__('create.Invalid_element')}}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento metri quadri -->
                        <div class="form-group row">
                            <label for="sq_meters" class="col-md-3 col-form-label text-md-right">{{ __('create.Square_meters') }} <i class="fas fa-ruler ml-3"></i></label>
                            <div class="col-md-2">
                                <input id="sq_meters" class="form-control @error('sq_meters') is-invalid @enderror" type="number" name="sq_meters" value="{{ old('sq_meters')}}" required>
                                <div class="sq_meters invalid-tooltip">
                                    {{__('create.Invalid_mq')}}
                                </div>
                            </div>
                        </div>

                        <!-- searchbox indirizzo collegato a tomtom -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label text-md-right tt-address-label">{{ __('create.Address') }}<i class="fas fa-map-marker-alt ml-3"></i></label>
                            <div class="col-md-9">
                                {{-- <input id="address" type="text" name="address" value="{{ $flat->address }}" required hidden> --}}
                                <div id="address-edit" class="fuzzy-create">
                                </div>
                            </div>
                        </div>

                        <!-- campi nascosti per Inserimento latitudine recuperato da API tomtom -->
                        <input id="lat" type="text" name="lat" hidden>
                        <!-- campi nascosti per Inserimento longitudine recuperato da API tomtom -->
                        <input id="lon" type="text" name="lon" hidden>

                        <!-- Inserimento active -->

                        <!-- Inserimento active -->
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="active" name="active" value="1" required>
                                    <label for="active" class="col-form-label text-md-right">{{ __('create.Show_on_site') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="active" name="active" value="0" required>
                                    <label for="active" class="col-form-label text-md-right">{{__('create.Hide_on_site')}}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Inserimento uri immagine -->
                        <div class="col-form-group row">
                            <div class="col-md-9 offset-md-3">
                                <label for="img_uri" class="form-control-label">{{__('create.Upload')}}</label>
                                <input id="img_uri" type="file" class="form-control-file @error('img_uri') is-invalid @enderror" name="img_uri">
                                <div class="img_uri invalid-tooltip">
                                    {{__('create.Invalid_img')}}
                                </div>
                            </div>
                        </div>

                        <!-- PARTE DEI SERVIZI: -->
                        <div class="col-md-9 offset-md-3">
                            <h4 class="mt-4">{{__('create.Service')}}</h4>
                            <div class="col-form-group row">
                                <div class="col-12">
                                    @forelse ($servizi as $service)
                                    <input type="checkbox" id="{{ $service -> id }}" name="{{ $service -> name }}" value="{{ $service -> id }}">
                                    <label for="{{ $service -> id }}"><i class="{{ $service->fa_icon }} mx-3"></i> {{ $service -> name }}</label><br>
                                    @empty
                                    <p>{{__('create.Invalid_service')}}</p>
                                    @endforelse
                                </div>
                            </div>
                            <!-- pulsante Invio modulo -->
                            <div class="form-group">
                                <input id="crea" type="submit" disabled class="crea btn btn-primary my-3" value="{{__('create.Btn_create')}}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
