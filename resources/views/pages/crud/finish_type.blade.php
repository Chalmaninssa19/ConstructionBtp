<div class="row">
    <div class="col-md-12 mx-auto grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                    @if(isset($finishType))
                        <h4 class="card-title text-primary">MODIFICATION TYPE FINITION</h4>
                        <form class="forms-sample" action="update_finish_type" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputUsername1">Type finition</label>
                                <input type="text" name="designation" class="form-control" id="exampleInputUsername1" 
                                value="{{ $finishType->finish_type_name }}">
                            </div>
                            <input type="hidden" name="id_finish_type" class="form-control" id="exampleInputUsername1" value="{{ $finishType->id_finish_type }}">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Taux d'augmentation</label>
                                <input type="number" step="0.01" name="percent" class="form-control" 
                                id="exampleInputEmail1" value="{{ $finishType->increase_percent }}">
                            </div>
                            @if(isset($error))
                            <div class="mt-3">
                                <p class="text-danger text-small">{{ $error; }}</p>
                            </div>
                            @endif
                            <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    class="btn btn-gradient-primary me-2">Modifier type finition
                                </button>
                            <a href="./reception-list" class="btn btn-light">Cancel</a>
                        </form>
                    @else
                        <h4 class="card-title text-primary">INSERTION NOUVELLE TYPE FINITION</h4>
                        <form class="forms-sample" action="save_finish_type" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputUsername1">Type finition</label>
                                <input type="text" name="designation" class="form-control" id="exampleInputUsername1">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Taux d'augmentation</label>
                                <input type="number" step="0.2" name="percent" class="form-control" id="exampleInputEmail1"
                                       placeholder="">
                            </div>
                           
                            @if(isset($error))
                            <div class="mt-3">
                                <p class="text-danger text-small">{{ $error; }}</p>
                            </div>
                            @endif
                            <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    class="btn btn-gradient-primary me-2">Creer type finition
                                </button>
                        </form>
                    @endif
                    </div>
                    <div class="col-md-6">
                        <h4 class="card-title text-primary">LISTE TYPE FINITION</h4>
                        <table class="table table-no-border align-middle" id="tableau">
                            <thead>
                                <tr class="table-primary">
                                    <th>Type finition</th>
                                    <th>Taux d'augmentation</th>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->finish_type_name }}</td>
                                    <td>{{ $item->increase_percent }} %</td>
                                    <td><a href="edit_finish_type?id_finish_type={{ $item->id_finish_type }}" class="text-warning"><i
                                                class="mdi mdi-settings action-icon me-5"></i></a>
                                        <a href="delete_finish_type?id_finish_type={{ $item->id_finish_type }}" class="text-danger"><i class="mdi mdi-close action-icon"></i></a>
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