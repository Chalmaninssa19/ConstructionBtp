<div class="row">
    <div class="col-12 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title text-primary" style="text-align: center">CHOISISSEZ LE TYPE DE MAISON QUI VOUS CONVIENT </h1>
                <div class="mt-4">
                    <form action="save_house_type" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group row">
                                @foreach($list as $item)
                                <div class="col-md-4 grid-margin stretch-card typeHouseDiv">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $item->designation }}
                                                <input type="radio" class="form-check-input" name="house_type" id="membershipRadios2" value="{{ $item->id_house_type }}">
                                            </h4>
                                            <p class="card-description">Avec une surface de {{ $item->surface }} m2, la maison est construit en {{ $item->duration }} jours</p>
                                            <h2 class="text-success">{{ $item->getAmountNumber() }}</h2>
                                            <br>
                                            <ul class="list-ticked">
                                                @foreach($item->getListDescription() as $descriptionItem)
                                                <li>{{ $descriptionItem }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        @if(isset($error))
                            <div class="mt-3">
                                <p class="text-danger text-small">{{ $error; }}</p>
                            </div>
                        @endif
                        <div class="mt-3">
                            <button type="submit"
                                class="btn btn-gradient-primary px-5 me-2">Suivant</button>
                            <a  href="estimate_list" class="btn btn-light">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>