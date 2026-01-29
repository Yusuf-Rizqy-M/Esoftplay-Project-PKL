<?php if (!defined('_VALID_BBC')) exit('No direct script access allowed'); ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .contact-wrapper {
        background-color: #ffffff;
        padding: 80px 0;
        font-family: 'Inter', sans-serif;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .contact-header h2 {
        color: #FFB300;
        font-size: 38px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .contact-header p {
        color: #717171;
        font-size: 16px;
        font-weight: 500;
    }

    .contact-card-container {
        max-width: 1050px;
        margin: 0 auto;
        background-color: #ffffff; 
        border-radius: 25px;
        display: flex;
        padding: 10px; 
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    }

    .contact-info-panel {
        background-color: #0d1b2a; 
        color: #ffffff;
        padding: 50px 40px;
        width: 40%;
        border-radius: 20px; 
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        z-index: 2;
    }

    .contact-info-panel h3 { font-size: 28px; margin-bottom: 10px; font-weight: 600; }
    .contact-info-panel p.sub-text { color: #C9C9C9; margin-bottom: 60px; font-size: 15px; }

    .info-detail { display: flex; align-items: center; margin-bottom: 40px; gap: 20px; }
    .info-detail span svg { width: 20px; height: 20px; }
    .info-detail p { margin: 0; font-size: 14px; line-height: 1.5; color: #ffffff; }

    .circle-1 { position: absolute; bottom: -70px; right: -50px; width: 200px; height: 200px; background: rgba(255, 255, 255, 0.08); border-radius: 50%; }
    .circle-2 { position: absolute; bottom: 30px; right: 40px; width: 110px; height: 110px; background: rgba(255, 255, 255, 0.12); border-radius: 50%; }

    .contact-form-panel {
        width: 60%;
        padding: 50px 60px;
        background-color: transparent;
        position: relative;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 40px;
        row-gap: 40px;
    }

    .field-group {
        border-bottom: 1.5px solid #8D8D8D; 
        padding-bottom: 5px;
    }

    .field-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #8D8D8D;
        margin-bottom: 8px;
    }

    .field-group input, .field-group textarea {
        width: 100%;
        border: none;
        background: transparent;
        outline: none;
        padding: 5px 0;
        font-size: 14px;
        color: #333;
    }

    .field-group textarea { resize: none; margin-top: 10px; }
    .full-row { grid-column: span 2; }

    .footer-form {
        display: flex;
        justify-content: flex-end;
        margin-top: 50px;
    }

    .btn-kirim {
        background-color: #0d1b2a;
        color: #ffffff;
        border: none;
        padding: 14px 40px;
        border-radius: 5px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: 0.3s ease;
    }

    .btn-kirim:hover { background-color: #1a2e44; }

    .plane-deco {
        position: absolute;
        bottom: -30px; 
        right: 120px;
        width: 180px;
        pointer-events: none;
        z-index: 10;
    }

    @media (max-width: 900px) {
        .contact-card-container { flex-direction: column; margin: 0 15px; }
        .contact-info-panel, .contact-form-panel { width: 100%; padding: 30px; }
        .contact-grid { grid-template-columns: 1fr; }
        .plane-deco { display: none; }
    }
</style>

<section class="contact-wrapper">
    <div class="contact-header">
        <h2><?php echo $config['title']; ?></h2>
        <p><?php echo $config['subtitle']; ?></p>
    </div>

    <div class="contact-card-container">
        <div class="contact-info-panel">
            <h3>Contact Information</h3>
            <p class="sub-text">Get in touch with us! We'd love to hear from you.</p>

            <div class="info-detail">
                <span><svg fill="white" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg></span>
                <p><?php echo $config['phone']; ?></p>
            </div>
            <div class="info-detail">
                <span><svg fill="white" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg></span>
                <p><?php echo $config['email']; ?></p>
            </div>
            <div class="info-detail">
                <span><svg fill="white" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></span>
                <p><?php echo nl2br($config['address']); ?></p>
            </div>

            <div class="circle-1"></div>
            <div class="circle-2"></div>
        </div>

        <div class="contact-form-panel">
            <form action="" method="POST">
                <div class="contact-grid">
                    <div class="field-group">
                        <label>Name</label>
                        <input type="text" name="name" placeholder="Enter your name">
                    </div>
                    <div class="field-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your email">
                    </div>
                    <div class="field-group">
                        <label>Address</label>
                        <input type="text" name="address" placeholder="Enter your address">
                    </div>
                    <div class="field-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" placeholder="+62 ...">
                    </div>
                    <div class="field-group full-row">
                        <label>Message</label>
                        <textarea name="message" rows="1" placeholder="Write your message here..."></textarea>
                    </div>
                </div>

                <div class="footer-form">
                    <button type="submit" class="btn-kirim">Send Message</button>
                </div>
            </form>

            <div class="plane-deco">
                <svg viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 120C40 100 80 110 120 70C150 45 180 30 195 20" stroke="#333" stroke-width="1.5" stroke-dasharray="6 6" opacity="0.4"/>
                    <g transform="translate(185, 10) rotate(-10)">
                        <path d="M0 0L15 6L0 9L3 4.5L0 0Z" fill="#333"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</section>