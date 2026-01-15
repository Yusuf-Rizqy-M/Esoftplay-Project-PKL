<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); 

$intro   = !empty($config['intro']) ? $config['intro'] : '';
$heading = !empty($config['heading']) ? $config['heading'] : '';
$tagline = !empty($config['tagline']) ? $config['tagline'] : '';
$hero_img = !empty($config['hero']) ? $config['hero'] : '';
?>

<style>
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 40px 8%;
    background: #ffffff;
    gap: 40px;
    margin-top: 20px; 
    margin-bottom: 20px;
}

.hero-text {
    flex: 1;
    max-width: 550px;
    text-align: left;
}

.hero-text p:first-child {
    font-size: 18px;
    color: #7a7a7a;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.hero-text h1 {
    font-size: 48px;
    line-height: 1.1;
    margin: 0 0 15px;
    color: #1a1a1a;
    font-weight: 700;
}

.hero-text p:last-child {
    font-size: 19px;
    color: #555;
    line-height: 1.5;
}

.hero img {
    flex: 1;
    max-width: 600px;
    width: 100%;
    height: auto;
    border-radius: 12px;
    object-fit: cover;
}

.marquee-container {
  padding: 10px 0 30px 0;
  background-color: #fff;
  text-align: center;
}

.marquee-title {
  color: #bbb;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 1.5px;
  margin-bottom: 25px;
  text-transform: uppercase;
}

.marquee {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.marquee-track {
  display: flex;
  width: max-content;
  animation: marquee 50s linear infinite;
}

.marquee-track:hover {
  animation-play-state: paused;
}

.marquee-group {
  display: flex;
  gap: 80px;
  align-items: center;
  padding-right: 80px;
}

.marquee-group img {
  height: 65px;
  width: auto;
  max-width: 180px;
  flex-shrink: 0;
  object-fit: contain;
  filter: grayscale(100%);
  opacity: 0.5;
  transition: all 0.3s ease;
  display: block;
}

.marquee-group img:hover {
  filter: grayscale(0%);
  opacity: 1;
  transform: scale(1.05);
}

@keyframes marquee {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

@media (max-width: 900px) {
    .hero {
        flex-direction: column;
        text-align: center;
        padding: 30px 5%;
    }
    .hero-text {
        max-width: 100%;
        margin-bottom: 20px;
    }
    .hero-text p:first-child { justify-content: center; }
    .hero-text h1 { font-size: 36px; }
    .marquee-group { gap: 40px; padding-right: 40px; }
    .marquee-group img { height: 45px; }
}
</style>

<div class="hero">
    <div class="hero-text">
        <?php if ($intro): ?>
        <p>
            <?php echo $intro; ?>
            <span>➜</span>
        </p>
        <?php endif; ?>
        
        <?php if ($heading): ?>
        <h1><?php echo $heading; ?></h1>
        <?php endif; ?>
        
        <?php if ($tagline): ?>
        <p><?php echo $tagline; ?></p>
        <?php endif; ?>
    </div>
    <?php if ($hero_img): ?>
    <img src="<?php echo $hero_img; ?>" alt="Hero Section">
    <?php endif; ?>
</div>

<div class="marquee-container">
  <h4 class="marquee-title">Our Partner</h4>
  <div class="marquee">
    <div class="marquee-track">
      <?php for ($i = 0; $i < 4; $i++): ?>
        <div class="marquee-group">
          <?php if (!empty($output['images'])): ?>
            <?php foreach ($output['images'] as $dt): ?>
              <?php if (!empty($dt['link'])): ?>
                <a href="<?= $dt['link'] ?>" title="<?= $dt['title'] ?>" style="display: block;">
              <?php endif; ?>
              <img src="<?= $dt['image'] ?>" alt="<?= $dt['title'] ?>" title="<?= $dt['title'] ?>">
              <?php if (!empty($dt['link'])): ?>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</div>