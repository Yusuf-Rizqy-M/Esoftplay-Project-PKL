<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');

    .hero-section {
        position: relative;
        overflow: hidden; 
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 140px 8%; 
        background: #ffffff;
        font-family: 'Inter', sans-serif;
        min-height: 70vh;
        gap: 60px;
    }

    /* --- DEKORASI (Hanya Warna Kuning, Tanpa Animasi) --- */
    .deco-shape {
        position: absolute;
        z-index: 1;
        opacity: 0.8; /* Lebih tegas karena tidak bergerak */
        fill: #faee84; /* Warna kuning asli Anda */
        pointer-events: none;
    }

    /* Penempatan posisi dekorasi yang seimbang */
    .shape-square { width: 45px; height: 45px; top: 15%; left: 6%; transform: rotate(15deg); }
    .shape-circle-big { width: 100px; height: 100px; bottom: 10%; left: 4%; opacity: 0.4; }
    .shape-triangle { width: 55px; height: 55px; top: 12%; right: 12%; transform: rotate(-10deg); }
    .shape-star { width: 50px; height: 50px; bottom: 20%; right: 7%; }
    .shape-dots { width: 40px; height: 40px; top: 70%; left: 15%; }

    /* --- KONTEN TEKS --- */
    .hero-text {
        flex: 1;
        max-width: 600px;
        position: relative;
        z-index: 2;
    }

    .hero-intro {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-left: 4px solid #faee84; /* Aksen kuning di samping teks intro */
        padding-left: 12px;
    }

    .hero-text h1 {
        font-size: 52px;
        line-height: 1.1;
        margin: 0 0 24px;
        color: #ffa600ff;
        font-weight: 600;
    }

    .hero-description {
        font-size: 18px;
        line-height: 1.8;
        color: #4b5563;
        margin: 0;
    }

    /* --- KONTEN GAMBAR --- */
    .hero-image {
        flex: 1;
        display: flex;
        justify-content: flex-end;
        position: relative;
        z-index: 2;
    }

    .image-container {
        position: relative;
        width: 100%;
        max-width: 550px;
    }

    .hero-image img {
        width: 100%;
        height: auto;
        border-radius: 24px; 
        object-fit: cover;
        display: block;
        box-shadow: 20px 20px 0px #faee84; /* Aksen kotak kuning kaku di belakang gambar */
        border: 1px solid #e5e7eb;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        .hero-section {
            flex-direction: column;
            text-align: center;
            padding: 100px 24px;
            gap: 50px;
        }
        .hero-text { max-width: 100%; }
        .hero-intro { justify-content: center; border-left: none; border-bottom: 3px solid #faee84; padding: 0 0 4px; }
        .hero-text h1 { font-size: 38px; }
        .hero-image { justify-content: center; }
        .hero-image img { box-shadow: 10px 10px 0px #faee84; }
        .deco-shape { display: none; }
    }
</style>

<div class="hero-section">
    <svg class="deco-shape shape-square" viewBox="0 0 100 100"><rect width="100" height="100" rx="15" /></svg>
    <svg class="deco-shape shape-circle-big" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" /></svg>
    <svg class="deco-shape shape-triangle" viewBox="0 0 100 100"><polygon points="50 15, 100 100, 0 100"/></svg>
    <svg class="deco-shape shape-star" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
   
    <div class="hero-text">
        <?php if (!empty($config['intro'])): ?>
            <div class="hero-intro">
                <?php echo $config['intro']; ?>
            </div>
        <?php endif; ?>
        
        <h1><?php echo $config['heading']; ?></h1>
        <p class="hero-description"><?php echo $config['tagline']; ?></p>
    </div>
    
    <div class="hero-image">
        <div class="image-container">
            <img src="<?php echo $config['hero']; ?>" alt="Hero Image">
        </div>
    </div>
</div>