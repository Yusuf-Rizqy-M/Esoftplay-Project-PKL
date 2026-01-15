<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

    .hero-section {
        /* Tambahan penting agar elemen dekorasi bisa diposisikan absolut relatif terhadap container ini */
        position: relative;
        overflow: hidden; 
        
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 220px 5% 180px; 
        background: #ffffff;
        font-family: 'Inter', sans-serif;
        gap: 60px;
        margin-bottom: 40px; 
    }

    /* --- STYLE BARU UNTUK DEKORASI --- */
    .deco-shape {
        position: absolute;
        /* Warna Biru Muda (Light Blue) */
        fill: #ADD8E6; 
        /* Sedikit transparan agar tidak mengganggu konten */
        opacity: 0.7;
        z-index: 0; /* Letakkan di belakang teks dan gambar */
    }

    /* Posisi masing-masing bentuk */
    .shape-square {
        width: 40px; height: 40px;
        top: 15%; left: 8%;
        transform: rotate(15deg);
    }

    .shape-circle-big {
        width: 60px; height: 60px;
        bottom: 20%; left: 5%;
    }
    
    .shape-triangle {
        width: 50px; height: 50px;
        top: 10%; right: 15%;
        transform: rotate(-10deg);
    }

    .shape-star {
        width: 70px; height: 70px;
        bottom: 15%; right: 8%;
    }

    .shape-circle-small {
        width: 1px; height: 1px;
        top: 85%; left: 15%; /* Di tengah-tengah agak kiri */
    }


    /* Memastikan konten utama tetap di depan dekorasi */
    .hero-text, .hero-image {
        position: relative;
        z-index: 2; 
    }

    .hero-text {
        flex: 1;
        max-width: 550px;
    }

    .hero-intro {
        font-size: 18px;
        color: #6b7280;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .hero-text h1 {
        font-size: 48px;
        line-height: 1.1;
        margin: 0 0 24px;
        color: #111827;
        font-weight: 600;
        letter-spacing: -0.02em;
    }

    .hero-description {
        font-size: 18px;
        line-height: 1.7;
        color: #4b5563;
        margin: 0;
    }

    .hero-image {
        flex: 1;
        display: flex;
        justify-content: flex-end;
    }

    .hero-image img {
        width: 100%;
        max-width: 550px;
        height: auto;
        border-radius: 12px;
        object-fit: cover;
        display: block;
        /* Memberikan efek bayangan agar gambar terlihat lebih menonjol */
        box-shadow: 0 20px 40px rgba(0,0,0,0.1); 
    }

    @media (max-width: 992px) {
        .hero-section {
            flex-direction: column;
            text-align: left;
            padding: 100px 20px 100px; /* Padding disesuaikan sedikit untuk mobile */
            gap: 40px;
        }
        .hero-image {
            justify-content: flex-start;
        }
        .hero-text h1 {
            font-size: 36px;
        }
        
        /* Penyesuaian posisi dekorasi di layar kecil (opsional, agar tidak menumpuk) */
        .shape-star { bottom: 10%; right: 5%; width: 50px; height: 50px; }
        .shape-circle-big { bottom: 5%; left: 2%; width: 40px; height: 40px; }
        .shape-triangle { top: 5%; right: 5%; }
    }
</style>

<div class="hero-section">
    <svg class="deco-shape shape-square" viewBox="0 0 100 100">
        <rect width="100" height="100" rx="10" />
    </svg>

    <svg class="deco-shape shape-circle-big" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="50" />
    </svg>

    <svg class="deco-shape shape-triangle" viewBox="0 0 100 100">
        <polygon points="50 15, 100 100, 0 100"/>
    </svg>

    <svg class="deco-shape shape-star" viewBox="0 0 24 24">
         <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
    </svg>

     <svg class="deco-shape shape-circle-small" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="50" />
    </svg>
    <div class="hero-text">
        <?php if (!empty($config['intro'])): ?>
            <div class="hero-intro">
                <?php echo $config['intro']; ?>
                <span>➜</span>
            </div>
        <?php endif; ?>
        
        <h1><?php echo $config['heading']; ?></h1>
        <p class="hero-description"><?php echo $config['tagline']; ?></p>
    </div>
    
    <div class="hero-image">
        <img src="<?php echo $config['hero']; ?>" alt="Hero Image">
    </div>
</div>