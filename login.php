<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Bus Monitoring System</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- PAGE STYLE (NO FORM LOGIC CHANGED) -->
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            /* Background image */
            background:
                linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                url("assets/img/new_bus.png") center / cover no-repeat fixed;

            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Wrapper */
        .login-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 20px;
        }
        /* Card */
        .login-card {
            background: rgba(36, 236, 136, 0.96);
            border-radius: 22px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
            animation: fadeUp 0.8s ease;
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Logo */
        .logo-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd, #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #12f19c;
            font-size: 32px;
            margin: 0 auto;
        }
        /* Inputs */
        .form-control {
            border-radius: 14px;
            padding: 12px;
        }
        /* Button */
        .btn-login {
            background: linear-gradient(135deg, #20c997, #8fd14f);
            border: none;
            border-radius: 30px;
            padding: 12px;
            color: #fff;
            font-weight: 600;
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        }
        /* Links */
        a {
            color: #0d6efd;
        }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card shadow-lg">
        <div class="text-center mb-4">
            <div class="logo-circle mb-2">
                🚌
            </div>
            <h4 class="fw-bold">Welcome Back!</h4>
            <p class="text-muted">Login to continue your journey</p>
        </div>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
                Invalid email or password
            </div>
        <?php endif; ?>
        <!-- 🔒 FORM UNCHANGED -->
        <form method="POST" action="actions/login_action.php">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <button class="btn btn-login w-100 mt-2">
                Login
            </button>
        </form>
        <p class="text-center mt-3">
            Don’t have an account?
            <a href="register.php" class="text-decoration-none fw-bold">Register</a>
        </p>
    </div>
</div>
</body>
</html>
