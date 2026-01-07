<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .sertifikat-wrapper {
        display: flex;
        gap: 40px;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        max-width: 1000px;
        margin: 40px auto;
        color: #333;
        line-height: 1.6;
    }

    /* Bagian Kiri */
    .sertifikat-left {
        flex: 2;
    }

    .header-sertifikat {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
    }

    .header-sertifikat img {
        width: 60px;
        height: auto;
    }

    .header-text h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 500;
        color: #444;
    }

    .header-text p {
        margin: 5px 0 0 0;
        font-style: italic;
        font-size: 20px;
        font-weight: 600;
    }

    .sertifikat-content {
        font-size: 16px;
        text-align: justify;
    }

    /* Bagian Kanan (Card) */
    .sertifikat-right {
        flex: 1;
    }

    .profile-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 40px 20px;
        text-align: center;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .profile-card img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 20px;
    }

    .profile-card h2 {
        font-size: 18px;
        margin-bottom: 25px;
        font-weight: 700;
    }

    .info-label {
        font-size: 13px;
        color: #888;
        margin: 0;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 18px;
        font-weight: 700;
        margin: 5px 0 20px 0;
    }

    .divider {
        height: 2px;
        background-color: #87CEEB;
        width: 80%;
        margin: 20px auto;
    }

    .link-profil {
        display: block;
        color: #555;
        text-decoration: none;
        font-size: 14px;
        margin-top: 15px;
    }

    .link-profil:hover {
        color: #000;
    }

    @media (max-width: 768px) {
        .sertifikat-wrapper {
            flex-direction: column;
            padding: 20px;
        }
    }
</style>

<div class="sertifikat-wrapper">
    <div class="sertifikat-left">
        <div class="header-sertifikat">
            <img src="<?php echo $config['image1']; ?>" alt="Icon Sertifikat">
            <div class="header-text">
                <h1><?php echo $config['Tagline']; ?></h1>
                <p>Telah diberikan pada tanggal <?php echo $config['tanggal']; ?></p>
            </div>
        </div>

        <div class="sertifikat-content">
            <?php echo nl2br($config['Intro']); ?>
        </div>
    </div>

    <div class="sertifikat-right">
        <div class="profile-card">
            <img src="<?php echo $config['foto_profil']; ?>" alt="Foto Profil">
            <h2><?php echo $config['name']; ?></h2>
            
            <p class="info-label"><?php echo $config['id']; ?></p>
            <p class="info-value"><?php echo $config['int_id']; ?></p>
            
            <p class="info-label"><?php echo $config['diberikan_kepada']; ?></p>
            <p class="info-value"><?php echo $config['tanggal']; ?></p>
            
            <div class="divider"></div>
            
            <a href="#" class="link-profil">
                <?php echo $config['lihat_profil_lengkap']; ?> &gt;
            </a>
        </div>
    </div>
</div>