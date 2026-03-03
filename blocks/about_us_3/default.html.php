<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .team-section { font-family: 'Arial', sans-serif; padding: 60px 0; background: #fff; }
    .team-title { text-align: center; font-size: 32px; font-weight: 500; margin-bottom: 50px; color: #000; }
    .team-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; max-width: 1100px; margin: 0 auto; padding: 0 20px; }
    .team-card { display: flex; flex-direction: column; }
    .team-image { width: 100%; height: 230px; overflow: hidden; margin-bottom: 15px; }
    .team-image img { width: 100%; height: 100%; object-fit: cover; }
    .team-name { font-size: 20px; font-weight: 600; color: #333; margin-bottom: 5px; }
    .team-role { font-size: 15px; font-weight: 600; color: #0084ff; margin-bottom: 12px; display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
    .team-role span.pipe { color: #333; font-weight: normal; }
    .team-desc { font-size: 13.5px; color: #666; line-height: 1.6; margin-bottom: 15px; text-align: left; }
    .team-social { display: flex; gap: 15px; }
    .team-social a { color: #000; font-size: 22px; text-decoration: none; transition: 0.2s; }
    .team-social a:hover { opacity: 0.6; }
    @media (max-width: 992px) { .team-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .team-grid { grid-template-columns: 1fr; } }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="team-section">
    <h2 class="team-title"><?php echo $config['heading']; ?></h2>
    <div class="team-grid">
        <?php
        /* Pastikan angka loop di bawah ini sama dengan yang ada di _setting.php */
        for ($i = 1; $i <= 6; $i++):
            $name = !empty($config['m_name'.$i]) ? $config['m_name'.$i] : 'Ahmad Syafiq';
            $role = !empty($config['m_role'.$i]) ? $config['m_role'.$i] : 'Staff Operator | Teacher PKL';
            $desc = !empty($config['m_desc'.$i]) ? $config['m_desc'.$i] : 'Designed to simplify project management, delight clients, and increase profitability.';
            $img  = !empty($config['m_img'.$i]) ? $config['m_img'.$i] : 'https://via.placeholder.com/400x300';
            $ig   = !empty($config['m_ig'.$i]) ? $config['m_ig'.$i] : '#';
            $gh   = !empty($config['m_gh'.$i]) ? $config['m_gh'.$i] : '#';

            $role_items = explode('|', $role);
        ?>
        <div class="team-card">
            <div class="team-image">
                <img src="<?php echo $img; ?>" alt="">
            </div>
            <div class="team-name"><?php echo $name; ?></div>
            <div class="team-role">
                <?php 
                foreach ($role_items as $index => $item) {
                    echo '<span>' . trim($item) . '</span>';
                    if ($index < count($role_items) - 1) {
                        echo '<span class="pipe">|</span>';
                    }
                }
                ?>
            </div>
            <div class="team-desc"><?php echo $desc; ?></div>
            <div class="team-social">
                <a href="<?php echo $ig; ?>" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                <a href="<?php echo $gh; ?>" target="_blank"><i class="fa-brands fa-github"></i></a>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</div>