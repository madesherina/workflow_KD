<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | NexPublish</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="auth-body">
    <div class="login-container" style="padding: 2.5rem 3rem;">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem; letter-spacing: -0.025em;">NEX<span style="color: #22c55e;">PUBLISH</span></h1>
            <p style="color: #64748b; font-size: 0.9rem; font-weight: 500;">Workflow Management System</p>
        </div>

        <h2 style="font-size: 1.75rem; margin-bottom: 0.5rem; text-align: center; color: #1e293b;">Welcome Back</h2>
        <p style="text-align: center; color: #64748b; margin-bottom: 2rem; font-size: 0.95rem;">Please enter your details to sign in</p>

        @if($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fee2e2; padding: 0.85rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem; color: #b91c1c; display: flex; align-items: center; gap: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="email" style="font-weight: 600; font-size: 0.85rem; color: #334155; margin-bottom: 0.5rem; display: block;">Email Address</label>
                <input type="email" id="email" name="email" placeholder="name@company.com" required value="{{ old('email') }}" style="width: 100%; padding: 0.85rem 1.1rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.95rem; transition: all 0.2s;">
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="password" style="font-weight: 600; font-size: 0.85rem; color: #334155; margin-bottom: 0.5rem; display: block;">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required style="width: 100%; padding: 0.85rem 1.1rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.95rem; transition: all 0.2s;">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; font-size: 0.85rem; color: #64748b;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none;">
                    <input type="checkbox" name="remember" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #cbd5e1; cursor: pointer;"> 
                    <span>Remember me</span>
                </label>
                <a href="#" style="color: #22c55e; text-decoration: none; font-weight: 700;">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login" style="background: linear-gradient(to right, #22c55e, #16a34a); padding: 1rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.3);">Sign In</button>
        </form>

        <div style="margin-top: 2rem; text-align: center; color: #64748b; font-size: 0.85rem; font-weight: 500;">
            Don't have an account? <a href="#" style="color: #22c55e; font-weight: 700; text-decoration: none;">Contact Admin</a>
        </div>
    </div>
</body>
</html>
