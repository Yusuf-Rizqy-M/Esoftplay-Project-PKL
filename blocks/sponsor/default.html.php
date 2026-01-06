<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>
<style>
.marquee {
  width: 100vw;
  overflow: hidden;
  padding: 20px 0;
}

.marquee-track {
  display: flex;
  width: max-content;
  animation: marquee 38s linear infinite;
}

.marquee-group {
  display: flex;
  gap: 80px;
  align-items: center;
}

.marquee-group img {
  height: 40px;
  flex-shrink: 0;
  object-fit: contain;
}

@keyframes marquee {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}
</style>

<div class="marquee">
  <div class="marquee-track">
    <?php for ($i = 0; $i < 4; $i++): ?>
      <div class="marquee-group">
        <?php foreach ($output['images'] as $dt): ?>
          <?php if (!empty($dt['link'])): ?>
            <a href="<?= $dt['link'] ?>" title="<?= $dt['title'] ?>">
          <?php endif; ?>
          <img src="<?= $dt['image'] ?>" alt="<?= $dt['title'] ?>" title="<?= $dt['title'] ?>">
          <?php if (!empty($dt['link'])): ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endfor; ?>
  </div>
</div>
