@php
    // Creo un array nella session per memorizzare le pagine visitate dagli utenti e memorizzo all'interno l'url della pagina corrente visitata
    session()->push('clicked_url', url()->current());

@endphp

@extends('layouts.short')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        @if (session('status'))
            <script>
            Swal.fire(
                'Inviato!',
                'Messaggio inviato correttamente',
                'success'
            )
            </script>
        @endif
    <div id="flat-det" class="container my-5">
        <h2 id="title" class="my-5 text-center">{{$flat->title}}</h2>
        <div class="row">
            <div class="col-12">
                <img class="rounded d-block w-100" src="{{asset('storage/' .$flat->img_uri)}}" alt="flat picture">
            </div>
        </div>
        <div class="row my-5">
            <div class="col-md-6">
                <h3 class="mt-5">{{__('dett_app.Flat_det')}}</h3>
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td class="text-right">{{__('dett_app.Address')}}</td>
                            <td class="text-center"><i class="fas fa-map-marker-alt"></i></td>
                            <td id="address">{{$flat->address}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{__('dett_app.Num_rooms')}}</td>
                            <td class="text-center"><i class="fas fa-door-open"></i></td>
                            <td>{{$flat->room_qty}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{__('dett_app.Num_beds')}}</td>
                            <td class="text-center"><i class="fas fa-bed"></i></td>
                            <td>{{$flat->bed_qty}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">{{__('dett_app.Num_baths')}}</td>
                            <td class="text-center"><i class="fas fa-bath"></i></td>
                            <td>{{$flat->bath_qty}}</td>
                        </tr>
                    </tbody>
                </table>

                <h3>{{__('dett_app.Service')}}</h3>
                <table class="table table-striped table-bordered">
                    <tbody>
                        @if(($flat->services)->isNotEmpty())
                            @foreach ($flat->services as $service)
                            <tr>
                                <td class="text-center">
                                    <i class="{{ $service->fa_icon }}"></i>
                                </td>
                                <td>
                                    {{$service->name}}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
            <!-- form user info -->
                <div class="card bg-light card-outline-secondary my-5">
                    <div class="card-header">
                    @if (Auth::user() && Auth::user()->id == $flat->user->id)
                            <h3 class="mt-1">{{__('dett_app.Owner')}}</h3>
                    @else
                            <h3 class="mt-1">{{__('dett_app.Contact')}}</h3>
                    @endif
                    </div>
                    @if (Auth::user() && Auth::user()->id == $flat->user->id)
                            <div class="card-body invisible">
                    @else
                    <div class="card-body mess_box">
                    @endif
                        <form class="form" action="{{route('send.mail')}}" method="post" role="form" autocomplete="on">
                        @csrf
                            <fieldset
                                @if (Auth::user() && Auth::user()->id == $flat->user->id)
                                    disabled>
                                @else
                                    >
                                @endif
                                <label for="email" class="col-form-label mb-0">Email</label>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" name="msg_email" id="msg_email" required class="form-control @error('msg_email') is-invalid @enderror"
                                            @if (Auth::user() && Auth::user()->id != $flat->user->id)
                                                value="{{Auth::user()->email}}">
                                            @else
                                                value="{{old('msg_email')}}">
                                            @endif
                                            {{-- <div class="msg_mail valid-feedback">
                                                Inserimento corretto!
                                            </div> --}}
                                            <div class="msg_mail invalid-tooltip">
                                                {{__('dett_app.Invalid_mail')}}
                                            </div>
                                            @error('msg_email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="message" class="col-form-label">{{__('dett_app.Message')}}</label>
                                        <textarea rows="6" value="text_msg" name="text_msg" id="text_msg" class="form-control @error('text_msg') is-invalid @enderror" required="">{{ old('text_msg') }}</textarea>
                                            {{-- <div class="text_msg valid-feedback">
                                                Inserimento corretto!
                                            </div> --}}
                                            <div class="text_msg invalid-tooltip">
                                                {{__('dett_app.Invalid_num')}}
                                            </div>
                                            @error('text_msg')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>
                                </div>
                                <input type="text" name="flat_id" value="{{$flat->id}}" hidden>
                                <input type="text" name="email_owner" value="{{$flat->user->email}}" hidden>
                                <input type="text" name="name_owner" value="{{$flat->user->name}}" hidden>
                                <input type="text" name="flat_title" value="{{$flat->title}}" hidden>
                                <input id="latNumber" type="text" name="lon" value="{{$flat->lon}}" hidden>
                                <input id="lonNumber" type="text" name="lat" value="{{$flat->lat}}" hidden>
                                <button type="submit" class="invio btn btn-success mt-3" disabled>{{__('dett_app.Send')}}</button>
                            </fieldset>
                        </form>
                    {{-- @endif --}}
                    </div>
                </div>
                <!-- /form user info -->
            </div>
        </div>
        <br>
        <h3>{{__('dett_app.Map')}}</h3>
        <div id='map' class='full-map'>

        </div>
    </div>
@endsection
