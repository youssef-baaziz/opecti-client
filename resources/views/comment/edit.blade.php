<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="editCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="editCommentModalLabel{{ $comment->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="editCommentModalLabel{{ $comment->id }}">Modifier le commentaire</h5>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('comments.update', $comment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="client_id{{ $comment->id }}" class="form-label">Client</label>
                <select class="form-control" id="client_id{{ $comment->id }}" name="client_id" required>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $comment->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="alert_id{{ $comment->id }}" class="form-label">Alerte</label>
                <select class="form-control" id="alert_id{{ $comment->id }}" name="alert_id" required>
                    @foreach($alerts as $alert)
                        <option value="{{ $alert->id }}" {{ $comment->alert_id == $alert->id ? 'selected' : '' }}>{{ $alert->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="content{{ $comment->id }}" class="form-label">Contenu</label>
                <textarea class="form-control" id="content{{ $comment->id }}" name="content" placeholder="Entrez le contenu" required>{{ $comment->content }}</textarea>
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