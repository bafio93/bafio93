@extends("layouts.upr")
@section("content")
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    @if (session('status'))
        <script>
        Swal.fire(
            '{{__('index_upr.Deleted')}}',
            '{{__('index_upr.Confirmation')}}',
            'success'
        )
        </script>
    @endif

<div id="dashboard-index" class="container">
    <h1 class="mt-5">{{__('index_upr.Your_flats')}}</h1>
    {{-- <ul class="list-group"> --}}
        @forelse($flats as $flat)
            <div class="card shadow my-5">
                <div class="row no-gutters">
                    <div class="col-md-5">
                        <div class="card-body">
                            <h4 class="card-title">{{ $flat->title }}</h4>
                            <img class="card-img" src="{{asset('storage/' .$flat->img_uri)}}" alt="flat picture">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body">
                            <p class="card-text"><i class="fas fa-map-marker-alt mr-2"></i> {{__('index_upr.Address')}}: {{ $flat->address }}</p>
                            <!-- Vado alla view per vedere i dettagli: -->
                            <a class="btn btn-info mr-2" href="{{ route('upr.flats.show' , ['flat' => $flat->id]) }}">{{__('index_upr.Details')}}</a>
                            <!-- Vado alla view di modifica: -->
                            <a class="btn btn-warning m-2" href="{{ route('upr.flats.edit', ['flat' => $flat->id]) }}" >{{__('index_upr.Edit_details')}}</a>
                            <!--Form per la destroy: -->
                            <form class="form-inline" action="{{ route('upr.flats.destroy', ['flat' => $flat->id]) }}" method="post" style='display:inline-block'>
                            <input id="delete_flat" type="submit" class="btn btn-danger m-2" data-id="{{$flat->id}}" value="{{__('index_upr.Delete')}}">
                                @csrf
                                @method('DELETE')
                            </form>
                            <!-- Messaggi ricevuti -->
                            @if ($flat->messages()->count() > 0)
                                    <p class="mt-3"><i class="far fa-envelope mr-2"></i> {{__('index_upr.Messages')}}: <a href="{{ route('messages.index') }}">{{$flat->messages()->count()}}</a></p>
                            @else
                                <p class="mt-3"><i class="far fa-envelope mr-2"></i> {{__('index_upr.No_messages')}}</p>
                            @endif
                            <p><i class="fas fa-eye mr-2"></i> {{__('index_upr.Visits')}} {{$flat->view}}</p>
                            <!-- Vado alla view per la sponsorizzazione: -->
                            {{-- Occorrono un insieme di condizioni: esiste? Oppure è scaduta? --}}
                            @if (array_key_exists($flat->id,$flat_sponsored))
                                {{-- Dichiaro alcune variabili per semplificarci la vita con le date: --}}
                                @php
                                    $sponsor_hours = $flat_sponsored[$flat->id]->hours;
                                    $start_date = $flat_sponsored[$flat->id]->created_at;
                                    $hour_diff = now()->diffInHours($start_date);
                                @endphp
                                {{-- Qui esiste, vediamo se è ancora valida: --}}
                                @if( $hour_diff < $sponsor_hours )
                                    {{-- Qui esiste ed è ancora valida: vediamo quanto dura ancora! --}}
                                    <p>
                                        {{__('index_upr.Sponsor_date')}} {{ $start_date }},  {{__('index_upr.Sponsor_use')}} {{ $hour_diff }} {{__('index_upr.Sponsor_hours')}} {{ $sponsor_hours }}.
                                    </p>
                                @else
                                    {{-- Qui esiste, ma è scaduta: consentiamo di rinnovare la sponsorizzazione. --}}
                                    <p>
                                        {{-- Ultima sponsorizzazione terminata. --}}
                                        <a class="btn btn-success my-2" href="{{ route('upr.flats.sponsor', ['flat' => $flat->id])}}">{{__('index_upr.Sponsor')}}</a>
                                    </p>
                                @endif
                            @else
                                {{-- Qui non esiste, quindi si può sponsorizzare! --}}
                                <p>
                                    {{-- Nessuna sponsorizzazione attiva! --}}
                                    <a class="btn btn-success my-2" href="{{ route('upr.flats.sponsor', ['flat' => $flat->id])}}">{{__('index_upr.Sponsor')}}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        {{-- </div> --}}

            {{-- <li class="list-group-item list-group-item-dark">
        </li> --}}
        @empty
            <li class="list-group-item list-group-item-warning"> {{__('index_upr.No_flat')}}</li>
        @endforelse
    {{-- </ul> --}}
</div>
@endsection
