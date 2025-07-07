<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="createIocModal" tabindex="-1" aria-labelledby="createIocModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="createIocModalLabel">Créer un nouvel IOC</h5>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('iocs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="ip">IP</option>
                    <option value="domain">Domaine</option>
                    <option value="url">URL</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Valeur</label>
                <input type="text" class="form-control" id="value" name="value" placeholder="Entrez la valeur" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" placeholder="Entrez la description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="first_seen" class="form-label">Première Observation</label>
                <input type="date" class="form-control" id="first_seen" name="first_seen" required>
            </div>
            <div class="mb-3">
                <label for="last_seen" class="form-label">Dernière Observation</label>
                <input type="date" class="form-control" id="last_seen" name="last_seen" required>
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