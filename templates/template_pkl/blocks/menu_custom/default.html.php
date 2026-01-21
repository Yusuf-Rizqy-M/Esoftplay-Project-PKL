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
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            color: #555;
            transition: all 0.3s ease;
            padding: 10px 0;
            position: relative;
        }

        .nav-menu a:hover {
            color: #000;
        }

        /* ========== GARIS BAWAH TEPAT DI BAWAH TEKS ========== */
        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: transparent;
            transition: all 0.3s ease;
        }

        .nav-menu a.active::after {
            background: #ffcb22ff;
        }

        .nav-menu a.active {
            color: #000;
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
            color: #ffffff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 203, 34, 0.4);
        }

        .btn-login span {
            width: 28px;
            height: 28px;
            background: #ffffff;
            color: #ffcb22ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
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
    <?php 
    // ========== DETEKSI MENU AKTIF - BERDASARKAN menu_id ==========
    // Ambil menu_id dari URL
    $current_menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;
    
    // Jika tidak ada menu_id, coba deteksi dari mod parameter
    $current_mod = isset($_GET['mod']) ? $_GET['mod'] : '';
    ?>
    
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="home83.html" class="nav-logo">
                <img src="<?php echo $config['logo']; ?>" alt="Logo">
                <span>Esoftplay Internship</span>
            </a>
            <div class="nav-menu">
                <?php foreach ($menus as $menu) { 
                    $active_class = '';
                    
                    // Method 1: Deteksi berdasarkan menu_id di URL
                    // Ekstrak menu_id dari link menu (contoh: home83.html → 83)
                    preg_match('/(\d+)\.html/', $menu['link'], $matches);
                    $menu_id = isset($matches[1]) ? intval($matches[1]) : 0;
                    
                    if ($menu_id > 0 && $menu_id == $current_menu_id) {
                        $active_class = 'active';
                    }
                    
                    // Method 2: Deteksi berdasarkan mod parameter (fallback)
                    // Contoh: client-esoftplay.html → client_esoftplay
                    if (empty($active_class) && !empty($current_mod)) {
                        $menu_name = pathinfo($menu['link'], PATHINFO_FILENAME); // home83, client-esoftplay, dll
                        $menu_name_clean = preg_replace('/\d+/', '', $menu_name); // hilangkan angka: home, client-esoftplay
                        $menu_name_clean = str_replace('-', '_', $menu_name_clean); // ganti - jadi _
                        
                        // Cek apakah mod mengandung nama menu
                        if (strpos($current_mod, $menu_name_clean) !== false) {
                            $active_class = 'active';
                        }
                    }
                    
                    // Method 3: Default untuk home jika tidak ada menu_id
                    if (empty($active_class) && $current_menu_id == 0) {
                        if (strpos($menu['link'], 'home') !== false) {
                            $active_class = 'active';
                        }
                    }
                ?>
                    <a href="<?php echo $menu['link']; ?>" class="<?php echo $active_class; ?>">
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