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
                <h4 class="card-title text-primary" style="text-align : center">LISTE DES DEVIS EN COURS</h4>
              
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                            <tr>
                                <th> Ref </th>
                                <th> Type maison </th>
                                <th> date debut </th>
                                <th> duration </th>
                                <th> N. jour tavaux </th>
                                <th> M. total </th>
                                <th> P. deja effectue </th>
                                <th> % P. effectue </th>
                                <th> % travaux </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($list))
                            @foreach($list as $item)
                            @if($item->percent_payment < 50)
                            <tr class="table-danger">
                                <td>{{ $item->ref_estimate }}</td>
                                <td>{{ $item->house_type_designation }}</td>
                                <td> {{ $item->start_date }} </td>
                                <td> {{ $item->duration }} jours</td>
                                <td> {{ $item->n_work_day }} jours</td>
                                <td> {{ $item->getAmountTotal() }} </td>
                                <td> {{ $item->getPaymentTotal() }} Ar </td>
                                <td> {{ $item->percent_payment }} % </td>
                                <td> {{ $item->work_progressing }} % </td>
                                <td><a href="work_progress?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Avancement travaux</a></td>
                                <td><a href="details_estimate?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Details</a></td>
                            </tr>
                            @elseif($item->percent_payment > 50)
                            <tr class="table-success">
                                <td>{{ $item->ref_estimate }}</td>
                                <td>{{ $item->house_type_designation }}</td>
                                <td> {{ $item->start_date }} </td>
                                <td> {{ $item->duration }} jours</td>
                                <td> {{ $item->n_work_day }} jours</td>
                                <td> {{ $item->getAmountTotal() }} </td>
                                <td> {{ $item->getPaymentTotal() }} Ar </td>
                                <td> {{ $item->percent_payment }} % </td>
                                <td> {{ $item->work_progressing }} % </td>
                                <td><a href="work_progress?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Avancement travaux</a></td>
                                <td><a href="details_estimate?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Details</a></td>
                            </tr>
                            @else
                            <tr>
                                <td>{{ $item->ref_estimate }}</td>
                                <td>{{ $item->house_type_designation }}</td>
                                <td> {{ $item->start_date }} </td>
                                <td> {{ $item->duration }} jours</td>
                                <td> {{ $item->n_work_day }} jours</td>
                                <td> {{ $item->getAmountTotal() }} </td>
                                <td> {{ $item->getPaymentTotal() }} Ar </td>
                                <td> {{ $item->percent_payment }} % </td>
                                <td> {{ $item->work_progressing }} % </td>
                                <td><a href="work_progress?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Avancement travaux</a></td>
                                <td><a href="details_estimate?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Details</a></td>
                            </tr>
                            @endif
                            @endforeach
                            @endif
                        </tbody>
                      </table>
                    </div>
            </div>
        </div>
    </div>
</div>