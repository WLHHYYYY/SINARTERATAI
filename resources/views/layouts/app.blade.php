    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sinar Teratai Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            :root {
                --primary-color: #2c3e50;
                --secondary-color: #34495e;
                --accent-color: #3498db;
                --text-color: #ecf0f1;
                --hover-color: #2980b9;
                --transition-speed: 0.3s;
            }

            body {
                background-color: #f5f6fa;
            }

            .sidebar {
                height: 100vh;
                background: var(--primary-color);
                color: var(--text-color);
                padding: 1rem;
                position: fixed;
                top: 0;
                left: 0;
                width: 280px;
                transition: all var(--transition-speed) ease;
                box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
                z-index: 1000;
            }

            .sidebar.collapsed {
                width: 80px;
            }

            .sidebar-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin-bottom: 1.5rem;
            }

            .sidebar-header h3 {
                font-size: 1.5rem;
                font-weight: 600;
                margin: 0;
            }

            #toggleSidebar {
                background: transparent;
                border: none;
                color: var(--text-color);
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 5px;
                transition: background var(--transition-speed);
            }

            #toggleSidebar:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            .nav-section {
                margin-bottom: 1.5rem;
            }

            .nav-section h4 {
                text-transform: uppercase;
                font-size: 0.8rem;
                color: rgba(255, 255, 255, 0.5);
                margin-bottom: 0.8rem;
                padding-left: 0.5rem;
            }

            .nav-link {
                display: flex;
                align-items: center;
                padding: 0.8rem 1rem;
                color: var(--text-color);
                text-decoration: none;
                border-radius: 8px;
                transition: all var(--transition-speed);
                margin-bottom: 0.3rem;
            }

            .nav-link i {
                font-size: 1.2rem;
                min-width: 2rem;
            }

            .nav-link span {
                margin-left: 0.5rem;
                white-space: nowrap;
            }

            .nav-link:hover {
                background: var(--hover-color);
                transform: translateX(5px);
            }

            .nav-link.active {
                background: var(--accent-color);
                font-weight: 500;
            }

            .sidebar.collapsed .nav-link span,
            .sidebar.collapsed .sidebar-header h3 {
                display: none;
            }

            .content {
                margin-left: 280px;
                padding: 2rem;
                transition: margin var(--transition-speed) ease;
            }

            .sidebar.collapsed~.content {
                margin-left: 80px;
            }

            .logout-link {
                margin-top: 2rem;
                color: #e74c3c !important;
            }

            @media (max-width: 768px) {
                .sidebar {
                    width: 80px;
                }

                .sidebar .nav-link span,
                .sidebar .sidebar-header h3 {
                    display: none;
                }

                .content {
                    margin-left: 80px;
                }

                .sidebar.collapsed {
                    transform: translateX(-100%);
                }

                .sidebar.collapsed~.content {
                    margin-left: 0;
                }
            }
        </style>
    </head>

    <body>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Sinar Teratai</h3>
                <button id="toggleSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav>
                <div class="nav-section">
                    <h4>Menu Utama</h4>
                    <a href="{{ route('pengurangan.index') }}"
                        class="nav-link {{ request()->routeIs('pengurangan.index') ? 'active' : '' }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Penjualan</span>
                    </a>
                    <a href="{{ route('stok.index') }}"
                        class="nav-link {{ request()->routeIs('stok.index') ? 'active' : '' }}">
                        <i class="fas fa-pills"></i>
                        <span>Data Stok Obat</span>
                    </a>
                    <a href="{{ route('barang.index') }}"
                        class="nav-link {{ request()->routeIs('barang.index') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Data Obat</span>
                    </a>
                </div>

                <div class="nav-section">
                    <h4>Riwayat</h4>
                    <a href="{{ route('pengurangan.riwayat') }}"
                        class="nav-link {{ request()->routeIs('pengurangan.riwayat') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Pengurangan</span>
                    </a>
                    <a href="{{ route('stok.riwayat') }}"
                        class="nav-link {{ request()->routeIs('stok.riwayat') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Riwayat Penambahan</span>
                    </a>
                </div>

                @if (Auth::user()->role === 'supervisor' || Auth::user()->role === 'direktur')
                    <div class="nav-section">
                        <h4>Supervisor</h4>
                        <a href="{{ route('stock.approval') }}"
                            class="nav-link {{ request()->routeIs('stock.approval') ? 'active' : '' }}">
                            <i class="fas fa-check-circle"></i>
                            <span>Persetujuan Stok</span>
                        </a>
                        <a href="{{ route('jenis_obat.index') }}"
                            class="nav-link {{ request()->routeIs('jenis_obat.index') ? 'active' : '' }}">
                            <i class="fas fa-tablets"></i>
                            <span>Jenis Obat</span>
                        </a>
                        <a href="{{ route('supplier.index') }}"
                            class="nav-link {{ request()->routeIs('supplier.index') ? 'active' : '' }}">
                            <i class="fas fa-truck"></i>
                            <span>Supplier</span>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->role === 'direktur')
                    <div class="nav-section">
                        <h4>Direktur</h4>
                        <a href="{{ route('log-aktivitas.index') }}"
                            class="nav-link {{ request()->routeIs('log-aktivitas.index') ? 'active' : '' }}">
                            <i class="fas fa-check-circle"></i>
                            <span>Log Aktivitas</span>
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <i class="fas fa-check-circle"></i>
                            <span>User</span>
                        </a>
                    </div>
                @endif

                <a href="#" class="nav-link logout-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
        </div>

        <div class="content">
            @yield('content')
        </div>

        @include('components.success-modal')
        <script>
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('toggleSidebar');

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        </script>

        <script>
            // Fungsi untuk menampilkan modal sukses
            function showSuccessModal(message) {
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                document.getElementById('successMessage').textContent = message;
                successModal.show();

                // Auto hide setelah 3 detik
                setTimeout(() => {
                    successModal.hide();
                }, 3000);
            }

            // Cek flash message dari session saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('success'))
                    showSuccessModal("{{ session('success') }}");
                @endif
            });
        </script>

        @stack('scripts')


    </body>

    </html>
