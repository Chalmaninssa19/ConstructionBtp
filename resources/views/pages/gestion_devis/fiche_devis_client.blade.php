<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-primary" style="text-align : center">FICHE DEVIS CLIENT</h2>
              
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <address>
                          <p><span>Ref : </span><span class="font-weight-bold"> {{ $estimate->ref_estimate }} </span></p>
                          <p><span>Client : </span><span class="font-weight-bold"> {{ $estimate->client_phone_number }} </span></p>
                          <p><span>Date debut construction : </span><span class="font-weight-bold"> {{ $estimate->start_date }} </span></p>
                          <p><span>Date fin construction : </span><span class="font-weight-bold"> {{ $estimate->getDateEnd() }} </span></p>
                          <p><span>Montant total : </span><span class="font-weight-bold"> {{ $estimate->getAmountTotalWithoutFormatter() }} Ar</span></p>
                        </address>
                    </div>
                    <div class="col-md-4">
                        <address>
                          <p><span>Type maison : </span><span class="font-weight-bold"> {{ $estimate->house_type_designation }} </span></p>
                          <p><span>Type finition : </span><span class="font-weight-bold"> {{ $estimate->finish_type_designation }} </span></p>
                          <p><span>Taux finition : </span><span class="font-weight-bold"> {{ $estimate->percent_increase }}%</span></p>
                        </address>
                    </div>
                </div>
                <h4 class="card-text">DETAILS DEVIS</h4>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th> Num </th>
                            <th> Type travaux </th>
                            <th> Designation </th>
                            <th> Unite </th>
                            <th> Quantite </th>
                            <th> Prix unitaire</th>
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
                            <td> {{ $item->quantity }} </td>
                            <td> {{ $item->unit_price }} </td>
                            <td> {{ $item->amount }} </td>
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
                                <td> {{ $estimate->sum_amount_work }} </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
            </div>
            <h4 class="card-text">LISTE PAIEMENT EFFECTUE</h4>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th> Ref </th>
                            <th> Date paiement</th>
                            <th> Montant paye </th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(isset($listPayment))
                        @foreach($listPayment as $item)
                          <tr>
                            <td> {{ $item->ref_payment }} </td>
                            <td> {{ $item->date_payment }} </td>
                            <td> {{ $item->amount }} </td>
                          </tr>
                        @endforeach
                        @endif
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong>TOTAL MONTANT PAYE</strong></td>
                                <td> {{ $payment->amount_payed }} </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
            </div>
        </div>
    </div>
</div>