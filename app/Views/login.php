<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinarumi | Secure Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            /* Logo Pastel Colors */
            --logo-blue: #71C9E6; 
            --logo-pink: #F49AC2; 
            
            /* Glow Effects */
            --logo-blue-glow: rgba(113, 201, 230, 0.4);
            --logo-pink-glow: rgba(244, 154, 194, 0.4);

            /* Light Glass Effects */
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 1);
            
            /* Text Colors */
            --text-main: #334155; 
            --text-light: #64748b; 
        }

        
        <?php if (env('OWNER', '') == "BrightElly"): ?>
           body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    /* Soft pastel gradient blending dengan logo di tengah dan ukuran besar */
    background: 
        linear-gradient(rgba(224, 242, 254, 0.85), rgba(252, 231, 243, 0.85)),
        url("<?= base_url('logobrightelly.png') ?>") 
        center center / 60% auto no-repeat,
        linear-gradient(135deg, #e0f2fe 0%, #fce7f3 100%);
    overflow: hidden;
    color: var(--text-main);
}
        <?php else: ?>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                margin: 0;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                /* Soft pastel gradient blending with a subtle white overlay for readability */
                background:
                    linear-gradient(rgba(224, 242, 254, 0.8), rgba(252, 231, 243, 0.8)),
                    url("<?= base_url('logo.png') ?>") 
                    right -150px bottom -150px / 600px auto no-repeat,
                    linear-gradient(135deg, #e0f2fe 0%, #fce7f3 100%);
                overflow: hidden;
                color: var(--text-main);
            }
        <?php endif ?>

        body::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            background: var(--logo-pink);
            filter: blur(120px);
            border-radius: 50%;
            z-index: -1;
            top: 5%;
            right: 15%;
            opacity: 0.3;
        }
        
        body::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--logo-blue);
            filter: blur(120px);
            border-radius: 50%;
            z-index: -1;
            bottom: 10%;
            left: 15%;
            opacity: 0.3;
        }

        /* === LOGIN CARD === */
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 24px; /* Sudut membulat di semua sisi */
            padding: 40px;
            width: 420px;
            box-shadow: 0 15px 35px rgba(113, 201, 230, 0.15); /* Shadow lebih seimbang ke tengah */
            z-index: 1;
        }
        
        .brand-logo {
            letter-spacing: 2px;
            font-weight: 800;
            background: linear-gradient(to right, var(--logo-blue), var(--logo-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .system-subtitle {
            font-size: 0.85rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: #ffffff;
            border-color: var(--logo-blue);
            box-shadow: 0 0 0 4px var(--logo-blue-glow);
            color: var(--text-main);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid var(--glass-border);
            color: var(--logo-pink);
            border-radius: 12px;
            font-size: 1.1rem;
        }

        .btn-login {
            background: var(--logo-blue);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 8px 20px var(--logo-blue-glow);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--logo-pink);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--logo-pink-glow);
            color: #fff;
        }

        .footer-link {
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .footer-link:hover {
            color: var(--logo-blue);
        }

        .form-check-input:checked {
            background-color: var(--logo-blue);
            border-color: var(--logo-blue);
        }

        .alert-custom {
            background: rgba(244, 154, 194, 0.15);
            border: 1px solid var(--logo-pink);
            color: #be185d;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* === MOBILE === */
        @media (max-width: 576px){
            body {
                padding: 20px;
            }

            .login-card {
                padding: 30px;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-5">
            <?php if (env('OWNER', '') == "BrightElly"): ?>
                <h2 class="brand-logo mb-1">BrightElly</h2>
                <p class="system-subtitle">BrightElly Management System</p>
            <?php else: ?>
                <h2 class="brand-logo mb-1">S I N A R U M I</h2>
                <p class="system-subtitle">My Little Island Management System</p>
            <?php endif ?>
        </div>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-custom py-2 mb-4 text-center">
                <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/loginauth') ?>">
            <div class="mb-3">
                <label class="form-label small text-secondary">Identification</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small text-secondary">Security Key</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" checked>
                    <label class="form-check-label small text-secondary fw-semibold" for="remember">Remember me</label>
                </div>
                <a href="javascript:void(0)" onclick="forgotPassword()" class="footer-link">Forgot?</a>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-4">
                Authorize Access
            </button>
        </form>

        <div class="text-center">
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="footer-link"><i class="bi bi-qr-code-scan me-1"></i> QR Scan</a>
                <span class="text-muted opacity-50">|</span>
                <a href="#" class="footer-link"><i class="bi bi-cpu me-1"></i> Scanner</a>
            </div>
        </div>
    </div>

<script>
function forgotPassword() {
    alert('Access Protocol: Please contact System Administrator');
}
</script>

</body>
</html>