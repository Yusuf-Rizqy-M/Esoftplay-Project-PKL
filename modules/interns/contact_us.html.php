<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<link rel="stylesheet" href="contact-style.css">
<script src="contact-script.js" defer></script>

<section class="contact-wrapper">
    <div class="contact-header">
        <h2><?php echo !empty($config['title']) ? $config['title'] : 'Contact Us'; ?></h2>
        <p><?php echo !empty($config['subtitle']) ? $config['subtitle'] : 'Any question or remarks? Just write us a message!'; ?></p>
    </div>

    <div class="contact-card-container">
        <div class="contact-info-panel">
            <h3>Contact Information</h3>
            <p class="sub-text">Get in touch with us! We'd love to hear from you.</p>

            <div class="info-detail">
                <span><svg fill="white" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg></span>
                <p><?php echo !empty($config['phone']) ? $config['phone'] : '+62 8xx xxxx xxxx'; ?></p>
            </div>
            
            <div class="info-detail">
                <span><svg fill="white" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
                <p><?php echo !empty($config['email']) ? $config['email'] : 'info@esoftplay.com'; ?></p>
            </div>
            
            <div class="info-detail">
                <span><svg fill="white" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></span>
                <p><?php echo !empty($config['address']) ? nl2br($config['address']) : 'Alamat kantor belum diatur.'; ?></p>
            </div>

            <div class="circle-1"></div>
            <div class="circle-2"></div>
        </div>

        <div class="contact-form-panel">
            <form action="" method="POST">
                <div class="contact-grid">
                    <div class="field-group">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="field-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="field-group">
                        <label>Address</label>
                        <input type="text" name="user_address" placeholder="Your Address">
                    </div>
                    <div class="field-group">
                        <label>Phone Number</label>
                        <input type="text" name="user_phone" placeholder="+62 ...">
                    </div>
                    <div class="field-group full-row">
                        <label>Message</label>
                        <textarea name="message" rows="1" placeholder="Write your message here..." required></textarea>
                    </div>
                </div>

                <div class="footer-form">
                    <button type="submit" class="btn-kirim">Send Message</button>
                </div>
            </form>

            <div class="plane-deco">
                <svg viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 120C40 100 80 110 120 70C150 45 180 30 195 20" stroke="#FFB300" stroke-width="1.5" stroke-dasharray="6 6" opacity="0.6"/>
                    <g transform="translate(185, 10) rotate(-10)">
                        <path d="M0 0L15 6L0 9L3 4.5L0 0Z" fill="#FFB300"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</section>