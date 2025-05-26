<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="createClientModalLabel">Créer un nouveau client</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom" required>
            </div>
            <div class="mb-3">
                <label for="secteur" class="form-label">Secteur</label>
                <input type="secteur" class="form-control" id="secteur" name="secteur" placeholder="Entrez le secteur" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">Enregistrer</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Selection -->