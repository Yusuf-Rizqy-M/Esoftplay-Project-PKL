
<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

    .internship-section {
        width: 100%;
        background-color: #ffffff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 40px 0;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;
    }

    .feature-card {
        padding: 80px 40px;
        text-align: center;
        border: 1px solid #f3f4f6;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 480px;
        background: #fff;
        position: relative;
        overflow: hidden;
    }

    /* Efek garis oranye di bawah saat hover */
    .feature-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 4px;
        background: #FFB300;
        transition: width 0.4s ease;
    }

    .feature-card:hover {
        background-color: #fff;
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        z-index: 2;
        border-color: transparent;
    }

    .feature-card:hover::after {
        width: 100%;
    }

    .icon-wrapper {
        width: 100px;
        height: 100px;
        background-color: rgba(255, 179, 0, 0.05);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 35px;
        transition: all 0.3s ease;
    }

    .feature-card:hover .icon-wrapper {
        background-color: #FFB300;
        transform: scale(1.1) rotate(5deg);
    }

    .feature-card img {
        width: 55px;
        height: 55px;
        object-fit: contain;
        display: block;
        transition: all 0.3s ease;
    }

    .feature-card:hover img {
        filter: brightness(0) invert(1); /* Mengubah ikon jadi putih saat bg wrapper jadi oranye */
    }

    .feature-card h3 {
        font-size: 2.5rem; /* Teks digedein */
        font-weight: 800;
        color: #111827;
        margin-bottom: 20px;
        line-height: 1.1;
        letter-spacing: -0.05em;
    }

    .feature-card p {
        font-size: 1.25rem; /* Teks deskripsi digedein */
        color: #6b7280;
        line-height: 1.7;
        max-width: 350px;
        margin: 0 auto;
        font-weight: 400;
    }

    @media (max-width: 1200px) {
        .feature-card h3 { font-size: 2rem; }
        .feature-card p { font-size: 1.1rem; }
    }

    @media (max-width: 992px) {
        .feature-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .feature-grid { grid-template-columns: 1fr; }
        .feature-card { min-height: auto; padding: 60px 30px; }
        .feature-card h3 { font-size: 1.8rem; }
        .icon-wrapper { width: 80px; height: 80px; }
        .feature-card img { width: 45px; height: 45px; }
    }
</style>

<section class="internship-section">
    <div class="feature-grid">
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <?php if (!empty($config['text'.$i])): ?>
                <div class="feature-card">
                    <div class="icon-wrapper">
                        <?php if (!empty($config['icon'.$i])): ?>
                            <img src="<?php echo $config['icon'.$i]; ?>" alt="<?php echo $config['text'.$i]; ?>">
                        <?php else: ?>
                            <img src="https://cdn-icons-png.flaticon.com/512/1055/1055666.png" alt="default">
                        <?php endif; ?>
                    </div>

                    <h3><?php echo $config['text'.$i]; ?></h3>
                    <p><?php echo isset($config['desc'.$i]) ? $config['desc'.$i] : ''; ?></p>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</section>