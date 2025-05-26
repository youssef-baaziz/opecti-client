@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Utilisateurs</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
        </button>
        <div class="d-flex">
            <input type="text" class="form-control me-2" id="searchUser" name="query" value="{{ request()->query('query') }}" placeholder="Rechercher un utilisateur">
            <button type="button" class="btn btn-outline-primary" onclick="liveSearch()"><i class="bi bi-search"></i></button>
        </div>
    </div>
    @include('superadmin.create')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Rôle</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td class="text-right">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                            <i class="bi bi-pencil-square"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @include('superadmin.edit',['superadmin' => $user])
                @empty
                <tr id="no-users-row">
                    <td colspan="4" class="text-center">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4" id="pagination-links">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    let debounceTimer;
    const searchInput = document.getElementById('searchUser');
    const userTableBody = document.getElementById('user-table-body');
    const paginationLinks = document.getElementById('pagination-links');

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(liveSearch, 300);
    });

    async function liveSearch() {
        const query = searchInput.value.trim();

        try {
            const response = await axios.get(`/api/users/search`, {
                params: { query: query }
            });

            const users = response.data;

            userTableBody.innerHTML = '';

            if (users.length > 0) {
                users.forEach(user => {
                    const userRow = document.createElement('tr');
                    userRow.innerHTML = `
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td class="text-right">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal${user.id}">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal${user.id}">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                            <div class="modal fade" id="deleteUserModal${user.id}" tabindex="-1" aria-labelledby="deleteUserModalLabel${user.id}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteUserModalLabel${user.id}">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cet utilisateur ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="/users/${user.id}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    `;
                    userTableBody.appendChild(userRow);
                });
                paginationLinks.style.display = 'none';

            } else {
                userTableBody.innerHTML = `
                    <tr id="no-users-row">
                        <td colspan="4" class="text-center">Aucun utilisateur trouvé.</td>
                    </tr>
                `;
                paginationLinks.style.display = query === '' ? 'flex' : 'none';
            }
        } catch (error) {
            console.error('Error fetching search results:', error.message);
            userTableBody.innerHTML = `
                <tr id="error-row">
                    <td colspan="4" class="text-center text-danger">Erreur lors du chargement des utilisateurs: ${error.message}</td>
                </tr>
            `;
            if (query === '') {
                paginationLinks.style.display = 'flex';
            }
        }
    }
</script>
@endsection