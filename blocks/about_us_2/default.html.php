<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .stats-section {
        margin-top: 10px;
        width: 100%;
        background-color: #ffffff;
        border-top: 1px solid #e0e0e0;
        border-bottom: 1px solid #e0e0e0;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        margin-bottom: 10px;
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
    }

    .stats-item {
        flex: 1;
        min-width: 250px;
        padding: 60px 20px;
        text-align: center;
        border-right: 1px solid #e0e0e0;
    }

    .stats-item:last-child {
        border-right: none;
    }

    .stats-number {
        display: block;
        font-size: 84px;
        font-weight: 700;
        color: #000000;
        line-height: 1;
        margin-bottom: 15px;
        letter-spacing: -2px;
    }

    .stats-label {
        display: block;
        font-size: 20px;
        color: #555555;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .stats-item {
            flex: 0 0 100%;
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
            padding: 40px 20px;
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-number {
            font-size: 60px;
        }
    }
</style>

<div class="stats-section">
    <div class="stats-container">
        <?php
        if (!empty($config['items'])) {
            $lines = explode("\n", str_replace("\r", "", $config['items']));
            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                $data = explode('|', $line);
                $number = isset($data[0]) ? trim($data[0]) : '0';
                $label  = isset($data[1]) ? trim($data[1]) : '';
        ?>
                <div class="stats-item">
                    <span class="stats-number"><?php echo $number; ?></span>
                    <span class="stats-label"><?php echo $label; ?></span>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>