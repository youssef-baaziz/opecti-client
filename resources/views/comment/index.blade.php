@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestion des Commentaires</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCommentModal">
            <i class="fas fa-plus-circle"></i> Ajouter un Commentaire
        </button>
        <form class="d-none d-sm-inline-block form-inline ml-auto mr-md-0 my-4 my-md-0 mw-100 navbar-search float-right" method="GET" action="{{ route('comments.index') }}">
            <div class="input-group">
                <input type="text" class="form-control bg-white border-0 small" placeholder="Rechercher un commentaire"
                    aria-label="Search" aria-describedby="basic-addon2" id="searchComment" name="query" value="{{ request()->query('query') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('query'))
                    <a href="{{ route('comments.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-times-circle"></i> Effacer
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
    @include('comment.create')
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
                    <th scope="col">Client</th>
                    <th scope="col">Alerte</th>
                    <th scope="col">Contenu</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="comment-table-body">
                @forelse($comments as $comment)
                <tr>
                    <td>{{ $comment->client->name }}</td>
                    <td>{{ $comment->alert->title }}</td>
                    <td>{{ $comment->content }}</td>
                    <td class="text-right">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCommentModal{{ $comment->id }}">
                            <i class="fas fa-pencil-alt"></i> Modifier
                        </button>
                        <!-- Delete Confirmation Modal -->
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCommentModal{{ $comment->id }}">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteCommentModal{{ $comment->id }}" tabindex="-1" aria-labelledby="deleteCommentModalLabel{{ $comment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteCommentModalLabel{{ $comment->id }}">Confirmer la suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer ce commentaire ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
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
                @include('comment.edit', ['comment' => $comment, 'clients' => $clients, 'alerts' => $alerts])
                @empty
                <tr id="no-comments-row">
                    <td colspan="4" class="text-center">Aucun commentaire trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4" id="pagination-links">
        {{ $comments->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection