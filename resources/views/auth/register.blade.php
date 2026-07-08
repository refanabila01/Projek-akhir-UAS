<!DOCTYPE html>
<html>
<head>

    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-md-5">

<div class="card shadow">

<div class="card-header text-center">

<h3>Register</h3>

</div>

<div class="card-body">

@if ($errors->any())

<div class="alert alert-danger">

<ul>

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form action="{{ route('register.store') }}" method="POST">

@csrf

<div class="mb-3">

<label>Nama</label>

<input type="text" name="name" class="form-control" required>

</div>

<div class="mb-3">

<label>Email</label>

<input type="email" name="email" class="form-control" required>

</div>

<div class="mb-3">

<label>Password</label>

<input type="password" name="password" class="form-control" required>

</div>

<div class="mb-3">

<label>Konfirmasi Password</label>

<input type="password" name="password_confirmation" class="form-control" required>

</div>

<button class="btn btn-primary w-100">

Daftar

</button>

</form>

<div class="text-center mt-3">

Sudah punya akun?

<a href="/login">Login</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>