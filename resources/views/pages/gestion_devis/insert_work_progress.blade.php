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
    <div class="col-8 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-primary" style="text-align: center">INSERTION AVANCEMENT TRAVAIL</h4>
                <div class="mt-4">
                    <form action="save_work_progress" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date debut</label>
                            <input type="date" name="date_start" class="form-control" id="dateInput">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Date fin</label>
                            <input type="date" name="date_end" class="form-control" id="dateInput">
                        </div>
                        
                        <input type="hidden" name="id_estimate" value="{{ $id_estimate }}" id="id_estimate"/>
                        <div class="mt-3">
                            <p class="text-danger text-small error"></p>
                        </div>
                        <div class="mt-3">
                            <button type="submit"
                                class="btn btn-gradient-primary px-5 me-2">Valider</button>
                            <a  href="estimate_in_progress" class="btn btn-light">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>