<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
/* Container Utama untuk memberikan padding atas bawah */
.laporan-section-wrapper {
    background-color: #ffffff;
    padding: 100px 20px;
    width: 100%;
}

/* Pembatas Konten agar teks & gambar tidak terlalu lebar ke samping (Kanan-Kiri) */
.laporan-container-centered {
    max-width: 1000px; /* Batas lebar konten agar mirip Figma */
    margin: 0 auto;    /* Menaruh seluruh blok di tengah layar */
}

/* Judul Utama */
.laporan-title {
    text-align: center;
    font-size: 36px;
    font-family: 'Inter', sans-serif;
    color: #000;
    margin-bottom: 70px;
    font-weight: 400;
}

.laporan-title b {
    font-weight: 800;
}

/* Layout Flex untuk Teks dan Gambar */
.laporan-content-flex {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 80px; /* Jarak antara teks dan gambar */
}

/* Pengaturan Kolom Teks */
.laporan-text-side {
    flex: 1;
    max-width: 450px; /* Membatasi lebar teks agar rapi seperti di desain */
}

.laporan-text-side p {
    font-size: 18px;
    line-height: 1.6;
    color: #333;
    margin-bottom: 25px;
}

.laporan-text-side .website-label {
    font-size: 18px;
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

/* Pengaturan Kolom Gambar */
.laporan-image-side {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.laporan-image-side img {
    width: 100%;
    max-width: 500px;
    height: auto;
    border-radius: 10px;
}

/* Dots di bawah gambar */
.laporan-dots-nav {
    display: flex;
    gap: 8px;
    margin-top: 20px;
}

.laporan-dots-nav span {
    width: 7px;
    height: 7px;
    border: 1px solid #999;
    border-radius: 50%;
}

/* Responsif untuk Tablet dan HP */
@media (max-width: 992px) {
    .laporan-content-flex {
        flex-direction: column;
        align-items: center;
        gap: 40px;
    }
    .laporan-text-side {
        max-width: 100%;
        text-align: center;
    }
    .laporan-title {
        font-size: 28px;
        margin-bottom: 40px;
    }
}
</style>

<div class="laporan-section-wrapper">
    <div class="laporan-container-centered">
        
        <h2 class="laporan-title"><b>Apa</b> Itu laporan harian?</h2>
        
        <div class="laporan-content-flex">
            
            <div class="laporan-text-side">
                <p>
                    <?php echo !empty($config['intro']) ? $config['intro'] : 'Laporan harian adalah catatan aktivitas yang dibuat setiap hari untuk memantau pekerjaan, perkembangan, serta kendala yang terjadi di lapangan. Melalui laporan harian, setiap kegiatan dapat tercatat secara sistematis sehingga memudahkan proses evaluasi dan pelaporan.'; ?>
                </p>
                <a href="#" class="website-label">Website</a>
            </div>

            <div class="laporan-image-side">
                <img src="<?php echo $config['image1']; ?>" alt="Preview Laporan Harian">
                
                <div class="laporan-dots-nav">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>
    </div>
</div>