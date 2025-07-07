@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Rapports pour Analyste</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createRapportModal">
            <i class="bi bi-plus-circle"></i> Ajouter un Rapport
        </button>
        <div class="d-flex">
            <input type="text" class="form-control me-2" id="searchRapport" name="query" value="{{ request()->query('query') }}" placeholder="Rechercher un rapport">
            <button type="button" class="btn btn-outline-primary" onclick="liveSearch()"><i class="bi bi-search"></i></button>
        </div>
    </div>
    @include('analyste.rapport.create')
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
                    <th scope="col">Titre</th>
                    <th scope="col">Type</th>
                    <th scope="col">Utilisateur</th>
                    <th scope="col">Nom du Fichier</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="rapport-table-body">
                @forelse($rapports as $rapport)
                <tr>
                    <td>{{ $rapport->titre }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($rapport->type) }}</span></td>
                    <td>{{ $rapport->user_id }}</td>
                    <td>{{ $rapport->file }}</td>
                    <td class="text-right">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRapportModal{{ $rapport->id }}">
                            <i class="bi bi-pencil-square"></i> Modifier
                        </button>
                        <!-- Delete Confirmation Modal -->
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRapportModal{{ $rapport->id }}">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteRapportModal{{ $rapport->id }}" tabindex="-1" aria-labelledby="deleteRapportModalLabel{{ $rapport->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteRapportModalLabel{{ $rapport->id }}">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer ce rapport ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('rapports.destroy', $rapport->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('rapports.download') }}" method="GET" class="d-inline">
                            <input type="hidden" name="file" value="{{ $rapport->file }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @include('analyste.rapport.edit', ['rapport' => $rapport, 'users' => $users])
                @empty
                <tr id="no-rapports-row">
                    <td colspan="5" class="text-center">Aucun rapport trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4" id="pagination-links">
        {{ $rapports->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    let debounceTimer;
    const searchInput = document.getElementById('searchRapport');
    const rapportTableBody = document.getElementById('rapport-table-body');
    const paginationLinks = document.getElementById('pagination-links');

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(liveSearch, 300);
    });

    async function liveSearch() {
        const query = searchInput.value.trim();
        try {
            const response = await fetch(`{{ route('rapports.search') }}?query=${query}`);
            const rapports = await response.json();

            if (rapports.length > 0) {
                rapportTableBody.innerHTML = '';
                rapports.forEach(rapport => {
                    const rapportRow = document.createElement('tr');
                    rapportRow.innerHTML = `
                        <td>${rapport.titre}</td>
                        <td><span class="badge bg-secondary">${rapport.type.charAt(0).toUpperCase() + rapport.type.slice(1)}</span></td>
                        <td>${rapport.user_id}</td>
                        <td>${rapport.file}</td>
                        <td class="text-right">
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRapportModal${rapport.id}">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRapportModal${rapport.id}">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                            <div class="modal fade" id="deleteRapportModal${rapport.id}" tabindex="-1" aria-labelledby="deleteRapportModalLabel${rapport.id}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteRapportModalLabel${rapport.id}">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer ce rapport ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="/rapports/${rapport.id}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="/rapports/download" method="GET" class="d-inline">
                                <input type="hidden" name="file" value="${rapport.file}">
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download"></i>
                                </button>
                            </form>
                        </td>
                    `;
                    rapportTableBody.appendChild(rapportRow);
                });
                paginationLinks.style.display = 'none';
            } else {
                rapportTableBody.innerHTML = `
                    <tr id="no-rapports-row">
                        <td colspan="5" class="text-center">Aucun rapport trouvé.</td>
                    </tr>
                `;
                paginationLinks.style.display = query === '' ? 'flex' : 'none';
            }
        } catch (error) {
            console.error('Error fetching search results:', error.message);
            rapportTableBody.innerHTML = `
                <tr id="error-row">
                    <td colspan="5" class="text-center text-danger">Erreur lors du chargement des rapports: ${error.message}</td>
                </tr>
            `;
            if (query === '') {
                paginationLinks.style.display = 'flex';
            }
        }
    }
</script>
@endsection