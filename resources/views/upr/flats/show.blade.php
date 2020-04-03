@extends("layouts.upr")
@section("content")
<div id="show-details" class="container mb-5">
    <div class="row my-5">
        <div class="col-lg-12">
            <h1 class="float-left">{{__('dett_upr.Flat_det')}}</h1>
            <a class="btn btn-info float-right" href="{{ route('upr.flats.index') }}">{{__('dett_upr.Back')}}</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <h3>{{ $flat->title }}</h3>
            <h5>{{ $flat->address }}</h5>
            <img class="card-img" src="{{asset('storage/' .$flat->img_uri)}}" alt="flat picture">
        </div>
        <div class="col-lg-6">
            <h5 class="my-3">{{__('dett_upr.Features')}}</h5>
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td class="text-center"><i class="fas fa-door-open"></i></td>
                        <td>{{__('dett_upr.Rooms')}}</td>
                        <td>{{ $flat->room_qty }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="fas fa-bed"></i></td>
                        <td>{{__('dett_upr.Beds')}}</td>
                        <td>{{ $flat->bed_qty }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="fas fa-bath"></i></td>
                        <td>{{__('dett_upr.Baths')}}</td>
                        <td>{{ $flat->bath_qty }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="fas fa-ruler"></i></td>
                        <td>{{__('dett_upr.Square_meters')}}</td>
                        <td>{{ $flat->sq_meters }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="fas fa-globe"></i></td>
                        <td>{{__('dett_upr.Latitude')}}</td>
                        <td>{{ $flat->lat }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="fas fa-globe"></i></td>
                        <td>{{__('dett_upr.Longitude')}}</td>
                        <td>{{ $flat->lon }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="far fa-check-circle"></i></td>
                        <td>{{__('dett_upr.Active')}}</td>
                        <td>{{ $flat->active }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="far fa-plus-square"></i></td>
                        <td>{{__('dett_upr.Insert_date')}}</td>
                        <td>{{ $flat->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="text-center"><i class="far fa-edit"></i></td>
                        <td>{{__('dett_upr.Last_edit')}}</td>
                        <td>{{ $flat->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
            <h5 class="mt-4">{{__('dett_upr.Service')}}</h5>
            <table class="table table-striped table-bordered">
                <tbody>
                    @forelse($flat->services as $service)
                    <tr>
                        <td class="text-center"><i class="{{ $service->fa_icon }}"></i></td>
                        <td>{{ $service->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td>
                            {{__('dett_upr.No_service')}}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
