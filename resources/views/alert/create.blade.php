<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="createAlertModal" tabindex="-1" aria-labelledby="createAlertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="createAlertModalLabel">Créer une nouvelle alerte</h5>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('alerts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Entrez le titre" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Entrez la description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="ouvert">Ouvert</option>
                    <option value="fermé">Fermé</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="severity" class="form-label">Sévérité</label>
                <select class="form-control" id="severity" name="severity" required>
                    <option value="faible">Faible</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="élevée">Élevée</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="client_id" class="form-label">Client</label>
                <select class="form-control" id="client_id" name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                  <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Annuler</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Selection -->