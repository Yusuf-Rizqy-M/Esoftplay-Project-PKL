<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .cta-career-section {
        background-color: #001d57;
        color: #ffffff;
        padding: 80px 20px;
        text-align: center;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .cta-career-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .cta-career-title {
        font-size: 36px;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 30px;
    }

    .cta-career-desc {
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 40px;
        color: #e0e0e0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-career-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-career {
        display: inline-block;
        padding: 12px 30px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-apply {
        background-color: #ffffff;
        color: #001d57;
    }

    .btn-apply:hover {
        background-color: #f0f0f0;
        transform: translateY(-2px);
    }

    .btn-talk {
        background-color: transparent;
        color: #ffffff;
        border: 2px solid #ffffff;
    }

    .btn-talk:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .cta-career-title {
            font-size: 28px;
        }
        
        .cta-career-section {
            padding: 60px 15px;
        }

        .cta-career-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn-career {
            width: 100%;
            max-width: 250px;
        }
    }
</style>

<div class="cta-career-section">
    <div class="cta-career-container">
        <h2 class="cta-career-title">
            <?php echo !empty($config['heading']) ? $config['heading'] : 'Ready to Start Your Career Journey with Esoftplay?'; ?>
        </h2>
        
        <p class="cta-career-desc">
            <?php echo !empty($config['description']) ? $config['description'] : 'Join the Esoftplay Internship Program and gain hands-on experience through real-world projects, professional mentorship, and a supportive learning environment designed to prepare you for the industry.'; ?>
        </p>

        <div class="cta-career-actions">
            <a href="<?php echo !empty($config['link_apply']) ? $config['link_apply'] : '#'; ?>" class="btn-career btn-apply">
                Apply for Internship
            </a>
            <a href="<?php echo !empty($config['link_talk']) ? $config['link_talk'] : '#'; ?>" class="btn-career btn-talk">
                Talk to Our Team
            </a>
        </div>
    </div>
</div>