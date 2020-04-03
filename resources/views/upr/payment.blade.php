@extends("layouts.upr")
@section("content")
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p>
                {{__('sponsor_upr.Duration_sponsor')}} <em>{{ $sponsor_title }}</em> {{__('sponsor_upr.For')}} <strong>{{ $sponsor_hours }} {{__('sponsor_upr.Hours')}}</strong> {{__('sponsor_upr.Sum')}} <strong>{{ $sponsor_price }}€</strong>. <br> {{__('sponsor_upr.Pay_method')}}
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <!-- Qui verrà inserito il dropin di Braintree: -->
            <div id="dropin-container"></div>
        </div>
    </div>
    <div id="payment_button" class="row d-none">
        <div class="col">
            <!-- Verifica il pagamento: -->
            <button id="submit-button">{{__('sponsor_upr.Pay_now')}}</button>
            <!-- Torna in index: -->
            <a class="btn btn-info" href="{{ route('upr.flats.index') }}">{{__('sponsor_upr.Back_dashboard')}}</a>
        </div>
    </div>
</div>
<script>
    var button = document.querySelector('#submit-button');
    braintree.dropin.create({
        authorization: "{{ Braintree_ClientToken::generate() }}",
        container: '#dropin-container'
    }, function (createErr, instance) {
        // Faccio uscire il bottone subito dopo aver creato il form di pagamento
        var element = document.getElementById("payment_button");
        element.classList.remove("d-none");
        element.classList.add("d-block");
        button.addEventListener('click', function () {
            instance.requestPaymentMethod(function (err, payload) {
                $.get('{{ route('upr.payment.process', ['id' => $id, 'sponsor_id' => $sponsor_id]) }}', {payload}, function (response) {
                    if (response.success) {
                        Swal.fire(
                            '{{__('payment_popup.Congratulations')}}',
                            '{{__('payment_popup.Payment_correct')}}',
                            'success'
                        ).then((result) => {
                            if (result.value) {
                                window.location.href =  "/upr/flats";
                            }
                        })
                    } else {
                        Swal.fire(
                            '{{__('payment_popup.Warning')}}',
                            '{{__('payment_popup.Payment_failed')}}',
                            'error'
                        )
                    }
                }, 'json');
            });
        });
    });
    // $(document).ready(function(){
    //     $("#payment_button").addClass("d-block");
    // });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

@endsection
