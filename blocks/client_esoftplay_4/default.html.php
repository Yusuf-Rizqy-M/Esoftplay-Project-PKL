<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .sw-timeline-section {
        background-color: #ffffff;
        padding: 80px 0;
        color: #333333;
        font-family: 'Segoe UI', Roboto, Arial, sans-serif;
        overflow: hidden;
    }
    .sw-container {
        max-width: 1140px;
        margin: 0 auto;
        position: relative;
        padding: 0 20px;
    }
    .sw-line {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 2px;
        background: #FFB300;
        transform: translateX(-50%);
        opacity: 0.3;
    }
    .sw-row {
        display: flex;
        align-items: center;
        margin-bottom: 80px;
        position: relative;
        z-index: 1;
    }
    .sw-row:nth-child(even) {
        flex-direction: row-reverse;
    }
    .sw-col-text {
        width: 42%;
    }
    .sw-col-center {
        width: 16%;
        display: flex;
        justify-content: center;
    }
    .sw-number {
        width: 46px;
        height: 46px;
        background-color: #FFB300;
        border: 4px solid #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #ffffff;
        font-size: 18px;
        box-shadow: 0 4px 15px rgba(255, 179, 0, 0.4);
    }
    .sw-col-img {
        width: 42%;
    }
    .sw-img-box img {
        width: 100%;
        height: auto;
        border-radius: 16px;
        display: block;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border: 1px solid #eee;
    }
    .sw-title1 {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #444444;
    }
    .sw-title2 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 20px;
        color: #FFB300;
        text-transform: uppercase;
        letter-spacing: -0.5px;
    }
    .sw-desc {
        font-size: 16px;
        line-height: 1.7;
        color: #666666;
    }

    @media (max-width: 991px) {
        .sw-line { left: 25px; transform: none; }
        .sw-row, .sw-row:nth-child(even) { flex-direction: column-reverse; align-items: flex-start; margin-bottom: 60px; }
        .sw-col-text, .sw-col-img, .sw-col-center { width: 100%; }
        .sw-col-center { justify-content: flex-start; margin-bottom: 20px; padding-left: 2px; }
        .sw-col-text { padding-left: 60px; margin-top: 15px; }
        .sw-col-img { padding-left: 60px; }
        .sw-number { width: 40px; height: 40px; font-size: 16px; }
    }
</style>

<div class="sw-timeline-section">
    <div class="sw-container">
        <div class="sw-line"></div>
        <?php
        for ($i = 1; $i <= 20; $i++) {
            $t1   = isset($config['item'.$i.'_title1']) ? $config['item'.$i.'_title1'] : '';
            $t2   = isset($config['item'.$i.'_title2']) ? $config['item'.$i.'_title2'] : '';
            $img  = isset($config['item'.$i.'_image']) ? $config['item'.$i.'_image'] : '';
            $desc = isset($config['item'.$i.'_tagline']) ? $config['item'.$i.'_tagline'] : '';

            if (!empty($t1) || !empty($img)) {
                ?>
                <div class="sw-row">
                    <div class="sw-col-text">
                        <div class="sw-title1"><?php echo $t1; ?></div>
                        <div class="sw-title2"><?php echo $t2; ?></div>
                        <div class="sw-desc"><?php echo $desc; ?></div>
                    </div>
                    <div class="sw-col-center">
                        <div class="sw-number"><?php echo $i; ?></div>
                    </div>
                    <div class="sw-col-img">
                        <div class="sw-img-box">
                            <img src="<?php echo $img; ?>" alt="Timeline Visual">
                        </div>
                    </div>
                </div>
                <?php
            } else {
                break;
            }
        }
        ?>
    </div>
</div>