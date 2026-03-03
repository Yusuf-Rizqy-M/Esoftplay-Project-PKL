<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed');
if (empty($data)) {
}

$intro       = "Bekerja, Belajar, dan Berkembang Bersama.";
$heading     = "Esoftplay — Sistem Internal Pengelolaan Magang";
$tagline     = "Semua aktivitas magang—absensi, tugas harian, dan laporan—terkelola dalam satu sistem.";
$about_tag   = "Tentang Kami";
$about_intro = "Esoftplay team merupakan tim developer aplikasi yang telah berdiri sejak tahun 2014, terletak di Perumahan Muria, Kecamatan Kaliwungu, Kabupaten Kudus.";
$client_tag  = "CLIENT SAYS";
$client_para = "It is very easy to use this site because all of the features are functional and very helpful for people who are internship because we can see activities in the industrial world which is very heavy and maybe for apart of it it is a good placement, doing high active the industrial world in the school because it is about part of it. I feel that it is very helpful for the student study.";
?>
1
<section class="hero">
    <div class="hero-shape h-shape-1"></div>
    <div class="hero-shape h-shape-2"></div>
    <div class="hero-shape h-shape-3"></div>
    <div class="hero-shape h-shape-4"></div>
    <div class="hero-text">
        <p><?php echo $intro ?> <span>➜</span></p>
        <h1 class="h1"><?php echo $heading ?></h1>
        <p><?php echo $tagline ?></p>
    </div>
    <img src="http://localhost/pkl_project_esoftplay/images/uploads/asset/fotolanding.jpg?KeepThis=true&TB_iframe=true&height=430&width=700" alt="">
</section>

<section class="marquee-container">
    <h4 class="marquee-title">Our Partner</h4>
    <div class="marquee">
        <div class="marquee-track">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="marquee-group">
                    <?php if (!empty($output['images'])): ?>
                        <?php foreach ($output['images'] as $dt): ?>
                            <img src="" alt="<?php echo $dt['title'] ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69549b8136c0f.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69549bb760577.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69576cd52e406.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69576d7195d31.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69576d84670ba.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69576d94848ce.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69576dc3c953c.png" alt="Partner">
                        <img src="http://localhost/pkl_project_esoftplay/images/modules/imageslider/69576de4db3db.png" alt="Partner">


                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="about-section-wrapper">
    <div class="about-section">
        <div class="shape shape-square"></div>
        <div class="shape shape-circle"></div>
        <div class="shape shape-triangle"></div>
        <div class="shape shape-star"></div>
        <div class="about-content-container">
            <h1><?php echo $about_tag ?></h1>
            <p><?php echo $about_intro ?></p>
            <div class="about-gallery-wrapper">
                <div class="gallery-grid">
                    <img class="img-left" src="http://localhost/pkl_project_esoftplay/images/uploads/asset/tentangkami1.jpg?KeepThis=true&TB_iframe=true&height=430&width=700" alt="">
                    <img class="img-mid-top" src="http://localhost/pkl_project_esoftplay/images/uploads/asset/tentangkami2.jpg?KeepThis=true&TB_iframe=true&height=430&width=700" alt="">
                    <img class="img-mid-bottom" src="http://localhost/pkl_project_esoftplay/images/uploads/asset/tentangkami3.jpg?KeepThis=true&TB_iframe=true&height=430&width=700" alt="">
                    <img class="img-right" src="http://localhost/pkl_project_esoftplay/images/uploads/asset/tentangkami4.jpg?KeepThis=true&TB_iframe=true&height=430&width=700" alt="">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="client-says-section">
    <div class="bg-image-container">
        <img src="http://localhost/pkl_project_esoftplay/images/uploads/asset/Group%2020.png?KeepThis=true&TB_iframe=true&height=430&width=700" class="bg-slide active">
        <img src="http://localhost/pkl_project_esoftplay/images/uploads/asset/Group%2020.png?KeepThis=true&TB_iframe=true&height=430&width=700" class="bg-slide">
        <img src="" class="bg-slide">
    </div>
    <div class="overlay-dark"></div>
    <div class="client-content">
        <h2 class="client-heading"><?php echo $client_tag ?></h2>
        <div class="styled-quote-mark">“</div>
        <p class="client-paragraph"><?php echo $client_para ?></p>
        <div class="nav-dots">
            <span class="nav-dot active"></span>
            <span class="nav-dot"></span>
            <span class="nav-dot"></span>
        </div>
    </div>
</section>

