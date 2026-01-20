<style>
    .hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 50px 5%;
        background: #ffffff;
        gap: 40px;
        margin-top: 60px;
        margin-bottom: 150px;
        overflow: hidden;
    }

    .hero-text {
        position: relative;
        max-width: 600px;
        flex-shrink: 0;
        text-align: left;
        z-index: 2;
    }

    .hero-text p:first-child {
        font-size: 21px;
        color: #7a7a7a;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hero-text p:first-child span {
        color: #FFD700;
        font-weight: bold;
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
        position: relative;
        max-width: 580px;
        width: 100%;
        border-radius: 8px;
        flex-shrink: 0;
        object-fit: cover;
        margin-left: 400px;
        z-index: 2;
    }

    .hero-shape {
        position: absolute;
        background: #FFF59D;
        z-index: 1;
        opacity: 0.5;
    }
    .h-shape-1 { top: 10%; left: 2%; width: 80px; height: 80px; border-radius: 15px; transform: rotate(20deg); }
    .h-shape-2 { bottom: 15%; left: 40%; width: 60px; height: 60px; border-radius: 50%; }
    .h-shape-3 { top: 20%; right: 10%; width: 70px; height: 70px; clip-path: polygon(50% 0%, 0% 100%, 100% 100%); transform: rotate(45deg); }
    .h-shape-4 { bottom: 10%; right: 5%; width: 50px; height: 50px; clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); }

    @media (max-width: 1400px) {
        .hero img { margin-left: 100px; }
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
        .hero-shape { opacity: 0.3; }
    }
</style>

<div class="hero">
    <div class="hero-shape h-shape-1"></div>
    <div class="hero-shape h-shape-2"></div>
    <div class="hero-shape h-shape-3"></div>
    <div class="hero-shape h-shape-4"></div>

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