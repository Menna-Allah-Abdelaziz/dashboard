@extends('notes.layout')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Laravel CRUD Example</h2>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-success btn-sm" href="{{ route('notes.create') }}"><i class="fa fa-plus"></i> Create New Note</a>
        </div>
<form method="GET" action="{{ route('notes.index') }}" class="row g-3 mt-4">
    <div class="col-md-10">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search notes...">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-secondary w-100">Search</button>
    </div>
</form>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">No</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($notes as $note)
                    <tr id="note-{{ $note->id }}">
                        <td>{{ $loop->iteration + $i }}</td>
                        <td>{{ $note->title }}</td>
                        <td>{{ $note->content }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('notes.show',$note->id) }}">
                                <i class="fa-solid fa-list"></i> Show
                            </a>

                            <a class="btn btn-primary btn-sm" href="{{ route('notes.edit',$note->id) }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <form class="d-inline hide-form" data-id="{{ $note->id }}">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-eye-slash"></i> Hide
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">There are no data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {!! $notes->links() !!}

    </div>
</div>

<script>
document.querySelectorAll('.hide-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // منع إرسال الفورم العادي
        const id = this.dataset.id;

        fetch(`/notes/${id}/hide`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                document.getElementById('note-' + id).remove();
            }
        });
    });
});
</script>

@endsection
