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

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Tableau de bord
    </h3>
</div>
<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Montant total des devis
                </h4>
                <h2 class="mb-5">{{ $amountTotalEstimate }} Ar</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">Montant total payment effectue</h4>
                <h2 class="mb-5">{{ $amountTotalPayment }} Ar</h2>
            </div>
        </div>
    </div>
</div>
    
<div class="row">
    <div class="col-lg-10 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form action="filter_by_month" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="form-group col-md-5">
                            <label for="article">Choisir l'annee pour avoir l'histogramme des devis par mois de l'annee</label>
                            @if(isset($year))
                                <select name="year" class="form-control form-control-sm input-height mt-2" id="itemInput">
                                    @foreach($years as $item)
                                        @if($item==$year)
                                        <option selected value="{{ $item }}">{{ $item }}</option>
                                        @else
                                        <option value="{{ $item }}">{{ $item }}</option>
                                        @endif

                                    @endforeach
                                </select>
                            @else
                                <select name="year" class="form-control form-control-sm input-height mt-2" id="itemInput">
                                    @foreach($years as $item)
                                        @if($item==2024)
                                        <option selected value="{{ $item }}">{{ $item }}</option>
                                        @else
                                        <option value="{{ $item }}">{{ $item }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""></label>
                            <input type="submit" class="btn btn-gradient-primary" value="Valider">
                        </div>
                    </div>
                </form>

                <form action="filter_by_year" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row align-items-end">
                        <div class="form-group col-md-3">
                            <label for=""></label>
                            <input type="hidden" name="help" value="1"/>
                            <input type="submit" class="btn btn-gradient-primary" value="Filtrer par an">
                        </div>
                    </div>
                </form>
                <h4 class="card-title histogramme">Histogramme</h4>
                <canvas id="barChart" style="height:230px"></canvas>
            </div>
        </div>
    </div>
</div>