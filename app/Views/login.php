<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinarumi | Secure Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.07);
            --glass-border: rgba(255, 255, 255, 0.125);
            --accent-color: #3b82f6;
            --accent-glow: rgba(59, 130, 246, 0.5);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
              background:
            linear-gradient(rgba(15,23,42,.8), rgba(15,23,42,.8)),
            url("<?= base_url('logo.png') ?>") 
                right -150px bottom -150px / 600px auto no-repeat,
            radial-gradient(circle at top left, #0f172a, #1e293b);
            overflow: hidden;
            color: #ffffff;
        }

        body::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--accent-color);
            filter: blur(150px);
            border-radius: 50%;
            z-index: -1;
            top: 10%;
            right: 10%;
            opacity: 0.2;
        }

        /* === WRAPPER === */
        .login-wrapper{
            display:flex;
            align-items:center;
            gap:0px;
            z-index:1;
        }

        /* === COVER IMAGE === */
        .cover-image{
            width:420px;
            height:540px;
            border-top-left-radius: 24px;
            border-bottom-left-radius: 24px;
            background:
                linear-gradient(rgba(15,23,42,.7), rgba(15,23,42,.1)),
                url("<?= base_url('logoghibli.jpeg') ?>") center / cover no-repeat;
            box-shadow:0 25px 50px -12px rgba(0,0,0,.9);
        }

        /* === LOGIN CARD === */
        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-top-right-radius: 24px;
            border-bottom-right-radius: 24px;
            padding: 40px;
            width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        

        .brand-logo {
            letter-spacing: 2px;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .system-subtitle {
            font-size: 0.85rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 12px 16px;
            border-radius: 12px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px var(--accent-glow);
            color: #fff;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: #94a3b8;
            border-radius: 12px;
        }

        .btn-login {
            background: var(--accent-color);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .footer-link:hover {
            color: #fff;
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        /* === MOBILE === */
        @media (max-width: 992px){
            body{
                height: 100vh;
                margin-top: 0px;
            }

            .login-wrapper{
                flex-direction:column;
            }
            .cover-image{
                display:none;
            }
               .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="cover-image"></div>

    <div class="login-card">
        <div class="text-center mb-5">
            <h2 class="brand-logo mb-1">SINARUMI</h2>
            <p class="system-subtitle">CBIS v4.0.0</p>
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
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="text-dark bg-white form-control" placeholder="Username" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small text-secondary">Security Key</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" name="password" class="text-dark bg-white form-control" placeholder="Password" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label small text-secondary" for="remember">Remember me</label>
                </div>
                <a href="javascript:void(0)" onclick="forgotPassword()" class="footer-link">Forgot?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100 mb-4">
                Authorize Access
            </button>
        </form>

        <div class="text-center">
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="footer-link"><i class="bi bi-qr-code-scan me-1"></i> QR Scan</a>
                <span class="text-muted">|</span>
                <a href="#" class="footer-link"><i class="bi bi-cpu me-1"></i> Scanner</a>
            </div>
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
