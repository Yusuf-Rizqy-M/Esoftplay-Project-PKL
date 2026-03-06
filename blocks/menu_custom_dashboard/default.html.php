<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// Ambil status menu saat ini
$current_menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;
$current_mod     = isset($_GET['mod']) ? $_GET['mod'] : '';
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --bg-sidebar: #ffffff;    /* Putih Bersih */
        --accent-color: #ffcb22;  /* Kuning Esoftplay */
        --accent-hover: #f5b800;  /* Oranye Kuning Gelap */
        --text-dark: #1e293b;     /* Teks Utama */
        --text-gray: #64748b;     /* Teks Sekunder */
        --border-color: #f1f5f9;  /* Abu-abu sangat muda */
        --sidebar-width: 260px;
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
    }

    /* --- SIDEBAR CONTAINER --- */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--bg-sidebar);
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--border-color);
        z-index: 1000;
        box-shadow: 2px 0 15px rgba(0,0,0,0.02);
    }

    /* Logo Section */
    .sidebar-header {
        padding: 25px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 10px;
    }

    .logo-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
    }

    .logo-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .sidebar-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        letter-spacing: -0.5px;
        line-height: 1;
    }

    /* Navigation List */
    .nav-container {
        flex: 1;
        padding: 0 12px;
        overflow-y: auto;
    }

    .nav-container::-webkit-scrollbar { width: 4px; }
    .nav-container::-webkit-scrollbar-thumb { background: #eee; border-radius: 10px; }

    .nav-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        margin: 15px 12px 10px;
        letter-spacing: 1px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 14px;
        color: var(--text-gray);
        text-decoration: none !important;
        font-size: 14px;
        font-weight: 500;
        border-radius: 10px;
        margin-bottom: 4px;
        transition: all 0.2s ease;
    }

    .nav-link i {
        width: 24px;
        font-size: 18px;
        margin-right: 12px;
        display: flex;
        justify-content: center;
        transition: 0.2s;
    }

    /* Hover State */
    .nav-link:hover {
        background: #fffbeb; 
        color: var(--accent-hover);
    }

    /* Active State - SEKARANG MENGGUNAKAN TEKS PUTIH */
    .nav-link.active {
        background: var(--accent-color);
        color: #ffffff; /* Teks jadi putih sesuai permintaan */
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 203, 34, 0.3);
        text-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1); /* Sedikit shadow agar teks putih lebih terbaca */
    }

    .nav-link.active i {
        color: #ffffff; /* Ikon jadi putih */
    }

    /* --- FOOTER / LOGOUT --- */
    .sidebar-footer {
        padding: 16px;
        border-top: 1px solid var(--border-color);
    }

    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 12px;
        background: #fff1f2; 
        color: #e11d48;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none !important;
        transition: 0.2s;
    }

    .btn-logout:hover {
        background: #ffe4e6;
        transform: translateY(-1px);
    }

    /* Content push */
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 40px;
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-wrapper">
            <img src="<?php echo $config['logo']; ?>" alt="Logo Esoftplay">
        </div>
        <h2>Esoftplay Internship</h2>
    </div>

    <div class="nav-container">
        <div class="nav-label">Menu Utama</div>
        
        <?php 
        foreach ($menus as $menu): 
            $active_class = '';
            
            // Logika Deteksi Active
            preg_match('/(\d+)\.html/', $menu['link'], $matches);
            $menu_id = isset($matches[1]) ? intval($matches[1]) : 0;
            $menu_name = pathinfo($menu['link'], PATHINFO_FILENAME);
            $menu_name_clean = str_replace('-', '_', preg_replace('/\d+/', '', $menu_name));

            if ($menu_id > 0 && $menu_id == $current_menu_id) {
                $active_class = 'active';
            } else if (!empty($current_mod) && strpos($current_mod, $menu_name_clean) !== false) {
                $active_class = 'active';
            }

            // Ikon Otomatis berdasarkan Title
            $icon = 'fa-circle-dot';
            if (stripos($menu['title'], 'Tugas') !== false || stripos($menu['title'], 'Task') !== false) $icon = 'fa-list-check';
            if (stripos($menu['title'], 'Laporan') !== false) $icon = 'fa-file-lines';
            if (stripos($menu['title'], 'Sertif') !== false) $icon = 'fa-certificate';
            if (stripos($menu['title'], 'Intern') !== false) $icon = 'fa-users-rectangle';
            if (stripos($menu['title'], 'Contact') !== false) $icon = 'fa-address-book';
            if (stripos($menu['title'], 'Dashboard') !== false) $icon = 'fa-house';
        ?>
            <a href="<?php echo $menu['link']; ?>" class="nav-link <?php echo $active_class; ?>">
                <i class="fa-solid <?php echo $icon; ?>"></i>
                <span><?php echo $menu['title']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="sidebar-footer">
        <a href="index.php?mod=user.logout" class="btn-logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            Sign Out
        </a>
    </div>
</div>

<div class="main-content">
    </div>