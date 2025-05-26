<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="editClientModalLabel{{ $client->id }}">Modifier un client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nom{{ $client->id }}" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom{{ $client->id }}" name="nom" value="{{ $client->nom }}" placeholder="Entrez le nom" required>
            </div>
            <div class="mb-3">
                <label for="secteur{{ $client->id }}" class="form-label">Secteur</label>
                <input type="text" class="form-control" id="secteur{{ $client->id }}" name="secteur" value="{{ $client->secteur }}" placeholder="Entrez le secteur" required>
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