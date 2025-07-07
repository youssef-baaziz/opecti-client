@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Rapports pour Analyste</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success float-left" data-bs-toggle="modal" data-bs-target="#createRapportModal">
            <i class="fas fa-plus-circle"></i> Ajouter un Rapport
        </button>
        <form
            class="d-none d-sm-inline-block form-inline ml-auto mr-md-0 my-4 my-md-0 mw-100 navbar-search float-right"
            action="{{ route('rapports.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control bg-white border-0 small" placeholder="Rechercher un rapport"
                    aria-label="Search" aria-describedby="basic-addon2" name="search" id="searchRapport" value="{{ request()->query('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                    @if(request()->query('search'))
                    <a href="{{ route('rapports.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    @include('rapport.create')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
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
                            <i class="fas fa-pencil-alt"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteRapportModal{{ $rapport->id }}">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>

                        <!-- Delete Confirmation Modal -->
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
                                <i class="fas fa-download"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @include('rapport.edit', ['rapport' => $rapport])
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
@endsection