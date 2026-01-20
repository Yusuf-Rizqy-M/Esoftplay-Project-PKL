<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .diff-section {
        padding: 80px 20px;
        text-align: center;
        background-color: #ffffff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .diff-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .diff-title {
        font-size: 36px;
        font-weight: 700;
        color: #000000;
        margin-bottom: 25px;
        letter-spacing: -0.5px;
    }

    .diff-desc {
        font-size: 18px;
        line-height: 1.6;
        color: #555555;
        margin-bottom: 35px;
    }

    .diff-download-text {
        font-size: 16px;
        color: #333333;
        margin-bottom: 10px;
        display: block;
    }

    .diff-icon {
        display: block;
        margin: 0 auto 20px auto;
        width: 30px;
        height: 30px;
    }

    .btn-github {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #000000;
        color: #ffffff;
        text-decoration: none;
        padding: 15px 100px;
        border-radius: 8px;
        font-size: 24px;
        font-weight: 600;
        transition: transform 0.2s ease, background-color 0.2s ease;
        gap: 15px;
    }

    .btn-github:hover {
        background-color: #222222;
        transform: translateY(-2px);
        color: #ffffff;
    }

    .btn-github svg {
        width: 32px;
        height: 32px;
        fill: currentColor;
    }

    @media (max-width: 768px) {
        .diff-title { font-size: 28px; }
        .diff-desc { font-size: 16px; }
        .btn-github { width: 100%; padding: 15px 20px; }
    }
</style>

<div class="diff-section">
    <div class="diff-container">
        <h2 class="diff-title"><?php echo $config['heading']; ?></h2>
        
        <p class="diff-desc">
            <?php echo nl2br($config['description']); ?>
        </p>

        <span class="diff-download-text"><?php echo $config['sub_text']; ?></span>

        <div class="diff-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 13l5 5 5-5M7 6l5 5 5-5"/>
            </svg>
        </div>

        <a href="<?php echo $config['btn_url']; ?>" class="btn-github">
            <svg viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.416-4.041-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
            </svg>
            <?php echo $config['btn_text']; ?>
        </a>
    </div>
</div>