<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    .cta-career-section {
        background-color: #ffffff;
        color: #333333;
        padding: 80px 20px;
        text-align: center;
        font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
    }

    .cta-career-container {
        max-width: 850px;
        margin: 0 auto;
    }

    .cta-career-title {
        font-size: 36px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
        color: #FFB300;
        letter-spacing: -1px;
    }

    .cta-career-desc {
        font-size: 17px;
        line-height: 1.7;
        margin-bottom: 40px;
        color: #555555;
        max-width: 720px;
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
        padding: 14px 34px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-apply {
        background-color: #FFB300;
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(255, 179, 0, 0.35);
    }

    .btn-apply:hover {
        background-color: #FFB300;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(230, 81, 0, 0.4);
        color: #ffffff;
    }

    /* BAGIAN YANG DIUBAH WARNANYA */
    .btn-talk {
        background-color: transparent !important;
        color: #FFB300 !important;
        border: 2px solid #FFB300 !important;
    }

    .btn-talk:hover {
        background-color: rgba(230, 81, 0, 0.08) !important;
        color: #FFB300 !important;
        transform: translateY(-3px) !important;
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
            max-width: 280px;
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