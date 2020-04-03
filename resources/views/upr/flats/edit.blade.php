@extends("layouts.upr")
@section("content")
<div id="show-details" class="container mb-5">
    <div class="row my-5">
        <div class="col-lg-12">
            <h1 class="float-left">{{__('edit_flat.Edit_details')}}</h1>
            <a class="btn btn-info float-right" href="{{ route('upr.flats.index') }}">{{__('edit_flat.Back')}}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <h4>{{ $flat->title }}</h4>
            <img class="rounded d-block w-100 my-3" src="{{asset('storage/' .$flat->img_uri)}}" alt="">
        </div>

        <div class="col-lg-8">
            <div class="card bg-light">
                <div class="card-body">
                    <form id="edit" method="POST" action="{{ route('upr.flats.update', ['flat' => $flat->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Inserimento titolo(descrizione) -->
                        <div class="form-group row">
                            <label for="title" class="col-form-label col-md-4 text-md-right"> {{ __('edit_flat.Description') }}<i class="fas fa-home ml-3"></i></label>
                            <div class="col-md-8">
                                <input id="title" class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ $flat->title }}" required>
                                <div class="title invalid-tooltip">
                                    {{__('edit_flat.Invalid_title')}}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento numero di stanze -->
                        <div class="form-group row">
                            <label for="room_qty" class="col-md-4 col-form-label text-md-right">{{ __('edit_flat.Number_of_rooms') }}<i class="fas fa-door-open ml-3"></i></label>
                            <div class="col-md-2 col-xs-4 col-sm-4">
                                <input id="room_qty" class="form-control @error('room_qty') is-invalid @enderror" type="number" name="room_qty" value="{{ $flat->room_qty }}" required>
                                <div class="room_qty invalid-tooltip">
                                    {{ __('edit_flat.Invalid_element') }}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento numero di letti -->
                        <div class="form-group row">
                            <label for="bed_qty" class="col-md-4 text-md-right col-form-label">{{ __('edit_flat.Number_of_beds') }}<i class="fas fa-bed ml-3"></i></label>
                            <div class="col-md-2 col-xs-4 col-sm-4">
                                <input id="bed_qty" class="form-control @error('bed_qty') is-invalid @enderror" type="number" name="bed_qty" value="{{ $flat->bed_qty }}" required>
                                <div class="bed_qnty invalid-tooltip">
                                    {{ __('edit_flat.Invalid_element') }}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento numero di bagni -->
                        <div class="form-group row">
                            <label for="bath_qty" class="col-md-4 col-form-label text-md-right">{{ __('edit_flat.Number_of_baths') }}<i class="fas fa-bath ml-3"></i></label>
                            <div class="col-md-2 col-xs-4 col-sm-4">
                                <input id="bath_qty" class="form-control @error('bath_qty') is-invalid @enderror" type="number" name="bath_qty" value="{{ $flat->bath_qty }}" required>
                                <div class="bath_qty invalid-tooltip">
                                    {{ __('edit_flat.Invalid_element') }}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento metri quadri -->
                        <div class="form-group row">
                            <label for="sq_meters" class="col-md-4 col-form-label text-md-right">{{ __('edit_flat.Square_meters') }}<i class="fas fa-ruler ml-3"></i></label>
                            <div class="col-md-2 col-xs-4 col-sm-4">
                                <input id="sq_meters" class="form-control @error('sq_meters') is-invalid @enderror" type="number" name="sq_meters" value="{{ $flat->sq_meters }}" required>
                                <div class="sq_meters invalid-feedback">
                                    {{ __('edit_flat.Invalid_mq') }}
                                </div>
                            </div>
                        </div>
                        <!-- Inserimento indirizzo -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right tt-address-label">{{ __('edit_flat.Address') }}<i class="fas fa-map-marker-alt ml-3"></i></label>
                            <div class="col-md-8">
                                <input id="address" type="text" name="address" value="{{ $flat->address }}" required hidden>
                                <div id="address-edit" class="fuzzy-edit">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('edit_flat.Coordinates') }}<i class="fas fa-globe ml-3"></i></label>
                            <div class="col-md-3 col-sm-6">
                                <!-- Inserimento lat recuperato da API tomtom se viene modificato l'indirizzo -->
                                <input id="lat" class="form-control" type="text" name="lat" value="{{ $flat->lat }}" >
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <!-- Inserimento lon recuperato da API tomtom se viene modificato l'indirizzo  -->
                                <input id="lon" class="form-control" type="text" name="lon" value="{{ $flat->lon }}" >
                            </div>
                        </div>

                        <!-- Inserimento active -->
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                @if($flat->active)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="active" name="active" value="1" required checked>
                                    <label for="active" class="col-form-label text-md-right">{{ __('edit_flat.Show_on_site') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="active" name="active" value="0" required>
                                    <label for="active" class="col-form-label text-md-right">{{ __('edit_flat.Hide_on_site') }}</label>
                                </div>
                                @else
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="active" name="active" value="1" required>
                                    <label for="active" class="col-form-label text-md-right">{{ __('edit_flat.Show_on_site') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="active" name="active" value="0" required checked>
                                    <label for="active" class="col-form-label text-md-right">{{ __('edit_flat.Hide_on_site') }}</label>
                                </div>
                            @endif
                            </div>
                        </div>
                        <!-- Inserimento uri immagine -->
                        <div class="col-form-group row">
                            <div class="col-sm-12 offset-md-4">
                                <label for="img_uri" class="form-control-label">{{ __('edit_flat.New_upload') }}</label>
                                <input id="img_uri" type="file" class="form-control-file" name="img_uri">
                            </div>
                        </div>

                        <!-- PARTE DEI SERVIZI: -->
                        <h4 class="text-center my-4">{{ __('edit_flat.Service') }}</h4>
                            <div class="col-form-group row">
                                <div class="col-sm-12 offset-md-4">
                                    @forelse ($servizi as $service)
                                        @if(in_array($service->name, $servizi_su_appartamento_array))
                                        <input type="checkbox" id="{{ $service->id }}" name="{{ $service->name }}" value="{{ $service->id }}" checked>
                                        @else
                                        <input type="checkbox" id="{{ $service->id }}" name="{{ $service->name }}" value="{{ $service->id }}">
                                        @endif
                                        <label for="{{ $service->id }}"><i class="{{ $service->fa_icon }} mx-2"></i> {{ $service->name }}</label><br>
                                    @empty
                                        <p>{{ __('edit_flat.Invalid_service') }}</p>
                                    @endforelse
                                </div>
                            </div>
                        <!-- Invio modulo -->
                        <div class="col-form-group row">
                            <div class="col-sm-12 offset-md-4">
                                <button type="submit" class="btn btn-primary my-3">{{ __('edit_flat.Btn_submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
