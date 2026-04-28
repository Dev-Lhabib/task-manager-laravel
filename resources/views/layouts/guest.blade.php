<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TaskFlow') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f172a;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: -20%; left: -10%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,.25) 0%, transparent 70%);
            border-radius: 50%;
            animation: float1 8s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -20%; right: -10%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(168,85,247,.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: float2 10s ease-in-out infinite;
        }
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 20px); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-20px, -30px); }
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-brand-icon {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            box-shadow: 0 8px 24px rgba(99,102,241,.4);
            margin-bottom: .75rem;
        }
        .auth-brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.5px;
        }
        .auth-brand-sub {
            font-size: .8rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            animation: cardIn .5s ease;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px) scale(.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Form styles for auth pages */
        .auth-card label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }
        .auth-card input[type="text"],
        .auth-card input[type="email"],
        .auth-card input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: .9rem;
            font-family: inherit;
            color: #0f172a;
            background: #fff;
            transition: all .25s ease;
            margin-bottom: 1rem;
        }
        .auth-card input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .auth-card button[type="submit"],
        .auth-card .primary-button {
            width: 100%;
            padding: 11px 20px;
            border: none;
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 700;
            font-family: inherit;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            cursor: pointer;
            transition: all .25s ease;
            box-shadow: 0 4px 14px rgba(99,102,241,.35);
        }
        .auth-card button[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99,102,241,.45);
        }
        .auth-card a {
            color: #6366f1;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
        }
        .auth-card a:hover {
            text-decoration: underline;
        }

        /* Error messages */
        .auth-card .mt-2 {
            margin-top: -0.5rem;
            margin-bottom: 0.75rem;
            font-size: .78rem;
            color: #ef4444;
            font-weight: 500;
        }

        /* Checkbox styling */
        .auth-card input[type="checkbox"] {
            accent-color: #6366f1;
            margin-right: 6px;
        }

        /* Flex layouts in auth forms */
        .auth-card .flex { display: flex; }
        .auth-card .items-center { align-items: center; }
        .auth-card .justify-end { justify-content: flex-end; }
        .auth-card .justify-between { justify-content: space-between; }
        .auth-card .block { display: block; }
        .auth-card .mt-4 { margin-top: 1rem; }
        .auth-card .mt-1 { margin-top: .25rem; }
        .auth-card .ms-2 { margin-left: .5rem; }
        .auth-card .text-sm { font-size: .85rem; }
        .auth-card .text-gray-600 { color: #64748b; }
        .auth-card .underline { text-decoration: underline; }
        .auth-card .ms-4 { margin-left: 1rem; }
        .auth-card .inline-flex { display: inline-flex; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-container">
        <div class="auth-brand">
            <div class="auth-brand-icon">✦</div>
            <div class="auth-brand-title">TaskFlow</div>
            <div class="auth-brand-sub">Gérez vos tâches efficacement</div>
        </div>

        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
