<!-- Start of Selection -->
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Indicateurs de Compromission (IOC)</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success float-left" data-bs-toggle="modal" data-bs-target="#createIocModal">
            <i class="fas fa-plus-circle"></i> Ajouter un IOC
        </button>
        <form class="d-none d-sm-inline-block form-inline ml-auto mr-md-0 my-4 my-md-0 mw-100 navbar-search float-right" method="GET" action="{{ route('iocs.index') }}">
            <div class="input-group">
                <input type="text" class="form-control bg-white border-0 small" placeholder="Rechercher un IOC"
                    aria-label="Search" aria-describedby="basic-addon2" id="searchIoc" name="query" value="{{ request()->query('query') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @include('ioc.create')
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
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Type</th>
                    <th scope="col">Valeur</th>
                    <th scope="col">Description</th>
                    <th scope="col">Première Observation</th>
                    <th scope="col">Dernière Observation</th>
                    <th scope="col">Client</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="ioc-table-body">
                @forelse($iocs as $ioc)
                <tr>
                    <td>{{ ucfirst($ioc->type) }}</td>
                    <td>{{ $ioc->value }}</td>
                    <td>{{ $ioc->description }}</td>
                    <td>{{ $ioc->first_seen->format('d/m/Y') }}</td>
                    <td>{{ $ioc->last_seen->format('d/m/Y') }}</td>
                    <td>{{ $ioc->client->name }}</td>
                    <td class="text-right">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editIocModal{{ $ioc->id }}">
                            <i class="fas fa-pencil-alt"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteIocModal{{ $ioc->id }}">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>

                        <div class="modal fade" id="deleteIocModal{{ $ioc->id }}" tabindex="-1" aria-labelledby="deleteIocModalLabel{{ $ioc->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteIocModalLabel{{ $ioc->id }}">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer cet IOC ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        <form action="{{ route('iocs.destroy', $ioc->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('iocs.download') }}" method="GET" class="d-inline">
                            <input type="hidden" name="file" value="{{ $ioc->file }}">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @include('ioc.edit', ['ioc' => $ioc])
                @empty
                <tr id="no-iocs-row">
                    <td colspan="7" class="text-center">Aucun IOC trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4" id="pagination-links">
        {{ $iocs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
<!-- End of Selection -->