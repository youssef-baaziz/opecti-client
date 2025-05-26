@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Liste des Clients</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createClientModal">
            <i class="bi bi-plus-circle"></i> Ajouter un Client
        </button>
        <div class="d-flex">
            <input type="text" class="form-control w-100 d-inline" id="searchClient" name="query" value="{{ request()->query('query') }}" placeholder="Rechercher un client">
            <button type="button" class="btn btn-outline-primary" onclick="liveSearch()"><i class="bi bi-search"></i></button>
        </div>
    </div>

    @include('admin.clients.create')

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
                    <th scope="col">Secteur</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="client-table-body">
                @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->nom }}</td>
                        <td>{{ $client->secteur }}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal{{ $client->id }}">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteClientModal{{ $client->id }}">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>

                            <div class="modal fade" id="deleteClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="deleteClientModalLabel{{ $client->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteClientModalLabel{{ $client->id }}">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer ce client ({{ $client->nom }}) ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
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
                    @include('admin.clients.edit', ['client' => $client])
                @empty
                    <tr id="no-clients-row">
                        <td colspan="3" class="text-center">Aucun client trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4" id="pagination-links">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    let debounceTimer;
    const searchInput = document.getElementById('searchClient');
    const clientTableBody = document.getElementById('client-table-body');
    const paginationLinks = document.getElementById('pagination-links');

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(liveSearch, 300);
    });

    async function liveSearch() {
        const query = searchInput.value.trim();

        try {
            const response = await axios.get(`/api/clients/search`, {
                params: { query: query }
            });

            const clients = response.data;

            clientTableBody.innerHTML = '';

            if (clients.length > 0) {
                clients.forEach(client => {
                    const clientRow = document.createElement('tr');
                    clientRow.innerHTML = `
                        <td>${client.nom}</td>
                        <td>${client.secteur}</td>
                        <td class="text-center">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editClientModal${client.id}">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteClientModal${client.id}">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                            <div class="modal fade" id="deleteClientModal${client.id}" tabindex="-1" aria-labelledby="deleteClientModalLabel${client.id}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteClientModalLabel${client.id}">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer ce client (${client.nom}) ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="/clients/${client.id}" method="POST" class="d-inline">
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
                    clientTableBody.appendChild(clientRow);
                });
                paginationLinks.style.display = 'none';

            } else {
                clientTableBody.innerHTML = `
                    <tr id="no-clients-row">
                        <td colspan="3" class="text-center">Aucun client trouvé.</td>
                    </tr>
                `;
                paginationLinks.style.display = query === '' ? 'flex' : 'none';
            }
        } catch (error) {
            console.error('Error fetching search results:', error.message);
            clientTableBody.innerHTML = `
                <tr id="error-row">
                    <td colspan="3" class="text-center text-danger">Erreur lors du chargement des clients: ${error.message}</td>
                </tr>
            `;
            if (query === '') {
                paginationLinks.style.display = 'flex';
            }
        }
    }
</script>
@endsection