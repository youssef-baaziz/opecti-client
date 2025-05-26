<!-- Start of Selection -->
<!-- Modern Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header text-dark">
        <h5 class="modal-title" id="createUserModalLabel">Créer un nouvel utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Entrez le nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Entrez l'email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Entrez le mot de passe" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <script>
                    document.getElementById('togglePassword').addEventListener('click', function () {
                        const passwordField = document.getElementById('password');
                        const passwordFieldType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordField.setAttribute('type', passwordFieldType);
                        this.querySelector('i').classList.toggle('bi-eye');
                        this.querySelector('i').classList.toggle('bi-eye-slash');
                    });
                </script>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="client">Client</option>
                    <option value="admin">Admin</option>
                    <option value="analyste">Analyste</option>
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