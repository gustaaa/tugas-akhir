@extends('layouts.dashboard')

@section('content')

<section class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Data User</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('user.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" data-id="inputNameUser" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter User Name">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="username">Your Username</label>
                <input type="text" data-id="inputUsernameUser" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Enter Username">
                @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" data-id="inputEmailUser" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter User Email" value="{{ old('email') }}">
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" data-id="inputPasswordUser" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter User Password">
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="custom-select" type="role" name="role" id="role">
                    <option value="">Choose Role</option>
                    <option value="Administrator">Administrator</option>
                    <option value="Operator">Operator</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <input class="form-control" type="file" name="gambar" id="gambar" aria-describedby="gambar" placeholder=""></br>
            </div>
    </div>
    <div class="card-footer text-right">
        <button class="btn btn-primary" data-id="btnAddUser">Submit</button>
        <a class="btn btn-secondary" href="{{ route('user.index') }}">Cancel</a>
    </div>
    </form>


</section>
@endsection