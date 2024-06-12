

<div class="row">
    <div class="col-md-12 mx-auto grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-primary">INSERTION UNITE</h4>
                        <form class="forms-sample" action="save_unite" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputUsername1">Nom</label>
                                <input type="text" name="nom" class="form-control" id="exampleInputUsername1">
                            </div>
                            
                            @if(isset($error))
                            <div class="mt-3">
                                <p class="text-danger text-small">{{ $error; }}</p>
                            </div>
                            @endif
                            <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    class="btn btn-gradient-primary me-2">Creer
                                </button>
                            <a href="./reception-list" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h4 class="card-title text-primary">LISTE UNITE</h4>
                        <table class="table table-no-border align-middle">
                            <thead>
                                <tr class="table-primary">
                                    <td>Nom</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listUnite as $item)
                                <tr>
                                    <td>{{ $item->unit_name }}</td>
                                    <td>
                                        <a href="delete_unite?id_unite={{ $item->id_unit }}" class="text-danger"><i class="mdi mdi-close action-icon"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>