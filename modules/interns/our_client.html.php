<?php
if (!defined('_VALID_BBC')) exit('No direct script access allowed');

$client_h      = "Our Clients";
$client_t      = "Kami membantu bisnis anda mencapai pertumbuhan dan engagement yang berkelanjutan. Kami bermitra dengan perusahaan untuk memberikan hasil terbaik di dunia industri.";
$hero_h        = "The business software for design agencies";
$hero_t        = "Aplikasi sistem kami membantu mengelola manajemen, absensi, tugas harian, dan laporan para peserta magang di lingkungan industri dengan lebih efektif.";
$btn_p         = "Book a demo";
$btn_s         = "Contact sales";
$rating_s      = "4.8";
$rating_c      = "2k+ reviews of people";

$c1_logo = "http://localhost/pkl_project_esoftplay/images/uploads/asset/image%2024.png?KeepThis=true&TB_iframe=true&height=430&width=700";
$c1_img  = "http://localhost/pkl_project_esoftplay/images/uploads/asset/image%2028.png?KeepThis=true&TB_iframe=true&height=430&width=700";
$c1_name = "BBO";
$c1_link = "https://bbo.co.id/";

$c2_logo = $c1_logo; $c2_img = $c1_img; $c2_name = "CLIENT NAME 2"; $c2_link = "#";
$c3_logo = $c1_logo; $c3_img = $c1_img; $c3_name = "CLIENT NAME 3"; $c3_link = "#";
$c4_logo = $c1_logo; $c4_img = $c1_img; $c4_name = "CLIENT NAME 4"; $c4_link = "#";
$c5_logo = $c1_logo; $c5_img = $c1_img; $c5_name = "CLIENT NAME 5"; $c5_link = "#";
$c6_logo = $c1_logo; $c6_img = $c1_img; $c6_name = "CLIENT NAME 6"; $c6_link = "#";

$hero_img_url = "http://localhost/pkl_project_esoftplay/images/uploads/asset/image%2046.png?KeepThis=true&TB_iframe=true&height=430&width=700";
$bottom_img_url = "http://localhost/pkl_project_esoftplay/images/uploads/asset/assetkantor.png?KeepThis=true&TB_iframe=true&height=430&width=700";
?>

<section class="client-section">
  <h1><?php echo $client_h; ?></h1>
  <p><?php echo $client_t; ?></p>
</section>

<section class="client-grid">
  <div class="client-card">
    <img src="<?php echo $c1_logo; ?>" class="client-logo-top">
    <div class="client-media">
      <img src="<?php echo $c1_img; ?>" class="client-img">
      <div class="client-overlay">
        <button class="circle-btn" onclick="openZoom('<?php echo $c1_img; ?>')">
          <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
        </button>
        <a href="<?php echo $c1_link; ?>" target="_blank" class="circle-btn">
          <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>
        </a>
      </div>
      <div class="client-label"><?php echo $c1_name; ?></div>
    </div>
  </div>

  <div class="client-card">
    <img src="<?php echo $c2_logo; ?>" class="client-logo-top">
    <div class="client-media">
      <img src="<?php echo $c2_img; ?>" class="client-img">
      <div class="client-overlay">
        <button class="circle-btn" onclick="openZoom('<?php echo $c2_img; ?>')">
          <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
        </button>
        <a href="<?php echo $c2_link; ?>" target="_blank" class="circle-btn">
          <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>
        </a>
      </div>
      <div class="client-label"><?php echo $c2_name; ?></div>
    </div>
  </div>

  <div class="client-card">
    <img src="<?php echo $c3_logo; ?>" class="client-logo-top">
    <div class="client-media">
      <img src="<?php echo $c3_img; ?>" class="client-img">
      <div class="client-overlay">
        <button class="circle-btn" onclick="openZoom('<?php echo $c3_img; ?>')">
          <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
        </button>
        <a href="<?php echo $c3_link; ?>" target="_blank" class="circle-btn">
          <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>
        </a>
      </div>
      <div class="client-label"><?php echo $c3_name; ?></div>
    </div>
  </div>

  <div class="client-card">
    <img src="<?php echo $c4_logo; ?>" class="client-logo-top">
    <div class="client-media">
      <img src="<?php echo $c4_img; ?>" class="client-img">
      <div class="client-overlay">
        <button class="circle-btn" onclick="openZoom('<?php echo $c4_img; ?>')">
          <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
        </button>
        <a href="<?php echo $c4_link; ?>" target="_blank" class="circle-btn">
          <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>
        </a>
      </div>
      <div class="client-label"><?php echo $c4_name; ?></div>
    </div>
  </div>

  <div class="client-card">
    <img src="<?php echo $c5_logo; ?>" class="client-logo-top">
    <div class="client-media">
      <img src="<?php echo $c5_img; ?>" class="client-img">
      <div class="client-overlay">
        <button class="circle-btn" onclick="openZoom('<?php echo $c5_img; ?>')">
          <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
        </button>
        <a href="<?php echo $c5_link; ?>" target="_blank" class="circle-btn">
          <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>
        </a>
      </div>
      <div class="client-label"><?php echo $c5_name; ?></div>
    </div>
  </div>

  <div class="client-card">
    <img src="<?php echo $c6_logo; ?>" class="client-logo-top">
    <div class="client-media">
      <img src="<?php echo $c6_img; ?>" class="client-img">
      <div class="client-overlay">
        <button class="circle-btn" onclick="openZoom('<?php echo $c6_img; ?>')">
          <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" /></svg>
        </button>
        <a href="<?php echo $c6_link; ?>" target="_blank" class="circle-btn">
          <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z" /></svg>
        </a>
      </div>
      <div class="client-label"><?php echo $c6_name; ?></div>
    </div>
  </div>
</section>

<section class="hero-container">
  <div class="hero-content">
    <h1><?php echo $hero_h; ?></h1>
    <p><?php echo $hero_t; ?></p>
    <div class="btn-group">
      <a href="#" class="btn-main btn-black"><?php echo $btn_p; ?></a>
      <a href="#" class="btn-main btn-outline"><?php echo $btn_s; ?></a>
    </div>
    <div class="rating-section">
      <strong><?php echo $rating_s; ?></strong>
      <span class="star-icon">★</span>
      <span><?php echo $rating_c; ?></span>
    </div>
  </div>
  <div class="hero-visual">
    <img src="<?php echo $hero_img_url; ?>" alt="">
  </div>
</section>

<section class="sw-timeline-section">
  <div class="sw-container">
    <div class="sw-line"></div>
    
    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">1</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">2</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">3</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">4</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">5</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">6</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">7</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">8</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">9</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

    <div class="sw-row">
      <div class="sw-col-text">
        <div class="sw-title1">Esoftplay History</div>
        <div class="sw-title2">Sistem Terintegrasi</div>
        <div class="sw-desc">Aplikasi ini memudahkan manajemen absensi, tugas, dan laporan harian secara real-time untuk efisiensi maksimal.</div>
      </div>
      <div class="sw-col-center"><div class="sw-number">10</div></div>
      <div class="sw-col-img"><div class="sw-img-box"><img src="<?php echo $bottom_img_url; ?>" alt=""></div></div>
    </div>

  </div>
</section>

<div id="zoomModal" onclick="closeZoom()">
  <span class="close-modal">&times;</span>
  <img id="modalImg" src="" onclick="event.stopPropagation()">
</div>

<script>
function openZoom(src) {
  if(src !== "") {
    document.getElementById("modalImg").src = src;
    document.getElementById("zoomModal").style.display = "flex";
  }
}
function closeZoom() {
  document.getElementById("zoomModal").style.display = "none";
}
</script>