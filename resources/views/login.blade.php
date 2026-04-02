<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	@vite('resources/css/app.css')
</head>
<body class="login-page">
	<div class="login-card">
		<h1 class="login-title">Login</h1>

		<form method="POST" action="{{ url('/login') }}">
			@csrf

			<div class="form-group">
				<label for="nik">NIK</label>
				<input type="text" id="nik" name="nik" placeholder="Masukkan NIK" required>
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Masukkan password" required>
			</div>

			<button type="submit" class="btn-login">Masuk</button>
		</form>
	</div>
</body>
</html>
