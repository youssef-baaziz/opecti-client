<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="editUserModal{{ $superadmin->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $superadmin->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="editUserModalLabel{{ $superadmin->id }}">Modifier un utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('users.update', $superadmin->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name{{ $superadmin->id }}" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name{{ $superadmin->id }}" name="name" value="{{ $superadmin->name }}" placeholder="Entrez le nom" required>
            </div>
            <div class="mb-3">
                <label for="email{{ $superadmin->id }}" class="form-label">Email</label>
                <input type="email" class="form-control" id="email{{ $superadmin->id }}" name="email" value="{{ $superadmin->email }}" placeholder="Entrez l'email" required>
            </div>
            <div class="mb-3">
                <label for="role{{ $superadmin->id }}" class="form-label">Rôle</label>
                <select class="form-control" id="role{{ $superadmin->id }}" name="role" required>
                    <option value="client" {{ $superadmin->role == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="admin" {{ $superadmin->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="analyste" {{ $superadmin->role == 'analyste' ? 'selected' : '' }}>Analyste</option>
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