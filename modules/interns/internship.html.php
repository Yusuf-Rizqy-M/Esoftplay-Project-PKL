<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); 

// Link ke CSS dan JS (Sesuaikan path folder Anda)
echo '<link rel="stylesheet" href="path/to/style.css">';
echo '<script src="path/to/script.js" defer></script>';

// Data Logic
$hero_i = "Registration is Now Open!";
$hero_h = "Our Internship";
$hero_t = "This is a golden opportunity for those looking to develop their skills and learn directly in a professional environment.";

$features = [
    ['icon' => 'https://cdn-icons-png.flaticon.com/512/1063/1063376.png', 'title' => 'Real-World Project', 'desc' => 'Work on real industry projects and gain hands-on experience.'],
    ['icon' => 'https://cdn-icons-png.flaticon.com/512/3135/3135810.png', 'title' => 'Skill Learning', 'desc' => 'Focus on practical skills that the industry actually needs.'],
    ['icon' => 'https://cdn-icons-png.flaticon.com/512/609/609103.png', 'title' => 'Mentorship', 'desc' => 'Direct guidance from professional developers and leaders.']
];
?>

<section class="hero-section">
    <svg class="deco-shape shape-square" viewBox="0 0 100 100"><rect width="100" height="100" rx="15" /></svg>
    <div class="hero-text">
        <div class="hero-intro"><?php echo $hero_i; ?></div>
        <h1><?php echo $hero_h; ?></h1>
        <p class="hero-description"><?php echo $hero_t; ?></p>
    </div>
    <div class="hero-image">
        <div class="image-container">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1000&auto=format&fit=crop" alt="Internship">
        </div>
    </div>
</section>

<section class="internship-section">
    <div class="feature-grid">
        <?php foreach ($features as $f): ?>
        <div class="feature-card">
            <div class="icon-wrapper"><img src="<?php echo $f['icon']; ?>" alt="icon" style="width: 50px;"></div>
            <h3><?php echo $f['title']; ?></h3>
            <p><?php echo $f['desc']; ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="alumni-section">
    <div class="alumni-container">
        <h2 class="alumni-title">Alumni PKL Esoftplay</h2>
        <div class="alumni-grid" id="alumniGrid">
            <?php 
            $alumni = [['year' => '2024', 'count' => '6 Orang', 'school' => 'Polines'], ['year' => '2023', 'count' => '5 Orang', 'school' => 'UMK']];
            foreach ($alumni as $a): ?>
            <div class="alumni-card">
                <div class="alumni-img-box"><img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400" alt="alumni"></div>
                <div class="alumni-content">
                    <div class="alumni-year"><?php echo $a['year']; ?></div>
                    <div class="alumni-count"><?php echo $a['count']; ?></div>
                    <div class="alumni-meta"><span class="star">★</span> <?php echo $a['school']; ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="cta-career-section">
    <div class="cta-career-container">
        <h2 class="cta-career-title">
            <?php echo !empty($config['heading']) ? $config['heading'] : 'Ready to Start Your Career Journey with Esoftplay?'; ?>
        </h2>
        <p class="cta-career-desc">
            <?php echo !empty($config['description']) ? $config['description'] : 'Join the Esoftplay Internship Program and gain hands-on experience through real-world projects.'; ?>
        </p>
        <div class="cta-career-actions">
            <a href="<?php echo !empty($config['link_apply']) ? $config['link_apply'] : '#'; ?>" class="btn-career btn-apply">Apply for Internship</a>
            <a href="<?php echo !empty($config['link_talk']) ? $config['link_talk'] : '#'; ?>" class="btn-career btn-talk">Talk to Our Team</a>
        </div>
    </div>
</div>