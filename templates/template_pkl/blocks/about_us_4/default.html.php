<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .story-block {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        padding: 80px 0;
        background-color: #fff;
        color: #000;
    }

    .story-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .story-hero {
        width: 100%;
        margin-bottom: 60px;
        line-height: 0;
    }

    .story-hero img {
        width: 100%;
        height: auto;
        display: block;
    }

    .story-grid {
        display: grid;
        grid-template-columns: 1fr 1.6fr;
        gap: 40px;
        align-items: start;
    }

    .story-header h2 {
        font-size: 36px;
        font-weight: 700;
        margin: 0 0 12px 0;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .story-header p {
        font-size: 14px;
        color: #777;
        margin: 0;
        font-weight: 400;
    }

    .story-body {
        font-size: 13px;
        line-height: 1.8;
        color: #333;
        text-align: justify;
    }

    .story-body p {
        margin: 0 0 20px 0;
    }

    @media (max-width: 768px) {
        .story-block {
            padding: 40px 0;
        }
        .story-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        .story-header h2 {
            font-size: 28px;
        }
    }
</style>

<section class="story-block">
    <div class="story-container">
        <div class="story-hero">
            <img src="<?php echo $config['hero']; ?>" alt="Hero">
        </div>

        <div class="story-grid">
            <div class="story-header">
                <h2><?php echo $config['heading']; ?></h2>
                <p><?php echo $config['tagline']; ?></p>
            </div>
            <div class="story-body">
                <?php echo nl2br($config['description']); ?>
            </div>
        </div>
    </div>
</section>