<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    .internship-section {
        width: 100%;
        background-color: #ffffff;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        width: 100%;
    }

    .feature-card {
        padding: 90px 50px;
        text-align: center;
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        transition: all 0.3s ease;
        min-height: 420px;
        background: #fff;
    }

    .feature-card:hover {
        background-color: #fafafa;
    }

    .feature-card img {
        width: 90px;
        height: auto;
        margin-bottom: 35px;
        display: block;
    }

    .feature-card h3 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 20px;
        line-height: 1.2;
        letter-spacing: -0.04em;
    }

    .feature-card p {
        font-size: 1.15rem;
        color: #4b5563;
        line-height: 1.6;
        max-width: 380px;
        margin: 0 auto;
        font-weight: 400;
    }

    @media (max-width: 1200px) {
        .feature-card h3 { font-size: 1.8rem; }
        .feature-card { padding: 70px 30px; }
    }

    @media (max-width: 992px) {
        .feature-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .feature-grid { grid-template-columns: 1fr; }
        .feature-card { min-height: auto; padding: 60px 25px; }
        .feature-card h3 { font-size: 1.6rem; }
    }
</style>

<section class="internship-section">
    <div class="feature-grid">
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <?php if (!empty($config['text'.$i])): ?>
                <div class="feature-card">
                    <?php if (!empty($config['icon'.$i])): ?>
                        <img src="<?php echo $config['icon'.$i]; ?>" alt="<?php echo $config['text'.$i]; ?>">
                    <?php endif; ?>

                    <h3><?php echo $config['text'.$i]; ?></h3>
                    <p><?php echo isset($config['desc'.$i]) ? $config['desc'.$i] : ''; ?></p>
                </div>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</section>