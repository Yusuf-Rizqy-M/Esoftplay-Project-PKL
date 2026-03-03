<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style> .hero-container { display: flex; align-items: center; justify-content: space-between; padding: 160px 10%; background-color: #ffffff; font-family: 'Arial', sans-serif; gap: 120px; }

.hero-content {
    flex: 1;
    max-width: 550px;
}

.hero-content h1 {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 24px;
    color: #000;
}

.hero-content p {
    font-size: 18px;
    line-height: 1.6;
    color: #444;
    margin-bottom: 32px;
}

.btn-group {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
}

.btn-main {
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 18px;
    text-decoration: none;
    transition: 0.3s;
    display: inline-block;
}

.btn-black {
    background: #000;
    color: #fff;
    border: 2px solid #000;
}

.btn-outline {
    background: transparent;
    color: #000;
    border: 1px solid #000;
}

.rating-section {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    color: #000;
}

.star-icon {
    color: #FFD700;
    font-size: 20px;
}

.hero-visual {
    flex: 1.2;
    display: flex;
    justify-content: flex-end;
}

.hero-visual img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
}

@media (max-width: 992px) {
    .hero-container {
        flex-direction: column;
        text-align: center;
        padding: 80px 5%;
        gap: 60px;
    }
    .hero-content {
        max-width: 100%;
    }
    .btn-group {
        justify-content: center;
    }
    .rating-section {
        justify-content: center;
    }
    .hero-visual {
        justify-content: center;
    }
}

</style>

<div class="hero-container"> <div class="hero-content"> <h1><?php echo $config['heading']; ?></h1> <p><?php echo $config['tagline']; ?></p>

    <div class="btn-group">
        <a href="#" class="btn-main btn-black"><?php echo $config['btn_primary_text']; ?></a>
        <a href="#" class="btn-main btn-outline"><?php echo $config['btn_secondary_text']; ?></a>
    </div>

    <div class="rating-section">
        <strong><?php echo $config['rating_score']; ?></strong>
        <span class="star-icon">★</span>
        <span><?php echo $config['rating_count']; ?></span>
    </div>
</div>

<div class="hero-visual">
    <img src="<?php echo $config['hero']; ?>" alt="Hero Image">
</div>

</div>