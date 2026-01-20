<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
	.alumni-section {
		padding: 50px 0;
		font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
		background-color: #ffffff;
	}
	.alumni-container {
		max-width: 1200px;
		margin: 0 auto;
		padding: 0 15px;
	}
	.alumni-title {
		font-size: 28px;
		font-weight: 700;
		color: #333;
		margin-bottom: 30px;
	}
	.alumni-grid {
		display: flex;
		overflow-x: auto;
		gap: 20px;
		padding: 10px 5px 30px 5px;
		-webkit-overflow-scrolling: touch;
		scrollbar-width: thin;
		scrollbar-color: #ccc transparent;
	}
	.alumni-grid::-webkit-scrollbar {
		height: 6px;
	}
	.alumni-grid::-webkit-scrollbar-thumb {
		background: #ccc;
		border-radius: 10px;
	}
	.alumni-card {
		flex: 0 0 220px;
		background: #fff;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 4px 15px rgba(0,0,0,0.1);
		transition: transform 0.3s ease;
	}
	.alumni-card:hover {
		transform: translateY(-5px);
	}
	.alumni-img-box {
		width: 100%;
		height: 220px;
		overflow: hidden;
	}
	.alumni-img-box img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
	.alumni-content {
		padding: 15px;
	}
	.alumni-year {
		font-size: 13px;
		color: #666;
		margin-bottom: 4px;
	}
	.alumni-count {
		font-size: 16px;
		font-weight: 700;
		color: #000;
		margin-bottom: 8px;
	}
	.alumni-meta {
		font-size: 11px;
		color: #555;
		display: flex;
		align-items: center;
		gap: 4px;
	}
	.alumni-meta span.star {
		color: #ffc107;
		font-size: 14px;
	}
</style>

<div class="alumni-section">
	<div class="alumni-container">
		<h2 class="alumni-title"><?php echo !empty($config['heading']) ? $config['heading'] : ''; ?></h2>
		<div class="alumni-grid">
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
							<img src="<?php echo $img; ?>" alt="Alumni <?php echo $year; ?>">
						</div>
						<div class="alumni-content">
							<div class="alumni-year"><?php echo $year; ?></div>
							<div class="alumni-count"><?php echo $count; ?></div>
							<div class="alumni-meta">
								<span class="star">★</span> <?php echo $rating; ?> | <?php echo $text; ?>
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