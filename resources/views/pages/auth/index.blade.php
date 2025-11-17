<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Arsip Penelitian Kesehatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <style>
        body {
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            overflow-x: hidden;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 25%, #ffffff 100%);
        }
        .fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in-left { animation: slideInLeft 0.8s ease-out; }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in-right { animation: slideInRight 0.8s ease-out; }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .input-group { position: relative; }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 10;
        }
        .input-field { padding-left: 40px; transition: all 0.3s ease; }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            border-color: #10b981;
        }
        .btn-primary {
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }
        .btn-primary:active { transform: translateY(0); }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .illustration-container {
            background: linear-gradient(135deg, #047857 0%, #10b981 50%, #34d399 100%);
        }
        @media (max-width: 768px) {
            .split-screen { grid-template-columns: 1fr; }
            .illustration-container { display: none; }
        }
        /* Perbaikan untuk layout yang lebih compact */
        .compact-layout { min-height: 100vh; max-height: 100vh; overflow: hidden; }
        .compact-form { padding-top: 1rem; padding-bottom: 1rem; }
        .compact-card { padding: 1.5rem; }
        .compact-logo { width: 3rem; height: 3rem; margin-bottom: 0.75rem; }
        .compact-title { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .compact-input { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .compact-footer { margin-top: 1rem; }
        .health-icon {
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
        }
    </style>
    <style>
        @view-transition { navigation: auto; }
    </style>
</head>

<body class="h-full gradient-bg compact-layout">
    <div class="min-h-full grid grid-cols-1 lg:grid-cols-2 split-screen">
        <div class="illustration-container hidden lg:flex items-center justify-center p-8 slide-in-left">
            <div class="max-w-md text-center text-white">
                <div class="mb-6">
                    <svg class="w-48 h-48 mx-auto" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="200" cy="200" r="180" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="2" />
                        <!-- Simbol kesehatan - stetoskop dan plus -->
                        <circle cx="160" cy="160" r="40" fill="rgba(255,255,255,0.9)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <circle cx="240" cy="160" r="40" fill="rgba(255,255,255,0.8)" stroke="rgba(255,255,255,0.3)" stroke-width="2" />
                        <path d="M160 200 L200 240 L240 200" stroke="#10b981" stroke-width="8" fill="none" stroke-linecap="round" />
                        <path d="M200 120 L200 160 M180 140 L220 140" stroke="#10b981" stroke-width="6" stroke-linecap="round" />
                        <!-- Simbol plus di tengah -->
                        <path d="M200 240 L200 300 M170 270 L230 270" stroke="white" stroke-width="8" stroke-linecap="round" />
                        <!-- Elemen dekoratif -->
                        <circle cx="280" cy="120" r="8" fill="rgba(255,255,255,0.6)" />
                        <circle cx="320" cy="180" r="6" fill="rgba(255,255,255,0.4)" />
                        <circle cx="100" cy="160" r="10" fill="rgba(255,255,255,0.5)" />
                        <circle cx="80" cy="200" r="7" fill="rgba(255,255,255,0.3)" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-3">Arsip Penelitian Kesehatan Terpusat</h2>
                <p class="text-green-100 leading-relaxed text-sm">
                    Platform digital untuk mengelola, menyimpan, dan mengakses arsip penelitian
                    kesehatan secara terstruktur dan aman.
                </p>
            </div>
        </div>
        
        <div class="flex items-center justify-center p-4 lg:p-8 slide-in-right compact-form">
            <div class="w-full max-w-md">
                <div class="text-center mb-6 fade-in">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 health-icon rounded-xl mb-3 shadow-lg compact-logo">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h1 id="system-title" class="text-xl font-bold text-gray-900 mb-1 compact-title">
                        Sistem Arsip Penelitian Kesehatan
                    </h1>
                    <p id="welcome-text" class="text-gray-600 text-sm">
                        Selamat datang di sistem arsip penelitian kesehatan
                    </p>
                </div>

                <div class="login-card rounded-2xl shadow-xl border border-white/20 compact-card fade-in">
                    
                    @if (session('flash.error'))
                        <div class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-700" role="alert">
                            {{ session('flash.error') }}
                        </div>
                    @endif
                    @if (session('flash.success'))
                         <div class="mb-4 rounded-lg bg-green-100 p-3 text-sm text-green-700" role="alert">
                            {{ session('flash.success') }}
                        </div>
                    @endif
                    
                    {{-- Tampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-100 p-3 text-sm text-red-700" role="alert">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="login-form" action="{{ route('login.process') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="input-group">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <input type="text" id="email" name="email" required value="{{ old('email') }}"
                                    class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                    placeholder="Masukkan email" />
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <label for="password"
                                class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <input type="password" id="password" name="password" required
                                    class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                    placeholder="Masukkan password" />
                                <button type="button" id="toggle-password"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center text-xs mt-3">
                            <a href="{{ route('auth.register') }}" class="text-green-600 hover:text-green-800 font-medium transition-colors">
                                Daftar Akun Baru
                            </a>
                            <a href="#" class="text-green-600 hover:text-green-800 font-medium transition-colors">
                                Lupa Password?
                            </a>
                        </div>

                        <button type="submit" id="login-btn"
                            class="btn-primary w-full py-2 px-4 text-white font-semibold rounded-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-green-300 mt-2">
                            <span id="login-text">Masuk ke Sistem</span>
                            <svg id="login-spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white inline"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="text-center mt-4 text-xs text-gray-500 fade-in compact-footer">
                    <p>
                        © 2025 <span id="institution-name">Kementerian Kesehatan Republik Indonesia</span>
                    </p>
                    <p class="mt-1">Sistem Arsip Penelitian Kesehatan v2.0</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setupEventListeners() {
            const togglePassword = document.getElementById("toggle-password");
            const passwordInput = document.getElementById("password");

            togglePassword.addEventListener("click", () => {
                const type =
                    passwordInput.getAttribute("type") === "password" ?
                    "text" :
                    "password";
                passwordInput.setAttribute("type", type);

                // Toggle icon
                const icon = togglePassword.querySelector("svg");
                if (type === "text") {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    `;
                } else {
                    icon.innerHTML = `
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    `;
                }
            });

            const inputs = document.querySelectorAll(".input-field");
            inputs.forEach((input) => {
                input.addEventListener("focus", () => {
                    input.parentElement.classList.add("focused");
                });
                input.addEventListener("blur", () => {
                    input.parentElement.classList.remove("focused");
                });
            });

            const loginForm = document.getElementById("login-form");
            const loginBtn = document.getElementById("login-btn");
            const loginText = document.getElementById("login-text");
            const loginSpinner = document.getElementById("login-spinner");

            loginForm.addEventListener("submit", function() {
                // Tampilkan status loading saat form di-submit
                loginText.textContent = "Memproses...";
                loginSpinner.classList.remove("hidden");
                loginBtn.disabled = true;
                
                // Form akan di-submit secara normal ke server (PHP)
            });
        }

        // Jalankan event listeners saat halaman dimuat
        document.addEventListener('DOMContentLoaded', setupEventListeners);
    </script>
</body>
</html>