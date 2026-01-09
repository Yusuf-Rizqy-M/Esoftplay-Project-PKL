<style>
    .esoft-container {
        width: 100%;
        font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .esoft-top-banner {
        width: 100%;
        background-color: #E3F2FD;
        padding: 12px 40px;
        display: flex;
        align-items: center;
        margin-top: 3px; 
        margin-bottom: 40px;
    }

    .esoft-top-banner span {
        font-size: 13px;
        font-weight: normal;
        color: #64748b;
    }

    .esoft-info-icon {
        background-color: #94a3b8;
        color: #fff;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-right: 12px;
        font-style: italic;
        font-size: 10px;
        flex-shrink: 0;
    }

    .esoft-main-content {
        width: 100%;
        max-width: 850px;
        padding: 0 20px 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .esoft-hero-image {
        width: 100%;
        height: auto;
        display: block;
        margin-bottom: 50px;
    }

    .esoft-bottom-line {
        width: 100%;
        height: 1px;
        background-color: #6D81F6;
        opacity: 0.6;
    }
</style>

<div class="esoft-container">
    <div class="esoft-top-banner">
        <div class="esoft-info-icon">i</div>
        <span><?php echo $config['heading']; ?></span>
    </div>

    <div class="esoft-main-content">
        <img src="<?php echo $config['hero']; ?>" class="esoft-hero-image" alt="Hero Content">
        <div class="esoft-bottom-line"></div>
    </div>
</div>