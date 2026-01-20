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
            margin-bottom: 0 !important;
        }

        .navbar-inner {
            width: 100%;
            max-width: 1480px;
            margin: 0 auto;
            padding: 0 40px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .nav-logo img {
            height: 34px;
        }

        .nav-logo span {
            font-size: 20px;
            font-weight: 500;
            color: #111;
            letter-spacing: -0.5px;
        }

        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 32px;
        }

        .nav-menu a {
            text-decoration: none;
            font-size: 16px;
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
        }

        .nav-action {
            display: flex;
            align-items: center;
        }

        .btn-login {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 22px;
            background: #ffcb22ff;
            border-radius: 999px;
            color: #ffffffff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 62, 92, 0.2);
        }

        .btn-login span {
            width: 28px;
            height: 28px;
            background: #ffffff;
            color: #2f3e5c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .navbar {
                height: 70px;
            }

            .nav-menu {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php $current_url = basename($_SERVER['PHP_SELF']) . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="beranda.html" class="nav-logo">
                <img src="<?php echo $config['logo']; ?>" alt="Logo">
                <span>Esoftplay Internship</span>
            </a>
            <div class="nav-menu">
                <?php foreach ($menus as $menu) { ?>
                    <a href="<?php echo $menu['link']; ?>" class="<?php echo ($menu['link'] == $current_url) ? 'active' : ''; ?>">
                        <?php echo $menu['title']; ?>
                    </a>
                <?php } ?>
            </div>
            <div class="nav-action">
                <?php if (!empty($user->id)) { ?>
                    <a href="index.php?mod=user.logout" class="btn-login">
                        Logout
                        <span>↗</span>
                    </a>
                <?php } else { ?>
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