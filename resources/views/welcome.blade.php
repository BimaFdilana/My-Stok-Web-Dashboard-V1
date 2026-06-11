<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Stock - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* Base Reset & Background Setup */
        body {
            position: relative;
            background: url('{{ asset('img/polbeanss.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            color: #1e293b; 
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* PERUBAHAN: Warna biru dihilangkan, diganti dengan hitam transparan elegan */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg,
                    rgba(15, 23, 42, 0.25) 0%,   /* Slate gelap transparan tipis */
                    rgba(15, 23, 42, 0.75) 100%   /* Kontras gelap netral di bagian bawah */
                );
            backdrop-filter: blur(6px); 
            -webkit-backdrop-filter: blur(6px);
            z-index: 1;
        }

        /* Card Container Utama (Putih Solid) */
        .white-compact-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 540px; 
            padding: 50px 45px;
            margin: 20px;
            background: #ffffff; 
            border-radius: 32px;
            border: 1px solid rgba(11, 59, 182, 0.12); 
            box-shadow: 0 25px 55px rgba(15, 23, 42, 0.25); 
            text-align: center;
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardEntrance {
            from { transform: scale(0.96) translateY(15px); opacity: 0; }
            to { transform: scale(1) translateY(0); opacity: 1; }
        }

        /* Logo Section Terlindungi */
        .logo-wrapper {
            margin-bottom: 25px;
            display: inline-block;
            background: #ffffff;
            padding: 12px;
            border-radius: 50%;
            border: 3px solid #0B3BB6;
            box-shadow: 0 8px 20px rgba(11, 59, 182, 0.12);
            transition: all 0.4s ease;
        }
        .logo-wrapper img {
            width: 85px;
            height: 85px;
            object-fit: contain;
            display: block;
        }
        .logo-wrapper:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(11, 59, 182, 0.25);
        }

        /* --- STYLING TIPOGRAFI PREMIUM --- */
        
        /* Judul Form Atas */
        .form-title {
            font-size: 26px;
            font-weight: 700;
            color: #0B3BB6;
            letter-spacing: 0.5px;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
            width: 100%;
        }
        .form-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 45px;
            height: 3.5px;
            background: #0B3BB6;
            border-radius: 50px;
        }

        /* Wrapper Deskripsi Teks */
        .info-text-wrapper {
            padding: 0 10px;
            margin-bottom: 35px;
        }

        /* Kalimat Pertanyaan Atas */
        .sub-text-light {
            font-size: 14px;
            font-weight: 400;
            color: #64748b; 
            line-height: 1.5;
            margin-bottom: 8px;
        }

        /* Teks Utama "BeansPoint solusinya!" */
        .main-brand-text {
            font-size: 28px;
            font-weight: 800; 
            color: #1e293b; 
            letter-spacing: -0.5px;
            margin: 12px 0;
            line-height: 1.2;
        }
        /* Aksen Khusus Warna Biru Inti */
        .main-brand-text .accent-blue {
            color: #0B3BB6;
            font-weight: 900;
        }

        /* Kalimat Penjelasan Paling Bawah */
        .sub-text-dark {
            font-size: 13.5px;
            font-weight: 400;
            color: #475569;
            line-height: 1.6;
            max-width: 420px; 
            margin: 10px auto 0 auto;
        }

        /* BUTTON GRADASI BIRU PREMIUM PRO */
        .btn-gradient-blue {
            display: inline-block;
            background: linear-gradient(135deg, #0B3BB6 0%, #082980 100%); 
            color: #ffffff !important; 
            font-size: 13.5px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 15px 45px;
            border-radius: 14px; 
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 22px rgba(11, 59, 182, 0.35);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-gradient-blue:hover {
            background: linear-gradient(135deg, #164fe6 0%, #0B3BB6 100%); 
            transform: translateY(-2.5px);
            box-shadow: 0 14px 30px rgba(11, 59, 182, 0.5);
        }
        
        .btn-gradient-blue:active {
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

    <!-- Card Container Utama -->
    <div class="white-compact-container">
        
        <!-- Logo Section Terlindungi -->
        <div class="logo-wrapper">
            <img src="{{ asset('/img/logo4.png') }}" alt="My Stock Logo">
        </div>

        <!-- Heading Form -->
        <h2 class="form-title">Sign Up</h2>

        <!-- Info & Text Layout Wrapper -->
        <div class="info-text-wrapper">
            <p class="sub-text-light">Ingin stok barang terkendali dan pengelolaan lebih efisien?</p>
            <h1 class="main-brand-text">BeansPoint <span class="accent-blue">solusinya!</span></h1>
            <p class="sub-text-dark">Aplikasi yang memudahkan Anda dalam mengelola bisnis Anda secara praktis.</p>
        </div>

        <!-- Button Gradasi Biru -->
        <a href="{{ action([App\Http\Controllers\LoginRegisterController::class, 'showRegisterForm']) }}"
           class="btn-gradient-blue">Ayo Daftar Sekarang !!</a>
            
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>