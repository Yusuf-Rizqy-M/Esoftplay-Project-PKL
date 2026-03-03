<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .client-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        padding: 20px;
        max-width: 1100px;
        margin: 0 auto;
        font-family: 'Poppins', sans-serif;
    }

    .client-card {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .client-logo-top {
        width: 45px;
        height: 45px;
        margin-bottom: 12px;
        object-fit: contain;
    }

    .client-media {
        position: relative;
        width: 100%;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        line-height: 0;
    }

    .client-img {
        width: 100%;
        height: auto;
        display: block;
    }

    .client-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    .client-media:hover .client-overlay {
        opacity: 1;
    }

    .circle-btn {
        width: 45px;
        height: 45px;
        border: 2px solid #ffc107;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.3s;
        background: transparent;
        cursor: pointer;
        padding: 0;
    }

    .circle-btn svg {
        width: 22px;
        height: 22px;
        fill: #ffc107;
    }

    .circle-btn:hover {
        background: #ffc107;
    }

    .circle-btn:hover svg {
        fill: #000;
    }

    .client-label {
        width: 100%;
        background-color: #ffc107;
        padding: 12px 5px;
        text-align: center;
        font-weight: 400; 
        font-size: 13px;
        color: #333;
        text-transform: uppercase;
        line-height: 1.2;
    }

    #zoomModal {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* BACKGROUND DIUBAH JADI TRANSPARAN */
        background: transparent; 
        /* Jika ingin sedikit gelap tapi tetap transparan, gunakan: background: rgba(0,0,0,0.3); */
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    #zoomModal.active {
        display: flex;
        opacity: 1;
    }

    #zoomModal img {
        width: 90%;
        max-width: 1100px;
        height: auto;
        max-height: 85vh;
        object-fit: contain;
        border-radius: 8px;
        /* Menambahkan shadow lebih kuat agar gambar tetap menonjol di background transparan */
        box-shadow: 0 5px 30px rgba(0,0,0,0.3); 
        transform: scale(0.8);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    #zoomModal.active img {
        transform: scale(1);
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 30px;
        color: #ffc107;
        font-size: 50px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10001;
        line-height: 1;
        /* Menambahkan text-shadow agar tombol close tetap terlihat jelas */
        text-shadow: 0 2px 5px rgba(0,0,0,0.5);
    }

    @media (max-width: 900px) {
        .client-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .client-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="client-grid">
    <?php 
    for ($i = 1; $i <= 6; $i++) { 
        $logo  = $config['item'.$i.'_logo'];
        $image = $config['item'.$i.'_image'];
        $title = $config['item'.$i.'_title'];
        $link  = $config['item'.$i.'_link'];
        
        if (empty($image)) continue;
    ?>
    <div class="client-card">
        <img src="<?php echo $logo; ?>" class="client-logo-top">
        
        <div class="client-media">
            <img src="<?php echo $image; ?>" class="client-img">
            
            <div class="client-overlay">
                <button class="circle-btn" onclick="openZoom('<?php echo $image; ?>')">
                    <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                </button>
                <a href="<?php echo $link; ?>" target="_blank" class="circle-btn">
                    <svg viewBox="0 0 24 24"><path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/></svg>
                </a>
            </div>

            <div class="client-label">
                <?php echo $title; ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<div id="zoomModal" onclick="closeZoom()">
    <span class="close-modal">&times;</span>
    <img id="modalImg" src="" onclick="event.stopPropagation()">
</div>

<script>
    function openZoom(imgSrc) {
        const modal = document.getElementById('zoomModal');
        const modalImg = document.getElementById('modalImg');
        modalImg.src = imgSrc;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeZoom() {
        const modal = document.getElementById('zoomModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
</script>