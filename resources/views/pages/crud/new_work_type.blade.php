@if(isset($error))
<div class="messages">
    <div class="alert alert-danger">
        {{ $error }}
    </div>
</div>
@endif
@if(isset($success))
<div class="messages">
    <div class="alert alert-success">
        {{ $success }}
    </div>
</div>
@endif
<div class="row">
    <div class="col-10 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary">AJOUT NOUVEAU TYPE DE TRAVAUX</h4>
                <div class="mt-4">
                    <form action="save_work_type" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputUsername1">Code</label>
                            <input type="text" name="code" class="form-control" id="exampleInputUsername1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Designation</label>
                            <input type="text" name="designation" class="form-control" id="exampleInputUsername1">
                        </div>
                        <div class="form-group">
                            <label for="article">Unite</label>
                            <select name="unit" class="form-control form-control-sm input-height mt-2" id="articleInput">
                                @foreach ($listUnit as $item)
                                        <option selected value="{{ $item->id_unit }}">{{ $item->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Prix unitaire</label>
                            <input type="number" step="0.01" name="unit_price" class="form-control" id="exampleInputUsername1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Quantite</label>
                            <input type="number" step="0.01" name="quantity" class="form-control" id="exampleInputUsername1">
                        </div>
                        
                        @if(isset($error))
                            <div class="mt-3">
                                <p class="text-danger text-small">{{ $error; }}</p>
                            </div>
                        @endif
                        <div class="mt-3">
                            <button type="submit"
                                class="btn btn-gradient-primary px-5 me-2">Ajouter</button>
                            <a  href="work_type" class="btn btn-light">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>