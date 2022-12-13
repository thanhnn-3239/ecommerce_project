@extends('admin.layouts.app')
@section('title', 'List User')
@section('content')
    <div class="card">
        @if (session('message'))
            <h1 class="text-primary">{{ session('message') }}</h1>
        @endif

        <h1>
            Users list
        </h1>
        <div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create</a>

        </div>
        <div>
            <table class="table table-hover">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>

                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><img src="{{ $user->images->count() > 0 ? asset('upload/users/' . $user->images->first()->url) : 'upload/users/default.png' }}"
                            width="200px" height="200px" alt=""></td>
                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->address }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>

                            <form action="{{ route('users.destroy', $user->id) }}" id="form-delete{{ $user->id }}"
                                method="post">
                                @csrf
                                @method('delete')

                            </form>
                            <button class="btn btn-delete btn-danger" data-id={{ $user->id }}>Delete</button>


                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $users->links() }}
        </div>
    </div>
@endsection