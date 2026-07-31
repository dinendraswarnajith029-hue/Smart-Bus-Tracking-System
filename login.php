<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | WayFinder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
        }

        /* ── Left Panel ── */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e3a5f 0%, #0f4c75 50%, #1b6ca8 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            top: -100px; left: -100px;
            overflow: hidden;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            bottom: -80px; right: -80px;
        }

        .brand-icon {
            width: 90px; height: 90px;
            background: rgba(255,255,255,0.15);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            font-size: 42px;
            margin-bottom: 28px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .left-panel h1 {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-align: center;
        }

        .left-panel p {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            text-align: center;
            max-width: 280px;
            line-height: 1.6;
        }

        .feature-list {
            margin-top: 40px;
            list-style: none;
            width: 100%;
            max-width: 280px;
        }

        .feature-list li {
            color: rgba(255,255,255,0.8);
            font-size: 0.88rem;
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .feature-list li i {
            color: #38bdf8;
            font-size: 1rem;
        }

        /* ── Right Panel ── */
        .right-panel {
            width: 480px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 48px;
        }

        .form-box { width: 100%; }

        .form-box h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .form-box .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 20px;
        }

        .input-wrap i.field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.92rem;
            color: #0f172a;
            background: #f8fafc;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .input-wrap input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
            background: #fff;
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            padding: 0;
        }

        .toggle-pw:hover { color: #3b82f6; }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(59,130,246,0.35);
        }

        .divider {
            text-align: center;
            color: #94a3b8;
            font-size: 0.82rem;
            margin: 22px 0;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider::before { left: 0; }
        .divider::after  { right: 0; }

        .register-link {
            text-align: center;
            font-size: 0.88rem;
            color: #64748b;
        }

        .register-link a {
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover { text-decoration: underline; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.87rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 28px; }
        }
    </style>
</head>
<body>

<!-- Left Branding Panel -->
<div class="left-panel">
    <div class="brand-icon">🚌</div>
    <h1>WayFinder</h1>
    <p>Smart Bus Tracking System — real-time routes, live tracking & easy booking.</p>
    <ul class="feature-list">
        <li><i class="bi bi-geo-alt-fill"></i> Live Bus Tracking</li>
        <li><i class="bi bi-ticket-perforated"></i> Easy Seat Booking</li>
        <li><i class="bi bi-star-fill"></i> Rate Your Journey</li>
        <li><i class="bi bi-shield-check"></i> Secure & Reliable</li>
    </ul>
</div>

<!-- Right Form Panel -->
<div class="right-panel">
    <div class="form-box">
        <h2>Welcome back 👋</h2>
        <p class="subtitle">Sign in to your account to continue</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                Invalid email or password. Please try again.
            </div>
        <?php endif; ?>

        <form method="POST" action="actions/login_action.php">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input type="email" name="email" placeholder="you@example.com" required>
            </div>

            <label class="form-label">Password</label>
            <div class="input-wrap">
                <i class="bi bi-lock field-icon"></i>
                <input type="password" name="password" id="passwordInput" placeholder="Enter your password" required>
                <button type="button" class="toggle-pw" onclick="togglePassword()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <div class="divider">or</div>

        <p class="register-link">
            Don't have an account? <a href="register.php">Create one</a>
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>
