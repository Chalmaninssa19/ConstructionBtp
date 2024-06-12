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


<div class="row">
    <div class="col-10 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary" style="text-align: center">IMPORTATION CSV DES DONNEES DE MAISON TRAVAUX ET DEVIS</h4>
                <div class="mt-4">
                    <form action="import_house_work_estimate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="fields">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="import_csv" name="upload_house_work" accept=".csv">
                                <label class="input-group-text" for="import_csv">Upload maison et travaux</label>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="import_csv" name="upload_estimate" accept=".csv">
                                <label class="input-group-text" for="import_csv">Upload devis</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit"
                                class="btn btn-gradient-primary px-5 me-2">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-10 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary" style="text-align: center">IMPORTATION CSV DES DONNEES DE PAIEMENT</h4>
                <div class="mt-4">
                    <form action="import_payment" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="fields">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="import_csv" name="upload_payment" accept=".csv">
                                <label class="input-group-text" for="import_csv">Upload paiement</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit"
                                class="btn btn-gradient-primary px-5 me-2">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>