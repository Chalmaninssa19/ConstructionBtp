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
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="mt-4 d-flex align-items-center justify-content-between">
                    <div>
                        <a href="new_work_type" class="btn btn-outline-primary">Ajout type travaux</a>
                        <a href="deleted_work_type" class="btn btn-outline-primary">Liste supprimes</a>
                        <a href="work_type" class="btn btn-outline-primary">Liste en cours</a>
                    </div>
                </div>
                <br>
                <br>
                <h4 class="card-title text-primary">
                    @if(isset($listTitle))
                    {{ $listTitle }}
                    @else
                        {{ 'LISTE TYPE TRAVAUX' }}
                    @endif
                </h4>
                <div class="mt-4">
                    @if(isset($id_helper))
                    <form action="search_work_type" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="form-group col-md-3">
                                <input type="hidden" name="id_helper" value=1 >
                                @if(isset($text))
                                <input type="text" class="form-control mt-2" name="search" value="{{ $text }}">
                                @else
                                <input type="text" class="form-control mt-2" name="search">
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <input type="submit" class="btn btn-gradient-primary" value="Rechercher">
                            </div>
                        </div>
                    </form>
                    @else
                    <form action="search_work_type" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="form-group col-md-3">
                                @if(isset($text))
                                <input type="text" class="form-control mt-2" name="search" value="{{ $text }}">
                                @else
                                <input type="text" class="form-control mt-2" name="search">
                                @endif                            </div>
                            <div class="form-group col-md-3">
                                <input type="submit" class="btn btn-gradient-primary" value="Rechercher">
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
                    <div class="table-responsive">
                      <table class="table">
                        @if(isset($id_helper))
                        <thead>
                            <tr>
                                <th> Code </th>
                                <th> Designation </th>
                                <th> Unite </th>
                                <th> Prix unitaire </th>
                                <th> Quantite </th>
                                <th> Montant </th>
                                <th> Date suppression </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($list))
                                @foreach($list as $item)
                                <tr>
                                <td>{{ $item->code }}</td>
                                <td> {{ $item->designation }} </td>
                                <td> {{ $item->unit }} </td>
                                <td> {{ $item->unit_price }} </td>
                                <td> {{ $item->quantity }} </td>
                                <td> {{ $item->amount }} </td>
                                <td> {{ $item->deleted_at }} </td>
                                <td><a href="restore_work_type?id_work_type={{ $item->id_work_type }}" class="text-warning action-icon" style="font-size : 20px"><i class="mdi mdi-backup-restore"></i></a></td>
                                <td><a href="force_deleted_work_type?id_work_type={{ $item->id_work_type }}" class="text-danger action-icon" style="font-size : 20px"><i class="mdi mdi-delete"></i></a></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                        @else
                        <thead>
                            <tr>
                                <th> Code </th>
                                <th> Designation </th>
                                <th> Unite </th>
                                <th> Prix unitaire </th>
                                <th> Quantite </th>
                                <th> Montant </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($list))
                                @foreach($list as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td> {{ $item->designation }} </td>
                                    <td> {{ $item->unit }} </td>
                                    <td> {{ $item->unit_price }} </td>
                                    <td> {{ $item->quantity }} </td>
                                    <td> {{ $item->amount }} </td>
                                    <td><a href="modif_work_type?id_work_type={{ $item->id_work_type }}" class="text-warning action-icon" style="font-size : 20px"><i class="mdi mdi-settings"></i></a></td>
                                    <td><a href="delete_work_type?id_work_type={{ $item->id_work_type }}" class="text-danger action-icon" style="font-size : 20px"><i class="mdi mdi-delete"></i></a></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                        @endif
                      </table>
                      {{ $list->links() }}
                    </div>
            </div>
        </div>
    </div>
</div>