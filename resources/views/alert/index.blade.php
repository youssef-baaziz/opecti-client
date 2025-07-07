@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Alertes</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAlertModal">
            <i class="fas fa-plus-circle"></i> Ajouter une Alerte
        </button>
        <form class="d-none d-sm-inline-block form-inline ml-auto mr-md-0 my-4 my-md-0 mw-100 navbar-search float-right" method="GET" action="{{ route('alerts.index') }}">
            <div class="input-group">
                <input type="text" class="form-control bg-white border-0 small" placeholder="Rechercher une alerte"
                    aria-label="Search" aria-describedby="basic-addon2" id="searchAlert" name="query" value="{{ request()->query('query') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('query'))
                    <a href="{{ route('alerts.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times-circle"></i> Effacer
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    @include('alert.create')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>
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
                    <th scope="col">Description</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Sévérité</th>
                    <th scope="col">Client</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="alert-table-body">
                @forelse($alerts as $alert)
                <tr>
                    <td>{{ $alert->title }}</td>
                    <td>{{ $alert->description }}</td>
                    <td>{{ $alert->status }}</td>
                    <td>{{ $alert->severity }}</td>
                    <td>{{ $alert->client->name }}</td>
                    <td class="text-right">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAlertModal{{ $alert->id }}">
                            <i class="fas fa-pencil-alt"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAlertModal{{ $alert->id }}">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>

                        <div class="modal fade" id="deleteAlertModal{{ $alert->id }}" tabindex="-1" aria-labelledby="deleteAlertModalLabel{{ $alert->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAlertModalLabel{{ $alert->id }}">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cette alerte ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('alerts.destroy', $alert->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('alerts.download') }}" method="GET" class="d-inline">
                            <input type="hidden" name="file" value="{{ $alert->file }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @include('alert.edit', ['alert' => $alert, 'clients' => $clients])
                @empty
                <tr id="no-alerts-row">
                    <td colspan="6" class="text-center">Aucune alerte trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4" id="pagination-links">
        {{ $alerts->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection