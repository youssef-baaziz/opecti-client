@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Utilisateurs</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success float-left" data-toggle="modal" data-target="#createUserModal">
            <i class="fas fa-plus-circle"></i> Ajouter un utilisateur
        </button>
        <form
            class="d-none d-sm-inline-block form-inline ml-auto mr-md-0 my-4 my-md-0 mw-100 navbar-search float-right"
            action="{{ route('users.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control bg-white border-0 small" placeholder="Search for..."
                    aria-label="Search" aria-describedby="basic-addon2" name="search" id="searchUser" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                    @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times"></i> Clear
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    @include('superadmin.create')
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
                        <button class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->id }}">
                            <i class="fas fa-pencil-alt"></i> Modifier
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $user->id }}">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Confirmer la suppression</h5>
                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">
                                            <i class="fas fa-times"></i>
                                        </button>
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
@endsection