<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Dashboard Admin | Kedibihi</title>
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            /* Tambahan agar judul di tengah */
            .app-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background: #8B6A42;
            color: #fff;
            position: relative;
            }
            .app-header h2 {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            }
            .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2; /* supaya dropdown tidak ketiban */
            }
            .profile-icon {
            width: 32px;
            height: 32px;
            cursor: pointer;
            border-radius: 50%;
            }
            .dropdown {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 40px;
            right: 0;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 100;
            }
            .dropdown-item, .submenu-items button {
            background: none;
            border: none;
            padding: 5px 0;
            text-align: left;
            cursor: pointer;
            }
            .submenu-items {
            display: none;
            flex-direction: column;
            margin-left: 10px;
            }
            .low-stock-list{
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }

            .low-stock-list li{
                list-style: none;
                margin: 5px 15px;
            }

            .card-product {
            border: 2px solid #5E9C32;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s;
            height: 100%;        
            max-width: 250px; 
            }
            .card-product:hover {
                transform: scale(1.03);
            }
            .card-product img {
                width: 100%;
                height: 180px;
                object-fit: cover;
                border-radius: 6px;
            }
            .card-product h4 {
                margin: 10px 0 5px;
                font-size: 18px;
            }
        </style>
    </head>
    <body>
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Kedibihi">
            </div>
            <ul>
                <li class="active"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li><a href="{{ route('products.index') }}">Manajemen Produk</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <header class="app-header">
                <button class="sidebar-toggle" aria-label="Toggle Sidebar">
                    <span style="font-size:28px;">&#9776;</span>
                </button>
                <h2>Dashboard Admin</h2>
                <div class="header-right">
                    <img src="{{ asset('img/profil.png') }}" alt="Profil Admin" class="profile-icon" id="profile-icon">
                    <div class="dropdown" id="profile-dropdown">
                        <button class="dropdown-item" onclick="toggleSubmenu()">Pengaturan Akun ▸</button>
                        <div class="submenu-items" id="submenu-items">
                            <button onclick="bukaModalPassword()">Ganti Kata Sandi</button>
                        </div>
                        <button class="dropdown-item" id="btn-logout">Logout</button>
                    </div>
                </div>
            </header>

            <!-- KPI Cards -->

            <!-- KPI Cards -->
            <section class="grid-kpi">
                <div class="kpi-card">
                    <div class="kpi-title">Total Produk</div>
                    <div class="kpi-value" id="kpi-total-produk">{{ $totalProduk }}</div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-title">Rata-rata Rating (bulan ini)</div>
                    <div class="kpi-value" id="kpi-rating-bulan-ini">{{ $ratingBulanIni }}</div>
                </div>
            </section>

            <!-- Grafik Rating Per Bulan -->
            <div class="card">
                <div class="card-title">Rata-rata Rating Per Bulan</div>
                <div style="height:400px;">
                    <canvas id="ratingChart"></canvas>
                </div>
            </div>

        </main>
        
        <!-- Form logout hidden -->
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire("Error!", "{{ $errors->first() }}", "error");
            })
        </script>
        @endif

        <!-- Modal Ganti Password -->
        <div id="password-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);justify-content:center;align-items:center;z-index:1000;">
            <div style="background:#fff;padding:20px;border-radius:10px;width:320px;">
                <h3>Ganti Kata Sandi</h3>
                <form id="form-password" action="{{ route('admin.changePassword') }}" method="POST" style="margin-top:10px;">
                    @csrf
                    <input type="password" name="old_password" id="password-lama" placeholder="Password Lama" required style="width:100%;margin-bottom:10px;padding:8px;">
                    <input type="password" name="new_password" id="password-baru" placeholder="Password Baru" required style="width:100%;margin-bottom:10px;padding:8px;">
                    <input type="password" name="new_password_confirmation" id="password-konfirmasi" placeholder="Konfirmasi Password Baru" required style="width:100%;margin-bottom:10px;padding:8px;">
                    <button type="submit" style="width:100%;margin-bottom:8px;">Simpan</button>
                    <button type="button" onclick="tutupModalPassword()" style="width:100%;">Batal</button>
                </form>
            </div>
        </div>

        <script src="{{ asset('js/script.js') }}"></script>
        <script>
        const ctx = document.getElementById('ratingChart').getContext('2d');
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const ratingData = @json($ratingChart);
        
        const ratingChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rata-rata Rating',
                    data: ratingData,
                    borderColor: '#8B6A42',
                    backgroundColor: 'rgba(139,106,66,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        </script>
        <script>
        function toggleSubmenu() {
            const submenu = document.getElementById("submenu-items");
            submenu.style.display = (submenu.style.display === "flex") ? "none" : "flex";
        }
        </script>

        <script>
            document.getElementById('btn-logout').addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Yakin Ingin Logout?',
                    text: "Kamu akan keluar dari akun admin!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, logout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        </script>
    </body>
</html>
