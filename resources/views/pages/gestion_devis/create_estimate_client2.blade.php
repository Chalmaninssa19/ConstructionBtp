<div class="row">
    <div class="col-8 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary" style="text-align: center">CREATION D'UN NOUVEAU DEVIS</h4>
                <div class="mt-4">
                    <form action="save_estimate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="article">Type finition</label>
                            <select name="id_finish_type" class="form-control form-control-sm input-height mt-2" id="articleInput">
                                @if(isset($listFinishType))
                                @foreach($listFinishType as $item)
                                <option selected value="{{ $item->id_finish_type }}">{{ $item->finish_type_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Lieu</label>
                            <input type="text" name="lieu" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date debut construction</label>
                            <input type="date" name="date_start" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date du devis</label>
                            <input type="date" name="date_estimate" class="form-control">
                        </div>
                        @if(isset($error))
                            <div class="mt-3">
                                <p class="text-danger text-small">{{ $error; }}</p>
                            </div>
                        @endif
                        <div class="mt-3">
                            <button type="submit"
                                class="btn btn-gradient-primary px-5 me-2">Creer</button>
                            <a  href="estimate_list" class="btn btn-light">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>