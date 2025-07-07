<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="createRapportModal" tabindex="-1" aria-labelledby="createRapportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="createRapportModalLabel">Créer un nouveau rapport</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('rapports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" placeholder="Entrez le titre" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="mensuel">Mensuel</option>
                    <option value="hebdomadaire">Hebdomadaire</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Fichier</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">Affecter à un utilisateur</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
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