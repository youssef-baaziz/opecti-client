<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="createCommentModal" tabindex="-1" aria-labelledby="createCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="createCommentModalLabel">Créer un nouveau commentaire</h5>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="client_id" class="form-label">Client</label>
                <select class="form-control" id="client_id" name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="alert_id" class="form-label">Alerte</label>
                <select class="form-control" id="alert_id" name="alert_id" required>
                    @foreach($alerts as $alert)
                        <option value="{{ $alert->id }}">{{ $alert->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea class="form-control" id="content" name="content" placeholder="Entrez le contenu" required></textarea>
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