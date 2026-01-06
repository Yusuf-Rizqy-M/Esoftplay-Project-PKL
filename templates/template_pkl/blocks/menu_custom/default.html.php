<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Esoftplay Project</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* 1. Menaikkan tinggi Navbar dari 60px ke 85px */
        .navbar {
            width: 100%;
            height: 85px;
            background: #fff;
            border-bottom: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            width: 100%;
            max-width: 1480px;
            margin: 0 auto;
            padding: 0 40px;
            /* Padding samping diperlebar */
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
        }

        /* 2. Memperbesar area Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-logo img {
            height: 34px;
            /* Naik dari 26px */
        }

        .nav-logo span {
            font-size: 20px;
            /* Naik dari 15px */
            font-weight: 500;
            color: #111;
            letter-spacing: -0.5px;
        }

        /* 3. Memperbesar Menu Navigasi */
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 32px;
            /* Jarak antar menu diperlebar */
        }

        .nav-menu a {
            text-decoration: none;
            font-size: 16px;
            /* Naik dari 14px */
            font-weight: 500;
            color: #555;
            transition: all 0.2s ease;
            padding: 8px 0;
        }

        .nav-menu a:hover {
            color: #000;
        }

        .nav-menu a.active {
            color: #000;
            font-weight: 600;
            border-bottom: 3px solid #000;
            /* Garis bawah lebih tebal */
        }

        /* 4. Memperbesar Tombol Login agar lebih "Bold" */
        .nav-action {
            display: flex;
            align-items: center;
        }

        .btn-login {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 22px;
            /* Padding diperbesar */
            background: #2f3e5c;
            border-radius: 999px;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            /* Naik dari 13px */
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 62, 92, 0.2);
        }

        .btn-login span {
            width: 28px;
            /* Naik dari 24px */
            height: 28px;
            background: #ffffff;
            color: #2f3e5c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 768px) {
            .navbar {
                height: 70px;
            }

            .nav-menu {
                display: none;
                /* Biasanya menu disembunyikan dalam hamburger pada mobile */
            }
        }
    </style>
</head>

<body>
    <?php $current_url = basename($_SERVER['PHP_SELF']) . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="nav-logo">
                <img src="<?php echo $config['logo']; ?>" alt="Logo">
                <span>Esoftplay Project</span>
            </div>
            <div class="nav-menu">
                <?php foreach ($menus as $menu) { ?>
                    <a href="<?php echo $menu['link']; ?>" class="<?php echo ($menu['link'] == $current_url) ? 'active' : ''; ?>">
                        <?php echo $menu['title']; ?>
                    </a>
                <?php } ?>
            </div>
            <div class="nav-action">
                <?php // pr($user) ?> 
                <?php if (!empty($user->id)) { ?><a href="index.php?mod=user.logout" class="btn-login">
                        Logout
                        <span>↗</span>
                    </a><?php } else { ?>


                    <a href="index.php?mod=user.login" class="btn-login">
                        Login
                        <span>↗</span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>
</body>

</html>