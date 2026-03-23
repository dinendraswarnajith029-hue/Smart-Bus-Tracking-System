<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ===============================
           PAGE BACKGROUND
        =============================== */
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", sans-serif;

            background:
                linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                url("assets/img/new_bus.png") center / cover no-repeat fixed;
        }
        /* ===============================
           WRAPPER
        =============================== */
        .auth-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        /* ===============================
           CARD
        =============================== */
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: rgba(145, 214, 205, 0.96);
            padding: 42px;
            border-radius: 26px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.45);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }
        .auth-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 40px 100px rgba(0,0,0,0.55);
        }
        /* ===============================
           ICON
        =============================== */
        .auth-icon {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38ef7d, #11998e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: auto;
            color: #fff;
            box-shadow: 0 12px 35px rgba(0,0,0,0.35);
        }
        /* ===============================
           INPUTS
        =============================== */
        .form-control {
            border-radius: 16px;
            padding: 14px;
            border: 1px solid #dde5ec;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 3px rgba(17,153,142,0.25);
        }

        /* ===============================
           BUTTON
        =============================== */
        .btn-auth {
            background: linear-gradient(135deg, #38ef7d, #11998e);
            color: #6e756f;
            border-radius: 30px;
            padding: 14px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(17,153,142,0.5);
            opacity: 0.95;
        }

        /* ===============================
           LINKS
        =============================== */
        .auth-card a {
            text-decoration: none;
            color: #11998e;
            font-weight: 600;
        }

        .auth-card a:hover {
            text-decoration: underline;
        }

        /* ===============================
           MOBILE
        =============================== */
        @media (max-width: 576px) {
            .auth-card {
                padding: 32px 26px;
            }
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card shadow-lg">
        <!-- Header -->
        <div class="text-center mb-4">
            <div class="auth-icon mb-3">🧑‍💼</div>
            <h3 class="fw-bold">Create Account</h3>
            <p class="text-muted">Join us and start your journey</p>
        </div>
        <!-- Form (UNCHANGED) -->
        <form method="POST" action="actions/register_action.php">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control"
                       placeholder="Upali Saman" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       placeholder="example@email.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-auth w-100">
                <i class="bi bi-person-plus"></i> Register
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="text-muted">Already have an account?</span>
            <a href="login.php">Login</a>
        </div>

    </div>
</div>

</body>
</html>
