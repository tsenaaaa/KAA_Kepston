@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin: 100px auto;">
    <div style="background: white; padding: 40px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h1 style="font-size: 24px; font-weight: 600; margin-bottom: 30px; text-align: center;">Login Admin</h1>

        @if($errors->has('error'))
            <div class="alert alert-danger">{{ $errors->first('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <span class="text-danger" style="font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px; color: #6b7280; font-size: 14px;">
            Default: admin@kaa.com / password
        </p>
    </div>
</div>

<style>
.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
}
.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
.alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
}
.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
.btn {
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-primary {
    background: #dc2626;
    color: white;
}
.btn-primary:hover {
    background: #b91c1c;
}
</style>
@endsection
