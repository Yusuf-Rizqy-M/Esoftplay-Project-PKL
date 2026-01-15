<style>
    .hero {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 50px 5%;
        background: #ffffff;
        gap: 40px;
        margin-top: 60px;
        margin-bottom: 150px;
    }

    .hero-text {
        max-width: 600px;
        flex-shrink: 0;
        text-align: left;
    }

    .hero-text p:first-child {
        font-size: 21px;
        color: #7a7a7a;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hero-text .h1 {
        font-size: 50px;
        line-height: 1.2;
        margin: 0 0 16px;
        color: #1a1a1a;
        font-weight: 500;
    }

    .hero-text p:last-child {
        font-size: 23px;
        color: #555555;
    }

    .hero img {
        max-width: 580px;
        width: 100%;
        border-radius: 8px;
        flex-shrink: 0;
        object-fit: cover;
        margin-left: 400px;
    }

    @media (max-width: 900px) {
        .hero {
            flex-direction: column;
            text-align: center;
            justify-content: center;
            padding: 60px 5%;
            gap: 40px;
            margin-top: 20px;
        }

        .hero-text {
            text-align: center;
            margin: 0 auto;
        }

        .hero-text p:first-child {
            justify-content: center;
        }

        .hero img {
            margin: 0 auto;
        }
    }
</style>

<div class="hero">
    <div class="hero-text">
        <p>
            <?php echo $config['intro'] ?>
            <span>➜</span>
        </p>
        <h1 class="h1"><?php echo $config['heading'] ?></h1>
        <p><?php echo $config['tagline'] ?></p>
    </div>
    <img src="<?php echo $config['hero'] ?>" alt="">
</div>