<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Supply Chain Risk</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-md-5">

<div class="card shadow">

<div class="card-header text-center">

<h3>🌍 Login</h3>

</div>

<div class="card-body">

@if(session('success'))

<div class="alert alert-success">

{{ session('success') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger">

{{ $errors->first() }}

</div>

@endif

<form action="{{ route('login.authenticate') }}" method="POST">

@csrf

<div class="mb-3">

<label>Email</label>

<input type="email" class="form-control" name="email" required>

</div>

<div class="mb-3">

<label>Password</label>

<input type="password" class="form-control" name="password" required>

</div>

<button class="btn btn-primary w-100">

Login

</button>

</form>

<div class="text-center mt-3">

Belum punya akun?

<a href="{{ route('register') }}">Register</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>
</html>