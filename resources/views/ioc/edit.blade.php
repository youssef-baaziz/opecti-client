<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="editIocModal{{ $ioc->id }}" tabindex="-1" aria-labelledby="editIocModalLabel{{ $ioc->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="editIocModalLabel{{ $ioc->id }}">Modifier l'IOC</h5>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('iocs.update', $ioc->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="type{{ $ioc->id }}" class="form-label">Type</label>
                <select class="form-control" id="type{{ $ioc->id }}" name="type" required>
                    <option value="ip" {{ $ioc->type == 'ip' ? 'selected' : '' }}>IP</option>
                    <option value="domain" {{ $ioc->type == 'domain' ? 'selected' : '' }}>Domaine</option>
                    <option value="url" {{ $ioc->type == 'url' ? 'selected' : '' }}>URL</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="value{{ $ioc->id }}" class="form-label">Valeur</label>
                <input type="text" class="form-control" id="value{{ $ioc->id }}" name="value" value="{{ $ioc->value }}" placeholder="Entrez la valeur" required>
            </div>
            <div class="mb-3">
                <label for="description{{ $ioc->id }}" class="form-label">Description</label>
                <textarea class="form-control" id="description{{ $ioc->id }}" name="description" placeholder="Entrez la description" required>{{ $ioc->description }}</textarea>
            </div>
            <div class="mb-3">
                <label for="first_seen{{ $ioc->id }}" class="form-label">Première Observation</label>
                <input type="date" class="form-control" id="first_seen{{ $ioc->id }}" name="first_seen" value="{{ $ioc->first_seen }}" required>
            </div>
            <div class="mb-3">
                <label for="last_seen{{ $ioc->id }}" class="form-label">Dernière Observation</label>
                <input type="date" class="form-control" id="last_seen{{ $ioc->id }}" name="last_seen" value="{{ $ioc->last_seen }}" required>
            </div>
            <div class="mb-3">
                <label for="client_id{{ $ioc->id }}" class="form-label">Client</label>
                <select class="form-control" id="client_id{{ $ioc->id }}" name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $ioc->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
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