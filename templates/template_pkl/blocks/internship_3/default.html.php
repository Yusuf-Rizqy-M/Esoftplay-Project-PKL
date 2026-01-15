<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .partnership-wrapper {
        text-align: center;
        font-family: 'Arial', sans-serif;
        padding: 180px 10px;
        background-color: #ffffff;
    }

    .partnership-title {
        margin-bottom: 50px;
        font-size: 32px;
        font-weight: 600;
        color: #333;
    }

    .logo-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        row-gap: 40px;
        column-gap: 20px;
        max-width: 1100px;
        margin: 0 auto;
    }

    .logo-item {
        flex: 0 0 calc(16.66% - 20px);
        min-width: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .logo-img {
        width: 100%;
        max-width: 110px;
        height: auto;
        object-fit: contain;
    }

    @media (max-width: 992px) {
        .logo-item {
            flex: 0 0 calc(25% - 20px);
        }
    }

    @media (max-width: 768px) {
        .logo-item {
            flex: 0 0 calc(33.33% - 20px);
        }
    }

    @media (max-width: 480px) {
        .logo-item {
            flex: 0 0 calc(50% - 20px);
        }
    }
</style>

<div class="partnership-wrapper">
    <h2 class="partnership-title">
        <?php echo !empty($config['heading']) ? $config['heading'] : 'Partnership'; ?>
    </h2>
    
    <div class="logo-grid">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            $img_url = $config['image' . $i];
            if (!empty($img_url)) {
                ?>
                <div class="logo-item">
                    <img src="<?php echo $img_url; ?>" class="logo-img" alt="Logo">
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>