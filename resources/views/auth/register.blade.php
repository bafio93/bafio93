@extends('layouts.app')

@section('login-content')
<div class="container">
    <div id="registerLeftBox" class="row py-5">
        <div class="col-lg-6 card border-light bg-light shadow">
            <div class="card-body">
                <div>
                    <h3>{{ __('registration.Register') }}</h3>
                </div>
                <form class="needs-validation" novalidate method="POST" action="{{ route('register') }}">
                    @csrf
                    <!-- Aggiunto campo inserimento nome -->
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('registration.Name') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" minlength="3" maxlength="20" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus style="text-transform:capitalize">
                            {{-- <div class="name valid-feedback">
                                {{ __('registration.Valid') }}
                            </div> --}}
                            <div class="name invalid-tooltip">
                                {{ __('registration.Invalid_name') }}
                            </div>
                        </div>
                    </div>
                    <!-- Aggiunto campo inserimento cognome -->
                    <div class="form-group row">
                        <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('registration.Surname') }}</label>
                        <div class="col-md-6">
                            <input id="surname" type="text" minlength="3" maxlength="20" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus style="text-transform: capitalize">
                            {{-- <div class="surname valid-feedback">
                                {{ __('registration.Valid') }}
                            </div> --}}
                            <div class="surname invalid-tooltip">
                                {{ __('registration.Invalid_surname') }}
                            </div>
                        </div>
                    </div>
                    <!-- Aggiunto campo inserimento data di nascita -->
                    <div class="form-group row">
                        <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('registration.Date_of_birth') }}</label>
                        <div class="col-md-6">
                            <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth') }}" required autocomplete="date_of_birth" autofocus>
                            {{-- <div class="date valid-feedback">
                                {{ __('registration.Valid') }}
                            </div> --}}
                            <div class="date invalid-tooltip">
                                {{ __('registration.Invalid_age') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('registration.E-mail') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"  {{-- onclick="validateEmail('#email')"--}} >
                            {{-- <div class="mail valid-feedback">
                                {{ __('registration.Valid') }}
                            </div> --}}
                            <div class="mail invalid-tooltip">
                                {{ __('registration.Invalid_mail') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        <div class="col-md-6">
                            <input id="password" type="password" minlength="8" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            {{-- <div class="valid-feedback">
                                {{ __('registration.Valid') }}
                            </div> --}}
                            <div class="invalid-tooltip">
                                {{ __('registration.Invalid_pass') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('registration.Confirm_Password') }}</label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('registration.btn_confirm') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
