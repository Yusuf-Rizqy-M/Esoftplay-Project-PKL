<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>
<style>
    .story-block {
        font-family: 'Inter', sans-serif;
        padding: 80px 0;
        background-color: #fff;
    }
    .story-container { 
        max-width: 1100px; 
        margin: 0 auto; 
        padding: 0 30px; 
    }
    .stats-grid {
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-top: 1px solid #ddd;
        padding-top: 50px;
        margin-top: 50px;
        text-align: center;
    }
    .stat-item {
        flex: 1;
        padding: 20px;
    }
    .stat-item:not(:last-child) {
        border-right: 1px solid #eee;
    }
    .stat-number {
       
        font-size: 80px;
        font-weight: 700;
        color: #000;
        display: block;
        line-height: 1;
        margin-bottom: 12px;
        letter-spacing: -1px;
    }
    .stat-label {
       
        font-size: 18px;
        color: #666;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .stats-grid { flex-direction: column; }
        .stat-item:not(:last-child) {
            border-right: none;
            border-bottom: 1px solid #eee;
            width: 100%;
        }
        .stat-number { font-size: 64px; }
    }
</style>

<section class="story-block">
    <div class="story-container">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number"><?php echo $config['stat1_num']; ?></span>
                <span class="stat-label"><?php echo $config['stat1_lbl']; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $config['stat2_num']; ?></span>
                <span class="stat-label"><?php echo $config['stat2_lbl']; ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $config['stat3_num']; ?></span>
                <span class="stat-label"><?php echo $config['stat3_lbl']; ?></span>
            </div>
        </div>
    </div>
</section>