<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$current_menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;
$current_mod = isset($_GET['mod']) ? $_GET['mod'] : '';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --bg-rail: #000000;
        --bg-side: #111111;
        --accent: #facc15;
        --text-white: #ffffff;
        --text-dim: #9ca3af;
        --hover-bg: #222222;
        --active-bg: #262626;
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background-color: #f1f5f9;
    }

    .sidebar-container {
        display: flex;
        width: 300px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: var(--bg-side);
        z-index: 9999;
    }

    .rail {
        width: 65px;
        background: var(--bg-rail);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 0;
        border-right: 1px solid #1a1a1a;
    }

    .rail-logo {
        width: 40px;
        height: 40px;
        background: var(--accent);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        box-shadow: 0 0 15px rgba(250, 204, 21, 0.2);
    }

    .rail-logo i {
        color: #000;
        font-size: 20px;
    }

    .rail-btn {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dim);
        border-radius: 10px;
        margin-bottom: 12px;
        transition: 0.2s;
        cursor: pointer;
    }

    .rail-btn.active {
        background: var(--active-bg);
        color: var(--text-white);
        border: 1px solid #333;
    }

    .main-side {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px 15px;
    }

    .side-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 0 5px;
    }

    .side-header h2 {
        color: var(--text-white);
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .collapse-btn {
        width: 28px;
        height: 28px;
        background: #222;
        border: 1px solid #333;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dim);
        font-size: 10px;
    }

    .nav-list {
        flex: 1;
        overflow-y: auto;
    }

    .nav-list::-webkit-scrollbar {
        width: 0;
    }

    .nav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        color: var(--text-dim);
        text-decoration: none;
        font-size: 14px;
        font-weight: 400;
        border-radius: 10px;
        margin-bottom: 4px;
        transition: 0.2s;
    }

    .nav-item:hover {
        background: var(--hover-bg);
        color: var(--text-white);
    }

    .nav-item.active {
        background: var(--active-bg);
        color: var(--text-white);
    }

    .nav-item i.chevron {
        font-size: 10px;
        transition: 0.3s;
    }

    .nav-item.active i.chevron {
        transform: rotate(180deg);
    }

    .submenu {
        margin-bottom: 15px;
        padding-left: 10px;
    }

    .sub-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 15px;
        color: var(--text-dim);
        text-decoration: none;
        font-size: 13px;
        transition: 0.2s;
    }

    .sub-link:hover {
        color: var(--text-white);
    }

    .sub-link i {
        font-size: 14px;
        width: 18px;
        text-align: center;
    }

    .si-ig {
        color: #e1306c;
    }

    .si-tw {
        color: #1da1f2;
    }

    .si-fb {
        color: #1877f2;
    }

    .si-li {
        color: #0077b5;
    }

    .si-yt {
        color: #ff0000;
    }

    .si-tk {
        color: #ffffff;
    }

    .side-footer {
        padding-top: 15px;
        border-top: 1px solid #222;
    }

    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: transparent;
        border: 1px solid #222;
        border-radius: 10px;
        color: #ef4444;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }

    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
    }

    .content-area {
        margin-left: 300px;
        padding: 40px;
    }
</style>

<div class="sidebar-container">
    <div class="rail">
        <div class="rail-logo">
            <i class="fa-solid fa-bahai"></i>
        </div>
        <div class="rail-btn active"><i class="fa-solid fa-table-cells-large"></i></div>
        <div class="rail-btn"><i class="fa-solid fa-diagram-project"></i></div>
        <div class="rail-btn"><i class="fa-solid fa-chart-line"></i></div>
        <div class="rail-btn"><i class="fa-solid fa-folder-open"></i></div>
        <div class="rail-btn"><i class="fa-solid fa-coins"></i></div>
        <div class="rail-btn"><i class="fa-solid fa-user-gear"></i></div>
    </div>

    <div class="main-side">
        <div class="side-header">
            <h2>Dashboard</h2>
            <div class="collapse-btn"><i class="fa-solid fa-chevron-left"></i></div>
        </div>

        <div class="nav-list">
            <?php foreach ($menus as $menu):
                $active_class = '';
                preg_match('/(\d+)\.html/', $menu['link'], $matches);
                $menu_id = isset($matches[1]) ? intval($matches[1]) : 0;

                if ($menu_id > 0 && $menu_id == $current_menu_id) {
                    $active_class = 'active';
                }

                if (empty($active_class) && !empty($current_mod)) {
                    $menu_name = pathinfo($menu['link'], PATHINFO_FILENAME);
                    $menu_name_clean = str_replace('-', '_', preg_replace('/\d+/', '', $menu_name));
                    if (strpos($current_mod, $menu_name_clean) !== false) {
                        $active_class = 'active';
                    }
                }

                if (empty($active_class) && $current_menu_id == 0 && strpos($menu['link'], 'home') !== false) {
                    $active_class = 'active';
                }

                $is_social = (stripos($menu['title'], 'Social') !== false);
            ?>
                <a href="<?php echo $menu['link']; ?>" class="nav-item <?php echo $active_class; ?>">
                    <span><?php echo $menu['title']; ?></span>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </a>

                <?php if ($is_social): ?>
                    <div class="submenu">
                        <a href="#" class="sub-link"><i class="fa-brands fa-instagram si-ig"></i> Instagram</a>
                        <a href="#" class="sub-link"><i class="fa-brands fa-twitter si-tw"></i> Twitter</a>
                        <a href="#" class="sub-link"><i class="fa-brands fa-facebook si-fb"></i> Facebook</a>
                        <a href="#" class="sub-link"><i class="fa-brands fa-linkedin si-li"></i> LinkedIn</a>
                        <a href="#" class="sub-link"><i class="fa-brands fa-youtube si-yt"></i> Youtube</a>
                        <a href="#" class="sub-link"><i class="fa-brands fa-tiktok si-tk"></i> Tiktok</a>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <div class="side-footer">
            <a href="index.php?mod=user.logout" class="logout-btn">
                <i class="fa-solid fa-power-off"></i>
                Logout
                <span>↗</span>
            </a>


        </div>
    </div>
</div>

<div class="content-area">
</div>