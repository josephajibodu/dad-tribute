@extends('admin.layout')

@section('title', 'Sign In')

@section('content')
        <form class="sign-in" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <h1>Admin</h1>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required autofocus>
            @error('password')
                <p class="error" role="alert">{{ $message }}</p>
            @enderror
            <button type="submit">Sign in</button>
        </form>
@endsection
