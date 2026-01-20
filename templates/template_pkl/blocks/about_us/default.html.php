<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
.about-section-wrapper {
    margin: 0;
    padding: 0;
    width: 100%;
    overflow-x: hidden;
    background: #ffffff;
}

.about-section {
    position: relative;
    padding: 80px 20px;
    display: flex;
    flex-direction: column;
    justify-content: center; 
    align-items: center;     
    min-height: 100vh;       
    box-sizing: border-box;
    text-align: center;
}

.about-content-container {
    width: 100%;
    max-width: 1000px;
    margin-left: 50px;
    transition: margin 0.3s ease;
}

.about-section h1 {
    font-size: clamp(28px, 5vw, 36px);
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 15px 0;
    display: inline-block;
    position: relative;
}

.about-section h1::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 20%;
    width: 60%;
    height: 3px;
    background-color: #FFD700;
}

.about-section p {
    max-width: 700px;
    margin: 30px auto 50px;
    font-size: 18px;
    color: #888;
    line-height: 1.6;
    font-weight: 500;
}

.about-gallery-wrapper {
    position: relative;
    width: 100%;
    z-index: 2;
}

.gallery-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr 1.2fr;
    grid-template-rows: 240px 240px;
    gap: 20px;
}

.gallery-grid img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    cursor: pointer;
}

.gallery-grid img:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.img-left { grid-column: 1; grid-row: 1 / span 2; }
.img-mid-top { grid-column: 2; grid-row: 1; }
.img-mid-bottom { grid-column: 2; grid-row: 2; }
.img-right { grid-column: 3; grid-row: 1 / span 2; }

.shape {
    position: absolute;
    background: #FFF59D;
    z-index: 1;
    opacity: 0.6;
}
.shape-square { top: 10%; left: 5%; width: 60px; height: 60px; border-radius: 12px; transform: rotate(35deg); }
.shape-circle { top: 20%; right: 5%; width: 50px; height: 50px; border-radius: 50%; }
.shape-triangle { bottom: 10%; left: 8%; width: 50px; height: 50px; clip-path: polygon(50% 0%, 0% 100%, 100% 100%); transform: rotate(-15deg); }
.shape-star { bottom: 15%; right: 8%; width: 60px; height: 60px; clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); }

@media (max-width: 1100px) {
    .about-content-container { margin-left: 50px; }
}

@media (max-width: 992px) {
    .about-content-container { margin-left: 0; padding: 0 20px; }
    .gallery-grid {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: repeat(3, 200px);
    }
    .img-left { grid-column: 1; grid-row: 1 / span 2; }
    .img-right { grid-column: 2; grid-row: 1 / span 2; }
    .img-mid-top { grid-column: 1; grid-row: 3; }
    .img-mid-bottom { grid-column: 2; grid-row: 3; }
    .shape { display: none; }
}

@media (max-width: 600px) {
    .about-section { padding: 60px 15px; }
    .gallery-grid {
        grid-template-columns: 1fr;
        grid-template-rows: auto;
        gap: 15px;
    }
    .img-left, .img-mid-top, .img-mid-bottom, .img-right {
        grid-column: span 1;
        grid-row: auto;
        height: 220px;
    }
    .about-section p { margin: 20px 0 40px; font-size: 16px; }
}
</style>

<div class="about-section-wrapper">
    <div class="about-section">
        <div class="shape shape-square"></div>
        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-star"></div>

        <div class="about-content-container">
            <h1><?php echo $config['Tagline'] ?></h1> 
            <p><?php echo $config['Intro'] ?></p> 

            <div class="about-gallery-wrapper">
                <div class="gallery-grid"> 
                    <img class="img-left" src="<?php echo $config['image1'] ?>" alt="Gallery 1"> 
                    <img class="img-mid-top" src="<?php echo $config['image2'] ?>" alt="Gallery 2"> 
                    <img class="img-mid-bottom" src="<?php echo $config['image4'] ?>" alt="Gallery 4"> 
                    <img class="img-right" src="<?php echo $config['image3'] ?>" alt="Gallery 3"> 
                </div> 
            </div>
        </div>
    </div>
</div>