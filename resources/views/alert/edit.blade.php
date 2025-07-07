<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="editAlertModal{{ $alert->id }}" tabindex="-1" aria-labelledby="editAlertModalLabel{{ $alert->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="editAlertModalLabel{{ $alert->id }}">Modifier l'alerte</h5>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('alerts.update', $alert->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title{{ $alert->id }}" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title{{ $alert->id }}" name="title" value="{{ $alert->title }}" placeholder="Entrez le titre" required>
            </div>
            <div class="mb-3">
                <label for="description{{ $alert->id }}" class="form-label">Description</label>
                <textarea class="form-control" id="description{{ $alert->id }}" name="description" placeholder="Entrez la description" required>{{ $alert->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="status{{ $alert->id }}" class="form-label">Statut</label>
                <select class="form-control" id="status{{ $alert->id }}" name="status" required>
                    <option value="ouvert" {{ $alert->status == 'ouvert' ? 'selected' : '' }}>Ouvert</option>
                    <option value="fermé" {{ $alert->status == 'fermé' ? 'selected' : '' }}>Fermé</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="severity{{ $alert->id }}" class="form-label">Sévérité</label>
                <select class="form-control" id="severity{{ $alert->id }}" name="severity" required>
                    <option value="faible" {{ $alert->severity == 'faible' ? 'selected' : '' }}>Faible</option>
                    <option value="moyenne" {{ $alert->severity == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                    <option value="élevée" {{ $alert->severity == 'élevée' ? 'selected' : '' }}>Élevée</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="client_id{{ $alert->id }}" class="form-label">Client</label>
                <select class="form-control" id="client_id{{ $alert->id }}" name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $alert->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
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