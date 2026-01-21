<?php
link_js('includes/lib/pea/includes/formIsRequire.js', false);
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Menghilangkan margin bawaan tema dan scroll horizontal */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden !important;
        width: 100%;
        height: 100%;
        font-family: 'Poppins', sans-serif;
    }

    /* TEKNIK FORCED FULLSCREEN: Keluar dari container induk */
    .master-login-wrapper {
        position: fixed; /* Mengunci posisi agar menutupi seluruh layar */
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        display: flex;
        z-index: 999999; /* Pastikan di atas elemen apapun */
        background: #fff;
    }

    /* BAGIAN KIRI: GAMBAR */
    .login-side-image {
        flex: 1;
        height: 100%;
        position: relative;
        background: url('http://localhost/pkl_project_esoftplay/images/uploads/asset/Rectangle%2067.png?KeepThis=true&TB_iframe=true&height=430&width=700') no-repeat center center;
        background-size: cover;
    }

    /* Logo di dalam gambar */
    .side-logo-container {
        position: absolute;
        top: 30px;
        left: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px 15px;
        border-radius: 8px;
        backdrop-filter: blur(5px);
        text-decoration: none;
    }

    .side-logo-container img {
        height: 28px;
    }

    .side-logo-container span {
        color: #fff;
        font-weight: 500;
        font-size: 16px;
    }

    /* BAGIAN KANAN: FORM */
    .login-side-form {
        flex: 1;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px;
        background: #fff;
    }

    .form-box {
        width: 100%;
        max-width: 420px;
    }

    .form-header h2 {
        font-size: 32px;
        font-weight: 600;
        color: #2D3748;
        margin: 0 0 10px 0;
    }

    .form-header p {
        color: #718096;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .dots-decor {
        font-size: 24px;
        color: #CBD5E0;
        letter-spacing: 4px;
        margin-bottom: 40px;
    }

    /* STYLING INPUT */
    .custom-group {
        margin-bottom: 25px;
    }

    .custom-group label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #4A5568;
        margin-bottom: 10px;
    }

    .input-field-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-field-wrapper i.prefix-icon {
        position: absolute;
        left: 18px;
        color: #5874f6;
        font-size: 18px;
    }

    .input-field-wrapper i.suffix-icon {
        position: absolute;
        right: 18px;
        color: #A0AEC0;
        cursor: pointer;
        transition: 0.3s;
    }

    .input-field-wrapper i.suffix-icon:hover {
        color: #5874f6;
    }

    .form-input-pro {
        width: 100%;
        padding: 15px 50px;
        background: #F7FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .form-input-pro:focus {
        outline: none;
        border-color: #5874f6;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(88, 116, 246, 0.1);
    }

    /* BUTTON */
    .btn-submit-pro {
        width: 100%;
        padding: 16px;
        background: #5874f6;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        box-shadow: 0 10px 15px -3px rgba(88, 116, 246, 0.3);
    }

    .btn-submit-pro:hover {
        background: #4661e0;
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(88, 116, 246, 0.4);
    }

    .form-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 14px;
        color: #718096;
    }

    .form-footer a {
        color: #2D3748;
        text-decoration: none;
        font-weight: 600;
        font-style: italic;
    }

    .copy-text {
        position: absolute;
        bottom: 30px;
        font-size: 12px;
        color: #A0AEC0;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .login-side-image { display: none; }
        .login-side-form { flex: 1; padding: 30px; }
    }
</style>

<div class="master-login-wrapper">
    <div class="login-side-image">
        <a href="#" class="side-logo-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRBdJBSF1ii-xU_NREaLsWhKxQ6soWaNgWE3A&s" alt="Logo">
            <span>Esoftplay Internship</span>
        </a>
    </div>

    <div class="login-side-form">
        <div class="form-box">
            <div class="form-header">
                <h2>Selamat datang kembali</h2>
                <p>Mohon masukkan email dan password untuk akses</p>
                <div class="dots-decor">...</div>
            </div>

            <form class="formIsRequire" method="POST" action="">
                <div class="custom-group">
                    <label>Username</label>
                    <div class="input-field-wrapper">
                        <i class="fa-regular fa-envelope prefix-icon"></i>
                        <input class="form-input-pro" placeholder="you@example.com" req="any true" autofocus="" type="text" name="usr" />
                    </div>
                </div>

                <div class="custom-group">
                    <label>Password</label>
                    <div class="input-field-wrapper">
                        <i class="fa-solid fa-lock prefix-icon"></i>
                        <input class="form-input-pro" placeholder="At least 8 characters" req="any true" type="password" name="pwd" id="passInput" />
                        <i class="fa-regular fa-eye suffix-icon" id="togglePass"></i>
                    </div>
                </div>

                <input type="hidden" name="url" value="<?php echo $user_url; ?>" />
                
                <button class="btn-submit-pro" type="submit">Login</button>
            </form>

            <div class="form-footer">
                Tidak punya akun? <a href="#">daftar sekarang</a>
            </div>
        </div>

        <div class="copy-text">
            &copy; 2025 Esoftplay &mdash; Proyek PKL. Semua hak cipta dilindungi.
        </div>
    </div>
</div>

<script>
    // Fungsi Toggle Intip Password
    const togglePass = document.querySelector('#togglePass');
    const password = document.querySelector('#passInput');

    togglePass.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>