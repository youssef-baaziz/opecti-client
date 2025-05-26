<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="editRapportModal{{ $rapport->id }}" tabindex="-1" aria-labelledby="editRapportModalLabel{{ $rapport->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="editRapportModalLabel{{ $rapport->id }}">Modifier le rapport</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('rapports.update', $rapport->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="titre{{ $rapport->id }}" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titre{{ $rapport->id }}" name="titre" value="{{ $rapport->titre }}" placeholder="Entrez le titre" required>
            </div>
            <div class="mb-3">
                <label for="type{{ $rapport->id }}" class="form-label">Type</label>
                <select class="form-control" id="type{{ $rapport->id }}" name="type" required>
                    <option value="mensuel" {{ $rapport->type == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                    <option value="hebdomadaire" {{ $rapport->type == 'hebdomadaire' ? 'selected' : '' }}>Hebdomadaire</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="file{{ $rapport->id }}" class="form-label">Fichier</label>
                <input type="file" class="form-control" id="file{{ $rapport->id }}" name="file">
            </div>
            <div class="mb-3">
                <label for="user_id{{ $rapport->id }}" class="form-label">Affecter à un utilisateur</label>
                <select class="form-control" id="user_id{{ $rapport->id }}" name="user_id" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $rapport->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
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