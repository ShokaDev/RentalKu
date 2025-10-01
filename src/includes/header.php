<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Active nav indicator */
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 2px;
            transition: transform 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            transform: translateX(-50%) scaleX(1);
        }
        
        .nav-link.active {
            color: #059669;
            font-weight: 600;
        }
        
        /* Dropdown animation */
        .dropdown-content {
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            pointer-events: none;
        }
        
        .dropdown:hover .dropdown-content {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        
        /* Smooth backdrop blur */
        .header-blur {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* User menu animation */
        .user-menu {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.2s ease;
            pointer-events: none;
        }
        
        .user-dropdown:hover .user-menu {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        /* Mobile menu */
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .mobile-menu.active {
            transform: translateX(0);
        }
    </style>
</head>
<body>

<!-- Header -->
<header class="h-16 w-full fixed top-0 z-50 header-blur bg-white/90 shadow-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
        
        <!-- Logo Section -->
        <div class="flex items-center gap-8">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-green-700 bg-clip-text text-transparent">
                    RentalKu
                </h1>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:block">
                <ul class="flex items-center gap-1">
                    <li>
                        <a href="index.php" class="nav-link active px-4 py-2 text-gray-700 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="kendaraan.php" class="nav-link px-4 py-2 text-gray-700 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">
                            Kendaraan
                        </a>
                    </li>

                    <!-- Admin Only Links -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li>
                        <a href="pemilik.php" class="nav-link px-4 py-2 text-gray-700 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">
                            Mitra
                        </a>
                    </li>
                    <li>
                        <a href="pelanggan.php" class="nav-link px-4 py-2 text-gray-700 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-all">
                            Pelanggan
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Dashboard Links -->
                    <?php if (isset($_SESSION['role'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li>
                            <a href="dashboard_admin.php" class="px-4 py-2 text-blue-600 font-semibold bg-blue-50 rounded-lg hover:bg-blue-100 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <?php elseif ($_SESSION['role'] === 'agen'): ?>
                        <li>
                            <a href="dashboard_agen.php" class="px-4 py-2 text-emerald-600 font-semibold bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- Right Section -->
        <div class="flex items-center gap-4">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <!-- Not Logged In -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="../register.php" class="px-5 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors">
                        Sign Up
                    </a>
                    <a href="../login.php" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-full hover:from-emerald-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Log In
                    </a>
                </div>
            <?php else: ?>
                <!-- Logged In - User Menu -->
                <div class="hidden md:block relative user-dropdown">
                    <button class="flex items-center gap-3 px-4 py-2 rounded-full hover:bg-gray-100 transition-all">
                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                            <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-gray-800">
                                <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                            </p>
                            <p class="text-xs text-gray-500 capitalize">
                                <?= htmlspecialchars($_SESSION['role'] ?? 'User') ?>
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="user-menu absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">
                                <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                <?= htmlspecialchars($_SESSION['email'] ?? '') ?>
                            </p>
                        </div>
                        
                        <a href="profile.php" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium">Profil Saya</span>
                        </a>
                        
                        <a href="settings.php" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm font-medium">Pengaturan</span>
                        </a>
                        
                        <hr class="my-2 border-gray-100">
                        
                        <a href="../../php/logout.php" class="flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-sm font-semibold">Logout</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="lg:hidden p-2 text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobileMenu" class="mobile-menu fixed top-16 right-0 w-80 h-screen bg-white shadow-2xl z-40 lg:hidden overflow-y-auto">
    <div class="p-6">
        <!-- User Info (if logged in) -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mb-6 p-4 bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-md">
                    <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)) ?>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">
                        <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
                    </p>
                    <p class="text-sm text-gray-600 capitalize">
                        <?= htmlspecialchars($_SESSION['role'] ?? 'User') ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Mobile Navigation -->
        <nav class="space-y-2 mb-6">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                Home
            </a>
            
            <a href="kendaraan.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                </svg>
                Kendaraan
            </a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="pemilik.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
                Mitra
            </a>
            
            <a href="pelanggan.php" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                Pelanggan
            </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['role'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 font-semibold rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    Dashboard Admin
                </a>
                <?php elseif ($_SESSION['role'] === 'agen'): ?>
                <a href="dashboard_agen.php" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-600 font-semibold rounded-lg">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                    Dashboard Agen
                </a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <!-- Auth Buttons -->
        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="space-y-3 pt-6 border-t border-gray-200">
            <a href="../register.php" class="block w-full px-4 py-3 text-center text-gray-700 font-semibold border-2 border-emerald-500 rounded-lg hover:bg-emerald-50 transition-all">
                Sign Up
            </a>
            <a href="../login.php" class="block w-full px-4 py-3 text-center bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-green-700 transition-all shadow-md">
                Log In
            </a>
        </div>
        <?php else: ?>
        <div class="pt-6 border-t border-gray-200">
            <a href="../../php/logout.php" class="flex items-center justify-center gap-3 w-full px-4 py-3 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobileOverlay" class="hidden fixed inset-0 bg-black/20 backdrop-blur-sm z-30 lg:hidden"></div>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        mobileOverlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    });

    mobileOverlay.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    });

    // Active nav link highlighting
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
</script>

</body>
</html>