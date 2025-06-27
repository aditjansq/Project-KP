<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Showroom Mobil Kami</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- External Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        /* Body styling */
        html, body {
            height: 100%; /* Full height for body */
            margin: 0; /* Remove default margin */
            font-family: 'Poppins', sans-serif;
            background-color: #f4f9fd;
            color: #333;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        nav {
            background-color: #0056b3; /* Navbar biru gelap */
        }

        nav .navbar-brand {
            color: white;
            font-weight: 600;
        }

        .cta-buttons a {
            text-decoration: none;
            color: #0056b3;
            background-color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-left: 15px;
        }

        .cta-buttons a:hover {
            background-color: #e0e0e0;
        }

        /* Hero Section */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 60px 20px;
            background-color: white;
            flex: 1; /* Allow hero to take available space */
        }

        .hero .text {
            max-width: 50%;
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #212529;
            font-weight: 600;
        }

        .hero p {
            font-size: 1.125rem;
            margin-bottom: 20px;
            color: #6c757d;
        }

        .hero img {
            max-width: 45%;
            height: auto;
            border-radius: 10px;
        }

        /* Footer */
        footer {
            background-color: #0056b3;
            color: white;
            padding: 10px;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Centra Mobilindo</a>
            <div class="cta-buttons ms-auto">
                <a href="/login">Masuk</a>
                <a href="/register">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero container mt-5">
        <div class="text">
            <!-- Simple "Selamat Datang" without animation -->
            <h2>Selamat Datang</h2>
            <p>Sistem Informasi Showroom Mobil Centra Mobilindo</p>
        </div>
        <img src="{{ asset('images/showroom.jpg') }}" alt="Ilustrasi Showroom Mobil" class="img-fluid">
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 xxxxx. Semua hak cipta dilindungi.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
