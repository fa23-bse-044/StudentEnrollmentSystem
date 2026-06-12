<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal | COMSATS University</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f1f5f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .auth-container { background: white; padding: 35px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; border-top: 5px solid #00a896; }
        .logo-header { text-align: center; margin-bottom: 20px; }
        .univ-logo { width: 90px; height: auto; }
        input, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; box-sizing: border-box; }
        .primary-btn { width: 100%; background: #00a896; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .primary-btn:hover { background: #008f7f; }
        .alert-error { background: #ef4444; color: white; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; font-weight: bold; }
        .alert-success { background: #22c55e; color: white; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="logo-header">
            <img src="https://www.comsats.edu.pk/images/logo.png" alt="COMSATS Logo" class="univ-logo">
            <h2 style="margin: 10px 0 5px 0; color: #1e293b;">COMSATS University</h2>
            <p style="margin: 0; color: #64748b; font-size: 14px;">Academic Portal Sign In</p>
        </div>

        @if(session('success')) <div class="alert-success">{{ session('success') }}</div> @endif
        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <select name="role" required>
                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Registered Student</option>
                <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>Faculty Member</option>
            </select>

            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="primary-btn">Sign In</button>
        </form>

        <p style="text-align: center; font-size: 14px; color: #64748b; margin-top: 20px;">
            Don't have an account? <a href="{{ route('register') }}" style="color: #00a896; font-weight: bold; text-decoration: none;">Sign Up</a>
        </p>
    </div>

</body>
</html>
