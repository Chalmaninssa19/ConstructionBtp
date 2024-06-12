@if(isset($error))
<div class="messages">
    <div class="alert alert-danger">
        {{ $error }}
    </div>
</div>
@endif
@if(isset($message))
<div class="messages">
    <div class="alert alert-success">
        {{ $message }}
    </div>
</div>
@endif
<div id="error"></div>
<div id="message"></div>

<div class="row">
    <div class="col-8 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary" style="text-align: center">EFECTUER VOTRE PAIMENT</h4>
                <div class="mt-4">
                    <form action="save_payment" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date paiement</label>
                            <input type="date" name="date_payment" class="form-control" id="dateInput">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Montant </label>
                            <input type="number" step="0.01" name="amount" class="form-control" id="amountInput">
                        </div>
                        <input type="hidden" name="id_estimate" value="{{ $id_estimate }}" id="id_estimate"/>
                            <div class="mt-3">
                                <p class="text-danger text-small error"></p>
                            </div>
                        <div class="mt-3">
                            <button type="button" onclick="paye()"
                                class="btn btn-gradient-primary px-5 me-2">Payer</button>
                            <a  href="estimate_list" class="btn btn-light">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>