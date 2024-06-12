<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-primary" style="text-align : center">FICHE DEVIS</h2>
              
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <address>
                          <p><span>Reference : </span><span class="font-weight-bold"> {{ $estimate->ref_estimate }} </span></p>
                          <p><span>Client : </span><span class="font-weight-bold"> {{ $estimate->client_phone_number }} </span></p>
                          <p><span>Type maison : </span><span class="font-weight-bold"> {{ $estimate->house_type_designation }} </span></p>
                          <p><span>Date debut construction : </span><span class="font-weight-bold"> {{ $estimate->start_date }} </span></p>
                          <p><span>Date fin construction : </span><span class="font-weight-bold"> {{ $estimate->getDateEnd() }} </span></p>
                        </address>
                    </div>
                    <div class="col-md-6">
                        <address>
                          <p><span>Type finition : </span><span class="font-weight-bold"> {{ $estimate->finish_type_designation }} </span></p>
                          <p><span>Taux finition : </span><span class="font-weight-bold"> {{ $estimate->percent_increase }}%</span></p>
                          <p><span>Montant total devis : </span><span class="font-weight-bold"> {{ $estimate->getAmountTotal() }} Ar</span></p>
                          <p><span>Montant total paye : </span><span class="font-weight-bold"> {{ $estimateProgress->getPaymentTotal() }} Ar</span></p>
                          <p><span>Avancement paiement : </span><span class="font-weight-bold"> {{ $estimateProgress->percent_payment }}%</span></p>
                        </address>
                    </div>
                </div>
                <hr>
                <h4 class="card-text">TRAVAUX A FAIRE</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th> Num </th>
                            <th> Type travaux </th>
                            <th> Designation </th>
                            <th> Unite </th>
                            <th> Prix unitaire</th>
                            <th> Quantite </th>
                            <th> Montant </th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(isset($listdetailEstimate))
                        @foreach($listdetailEstimate as $item)
                          <tr>
                            <td> {{ $item->code }} </td>
                            <td> {{ $item->work_type }} </td>
                            <td> {{ $item->designation }} </td>
                            <td> {{ $item->unit }} </td>
                            <td> {{ $item->getFormatNumber($item->unit_price) }} </td>
                            <td> {{ $item->getFormatNumber($item->quantity) }} </td>
                            <td> {{ $item->getFormatNumber($item->amount) }} </td>
                          </tr>
                        @endforeach
                        @endif
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>TOTAL MONTANT TRAVAUX</strong></td>
                                <td> {{ $estimate->getSumAmountWork() }} </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
            </div>
            <div class="mt-3">
                <a  href="estimate_in_progress" class="btn btn-light">Retour</a>
            </div>
        </div>
    </div>
</div>