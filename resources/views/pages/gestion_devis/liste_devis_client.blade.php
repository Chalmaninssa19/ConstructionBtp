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
                <h4 class="card-title text-primary">MES DEVIS</h4>
                <div class="mt-4 d-flex align-items-center justify-content-between">
                    <div>
                        <a href="new_estimate" class="btn btn-gradient-primary">Nouveau devis</a>
                    </div>
                </div>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                            <tr>
                                <th> Type maison </th>
                                <th> Type finition </th>
                                <th> date debut </th>
                                <th> date fin </th>
                                <th> Lieu </th>
                                <th> Montant total </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($list))
                            @foreach($list as $item)
                            <tr>
                              <td>{{ $item->house_type_designation }}</td>
                              <td> {{ $item->finish_type_designation }} </td>
                              <td> {{ $item->start_date }} </td>
                              <td> {{ $item->getDateEnd() }} </td>
                              <td> {{ $item->lieu }} </td>
                              <td> {{ $item->getAmountTotal() }} </td>
                              <td><a href="export_estimate_pdf?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Export pdf</a></td>
                              <td><a href="payment_page?id_estimate={{ $item->id_estimate }}" class="text-primary action-icon">Payer</a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                      </table>
                    </div>
            </div>
        </div>
    </div>
</div>