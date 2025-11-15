<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Admin Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Admin Login</h1>
    
    @if (session('error'))
        <div style="color: red; padding: 10px; border: 1px solid red; margin: 10px 0;">
            Error: {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div style="color: green; padding: 10px; border: 1px solid green; margin: 10px 0;">
            Success: {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        
        <div style="margin: 10px 0;">
            <label>Email:</label><br>
            <input type="email" name="email" value="AdminJuan@gmail.com" style="width: 300px; padding: 5px;" required>
            @error('email')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin: 10px 0;">
            <label>Password:</label><br>
            <input type="password" name="password" value="johnson@suceess!" style="width: 300px; padding: 5px;" required>
            @error('password')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" style="padding: 10px 20px; background: blue; color: white; border: none;">
            Login
        </button>
    </form>

    <div style="margin-top: 20px;">
        <p><strong>Expected Credentials:</strong></p>
        <p>Email: AdminJuan@gmail.com</p>
        <p>Password: johnson@suceess!</p>
    </div>

    <div style="margin-top: 20px;">
        <a href="/admin/login">Go to Real Admin Login</a> | 
        <a href="/admin/dashboard">Go to Admin Dashboard</a>
    </div>
</body>
</html>