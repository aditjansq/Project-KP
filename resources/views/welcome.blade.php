<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Centra Mobilindo</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8faff;
      overflow-x: hidden;
    }

    nav.navbar {
      background-color: transparent !important;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 100;
      padding: 15px 0;
      transition: background-color 0.3s ease, backdrop-filter 0.3s ease;
    }

    nav.navbar.scrolled {
      background-color: rgba(0, 0, 0, 0.6) !important;
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
    }

    nav .navbar-brand {
      color: white !important;
      font-weight: 700;
      font-size: 1.6rem;
    }

    .cta-buttons .btn {
      padding: 10px 25px;
      border-radius: 50px;
      font-weight: 600;
      margin-left: 10px;
    }

    .btn-outline-light {
      border: 2px solid white;
      color: white;
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
    }

    .btn-outline-light:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .btn-danger {
      background-color: #e74c3c;
      border: 2px solid #e74c3c;
    }

    .btn-danger:hover {
      background-color: #c0392b;
      border-color: #c0392b;
    }

    .hero {
      position: relative;
      width: 100%;
      height: 100vh;
      background-image: url('{{ asset('images/depan2.jpeg') }}');
      background-size: cover;
      background-position: 50% 60%;
      background-repeat: no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      color: white;
      overflow: hidden;
      animation: heroBackgroundFade 8s infinite;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('{{ asset('images/foto-tampak-depan.jpeg') }}');
      background-size: cover;
      background-position: 50% 40%;
      background-repeat: no-repeat;
      opacity: 0;
      animation: heroOverlayFade 8s infinite;
      z-index: 0;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1;
      box-shadow: inset 0 -150px 70px -30px rgba(0, 0, 0, 0.7);
    }

    .hero > * {
      position: relative;
      z-index: 2;
    }

    @keyframes heroOverlayFade {
      0% { opacity: 0; }
      40% { opacity: 0; }
      50% { opacity: 1; }
      90% { opacity: 1; }
      100% { opacity: 0; }
    }

    .hero h2 {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    }

    .hero p {
      font-size: 1.2rem;
      max-width: 800px;
      margin: 0 auto 40px;
      color: rgba(255,255,255,0.9);
    }

    .hero-buttons .btn {
      padding: 15px 40px;
      font-size: 1.1rem;
      margin: 0 10px;
      border-radius: 50px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
    }

    .hero-buttons .btn i {
      margin-right: 8px;
    }

    .btn-download {
      border: 2px solid white;
      color: white;
      background-color: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
    }

    .btn-download:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .btn-upgrade {
      background-color: #e74c3c;
      border: 2px solid #e74c3c;
      color: white;
    }

    .btn-upgrade:hover {
      background-color: #c0392b;
      border-color: #c0392b;
    }

    .animate-on-scroll {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .animate-on-scroll.show-element {
      opacity: 1;
      transform: translateY(0);
    }

    .hero-title, .hero-subtitle, .hero-btn {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s ease-out, transform 1s ease-out;
    }

    .hero-section.loaded .hero-title {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.3s;
    }

    .hero-section.loaded .hero-subtitle {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.6s;
    }

    .hero-section.loaded .hero-btn:nth-child(1) {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 0.9s;
    }

    .hero-section.loaded .hero-btn:nth-child(2) {
      opacity: 1;
      transform: translateY(0);
      transition-delay: 1.2s;
    }

    /* === Media Queries === */
    @media (max-width: 991.98px) {
      nav .navbar-brand {
        font-size: 1.4rem;
      }

      .cta-buttons .btn {
        padding: 8px 18px;
        font-size: 0.9rem;
      }
    }

    @media (max-width: 767.98px) {
      .hero h2 {
        font-size: 2.2rem;
        line-height: 1.2;
      }

      .hero p {
        font-size: 1rem;
        padding: 0 15px;
        margin-bottom: 30px;
      }

      .hero-buttons {
        flex-direction: column;
        width: 100%;
        align-items: center;
      }

      .hero-buttons .btn {
        margin: 10px 0;
        width: 70%;
        max-width: 300px;
        font-size: 1rem;
        padding-left: 0;
        padding-right: 0;
        justify-content: center;
        text-align: center;
        display: flex;
        align-items: center;
      }

      .hero-buttons .btn i {
        margin-right: 8px;
        position: relative;
        left: 0;
      }

      .hero-buttons .btn .btn-text {
        margin-left: 0;
        position: relative;
        left: 0;
      }

      .btn-download, .btn-upgrade {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .why-choose-us h2 {
        font-size: 1.8rem;
        margin-bottom: 30px !important;
      }

      .why-choose-us .col-lg-4 {
        padding-left: 15px;
        padding-right: 15px;
      }

      .why-choose-us .p-4 {
        padding: 1.5rem !important;
      }

      .why-choose-us .fs-1 {
        font-size: 2.5rem !important;
      }

      .why-choose-us h4 {
        font-size: 1.2rem;
      }

      .why-choose-us p {
        font-size: 0.9rem;
      }
    }

    @media (max-width: 575.98px) {
      nav .navbar-brand {
        font-size: 1.2rem;
      }

      .cta-buttons .btn {
        padding: 6px 15px;
        font-size: 0.8rem;
      }

      .hero h2 {
        font-size: 1.8rem;
      }

      .hero p {
        font-size: 0.9rem;
      }

      .hero-buttons .btn {
        width: 85%;
      }

      .why-choose-us h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#">Centra Mobilindo</a>
      <div class="cta-buttons ms-auto">
        <a href="/login" class="btn btn-outline-light">Masuk</a>
        <a href="/register" class="btn btn-danger">Daftar</a>
      </div>
    </div>
  </nav>

  <section class="hero hero-section">
    <h2 class="hero-title">SISTEM INFORMASI PENDATAAN MOBIL</h2>
    <p class="hero-subtitle">
      Website ini memudahkan dalam mengelola dan mendata koleksi mobil secara efisien. Dengan antarmuka yang intuitif, Anda dapat dengan cepat mengakses informasi mobil yang diperlukan.
    </p>
    <div class="hero-buttons d-flex justify-content-center flex-wrap">
      <a href="#" class="btn btn-download hero-btn">
        <i class="fas fa-car"></i> <span class="btn-text">Lihat Inventaris</span>
      </a>
      <a href="#" class="btn btn-upgrade hero-btn">
        <i class="fas fa-phone-alt"></i> <span class="btn-text">Hubungi Kami</span>
      </a>
    </div>
  </section>

  <section class="why-choose-us py-5 bg-white text-center">
    <div class="container">
      <h2 class="fw-bold mb-5 text-dark animate-on-scroll">Mengapa Memilih Centra Mobilindo?</h2>
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="p-4 shadow-sm border rounded-4 h-100 animate-on-scroll">
            <div class="mb-3 text-primary fs-1"><i class="fas fa-car"></i></div>
            <h4 class="fw-semibold mb-2">Pilihan Mobil Lengkap</h4>
            <p class="text-muted">
              Tersedia berbagai pilihan mobil baru dan bekas dari merek terpercaya sesuai kebutuhan Anda.
            </p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="p-4 shadow-sm border rounded-4 h-100 animate-on-scroll">
            <div class="mb-3 text-primary fs-1"><i class="fas fa-certificate"></i></div>
            <h4 class="fw-semibold mb-2">Kualitas Terjamin</h4>
            <p class="text-muted">
              Setiap unit melalui inspeksi menyeluruh oleh teknisi ahli agar Anda mendapatkan mobil terbaik.
            </p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="p-4 shadow-sm border rounded-4 h-100 animate-on-scroll">
            <div class="mb-3 text-primary fs-1"><i class="fas fa-headset"></i></div>
            <h4 class="fw-semibold mb-2">Cash dan kredit</h4>
            <p class="text-muted">
              Pembayaran Cash dan Kredit
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-2">&copy; 2025 Centra Mobilindo. Semua hak dilindungi.</p>
      <div>
        <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
        <a href="#" class="text-white mx-2"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const heroSection = document.querySelector('.hero-section');
      heroSection.classList.add('loaded');

      const navbar = document.querySelector('.navbar');
      window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }

        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        animatedElements.forEach(element => {
          const elementTop = element.getBoundingClientRect().top;
          const viewportHeight = window.innerHeight;

          if (elementTop < viewportHeight - 100) {
            element.classList.add('show-element');
          }
        });
      });

      window.dispatchEvent(new Event('scroll'));
    });
  </script>
</body>
</html>
