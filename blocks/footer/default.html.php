<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Reset total agar tidak ada ruang sisa */
    body, html {
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden !important;
        width: 100% !important;
    }

    .footer-breakout {
        background: #000 !important;
        color: #fff !important;
        width: 100vw !important;
        position: relative;
        left: 50%;
        margin-left: -50vw !important;
        
        /* MENGHILANGKAN JARAK ATAS */
        margin-top: 0 !important; 
        padding-top: 80px !important; /* Sesuaikan angka ini untuk jarak isi ke atas */
        padding-bottom: 80px !important;
        
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Roboto, Arial, sans-serif;
        box-sizing: border-box;
    }

    .footer-content-top {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 40px;
    }
    .footer-content-top img {
        height: 55px;
        width: auto;
    }
    .footer-content-top h1 {
        font-size: 26px;
        font-weight: 500;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .footer-links-row {
        display: flex;
        gap: 30px;
        margin-bottom: 50px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .footer-links-row p {
        margin: 0;
        font-size: 16px;
        color: #fff;
        font-weight: 400;
    }
    .footer-social-icons {
        display: flex;
        gap: 25px;
    }
    /* Link styling agar bisa diklik dan tetap putih */
    .footer-social-icons a {
        color: #fff;
        text-decoration: none;
        transition: opacity 0.3s;
    }
    .footer-social-icons a:hover {
        opacity: 0.7;
    }
    .footer-social-icons i {
        font-size: 22px;
    }

    @media (max-width: 768px) {
        .footer-links-row { gap: 15px; padding: 0 20px; }
        .footer-links-row p { font-size: 14px; }
    }
</style>

<div class="footer-breakout">
    <div class="footer-content-top">
        <img src="<?php echo $config['image1'] ?>">
        <h1><?php echo $config['Tagline'] ?></h1>
    </div>
    
    <div class="footer-links-row">
        <p><?php echo $config['Paragraf1'] ?></p>
        <p><?php echo $config['Paragraf2'] ?></p>
        <p><?php echo $config['Paragraf3'] ?></p>
        <p><?php echo $config['Paragraf4'] ?></p>
        <p><?php echo $config['Paragraf5'] ?></p>
    </div>

    <div class="footer-social-icons">
        <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
        <a href="https://instagram.com/prabowo" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
    </div>
</div>