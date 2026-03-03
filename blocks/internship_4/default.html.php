
<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .alumni-section {
        padding: 60px 0;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background-color: #ffffff;
    }
    .alumni-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
    }
    .alumni-header {
        margin-bottom: 20px;
    }
    .alumni-title {
        font-size: 24px;
        font-weight: 700;
        color: #2d3436;
        letter-spacing: -0.5px;
    }
    .alumni-wrapper {
        position: relative;
        cursor: grab;
    }
    .alumni-wrapper:active {
        cursor: grabbing;
    }
    .alumni-grid {
        display: flex;
        overflow-x: auto;
        gap: 16px;
        padding: 10px 5px 35px 5px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; 
    }
    .alumni-grid::-webkit-scrollbar {
        display: none; 
    }
    .alumni-card {
        flex: 0 0 190px;
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        user-select: none;
    }
    .alumni-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.08);
    }
    .alumni-img-box {
        width: 100%;
        height: 190px;
        overflow: hidden;
        pointer-events: none;
    }
    .alumni-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .alumni-content {
        padding: 10px 14px;
    }
    .alumni-year {
        font-size: 11px;
        font-weight: 500;
        color: #888;
        margin-bottom: 0px; /* Merapatkan jarak ke judul */
    }
    .alumni-count {
        font-size: 15px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 4px; /* Merapatkan jarak ke garis bawah */
        line-height: 1.2;
    }
    .alumni-meta {
        font-size: 11px;
        color: #666;
        display: flex;
        align-items: center;
        gap: 4px;
        border-top: 1px solid #f5f5f5;
        padding-top: 5px; /* Merapatkan jarak dari garis ke teks rating */
    }
    .star { color: #ffb300; font-size: 12px; }

    @media (max-width: 768px) {
        .alumni-card { flex: 0 0 170px; }
        .alumni-img-box { height: 170px; }
    }
</style>

<div class="alumni-section">
    <div class="alumni-container">
        <div class="alumni-header">
            <h2 class="alumni-title"><?php echo !empty($config['heading']) ? $config['heading'] : 'Alumni PKL Esoftplay'; ?></h2>
        </div>
        
        <div class="alumni-wrapper" id="dragWrapper">
            <div class="alumni-grid" id="alumniGrid">
                <?php
                if (!empty($config['items'])) {
                    $items = explode("\n", str_replace("\r", "", $config['items']));
                    foreach ($items as $line) {
                        if (empty(trim($line))) continue;
                        $data = explode('|', $line);
                        $img    = isset($data[0]) ? trim($data[0]) : '';
                        $year   = isset($data[1]) ? trim($data[1]) : '';
                        $count  = isset($data[2]) ? trim($data[2]) : '';
                        $rating = isset($data[3]) ? trim($data[3]) : '';
                        $text   = isset($data[4]) ? trim($data[4]) : '';
                        ?>
                        <div class="alumni-card">
                            <div class="alumni-img-box">
                                <img src="<?php echo $img; ?>" alt="Alumni">
                            </div>
                            <div class="alumni-content">
                                <div class="alumni-year"><?php echo $year; ?></div>
                                <div class="alumni-count"><?php echo $count; ?></div>
                                <div class="alumni-meta">
                                    <span class="star">★</span>
                                    <span><strong><?php echo $rating; ?></strong> | <?php echo $text; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    const grid = document.getElementById('alumniGrid');
    const wrapper = document.getElementById('dragWrapper');
    
    let isDown = false;
    let startX;
    let scrollLeft;

    wrapper.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - grid.offsetLeft;
        scrollLeft = grid.scrollLeft;
        grid.style.scrollBehavior = 'auto';
    });
    wrapper.addEventListener('mouseleave', () => { isDown = false; });
    wrapper.addEventListener('mouseup', () => { 
        isDown = false; 
        grid.style.scrollBehavior = 'smooth';
    });
    wrapper.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - grid.offsetLeft;
        const walk = (x - startX) * 2;
        grid.scrollLeft = scrollLeft - walk;
    });
</script>