<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    /* Menggunakan font sans-serif modern agar mirip dengan gambar */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

    .client-section {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        text-align: center;
        padding: 30px; /* Memberikan ruang atas dan bawah */
        background-color: #ffffff;
        color: #111827; /* Warna hitam yang lembut */
    }

    .client-section h1 {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: -0.02em;
    }

    .client-section p {
        font-size: 18px;
        line-height: 1.6;
        color: #4b5563; /* Warna abu-abu gelap untuk deskripsi */
        max-width: 700px; /* Membatasi lebar teks agar persis seperti di gambar */
        margin: 0 auto; /* Menengahkan blok paragraf */
    }

    /* Responsif untuk layar hp */
    @media (max-width: 640px) {
        .client-section h1 {
            font-size: 28px;
        }
        .client-section p {
            font-size: 16px;
        }
    }
</style>

<div class="client-section">
    <h1><?php echo $config['heading'] ?></h1>
    <p><?php echo $config['tagline'] ?></p>
</div>