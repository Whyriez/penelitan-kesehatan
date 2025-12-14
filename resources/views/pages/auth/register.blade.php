<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - Sistem Kearsipan Izin Kesehatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
          rel="stylesheet"/>
    <style>
        /* Semua style CSS dari halaman login dipertahankan */
        body {
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
            overflow-x: hidden;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 25%, #ffffff 100%);
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in-right {
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            z-index: 10;
        }

        /* Penyesuaian kecil untuk label dan input icon */
        .input-field-container {
            position: relative;
        }

        .input-field {
            padding-left: 40px;
            transition: all 0.3s ease;
        }

        /* Menyesuaikan posisi ikon ketika label ada di atas */
        .input-group .input-icon {
            top: 60%; /* Sesuaikan jika label 1 baris */
        }

        .input-group label + .input-field-container .input-icon {
            top: 50%; /* Reset jika label ada di atas */
        }


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

        .btn-primary:active {
            transform: translateY(0);
        }

        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .illustration-container {
            background: linear-gradient(135deg, #047857 0%, #10b981 50%, #34d399 100%);
        }

        @media (max-width: 768px) {
            .split-screen {
                grid-template-columns: 1fr;
            }

            .illustration-container {
                display: none;
            }
        }

        /* Style untuk layout compact dipertahankan */
        .compact-layout {
            min-height: 100vh;
            max-height: 100vh;
            overflow-y: auto; /* Diubah dari hidden ke auto agar bisa scroll jika form panjang */
        }

        .compact-form {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .compact-card {
            padding: 1.5rem;
        }

        .compact-logo {
            width: 3rem;
            height: 3rem;
            margin-bottom: 0.75rem;
        }

        .compact-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .compact-input {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .compact-footer {
            margin-top: 1rem;
        }

        /* Icon kesehatan */
        .health-icon {
            background: linear-gradient(135deg, #047857 0%, #10b981 100%);
        }
    </style>
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
</head>

<body class="h-full gradient-bg compact-layout">
<div class="min-h-full grid grid-cols-1 lg:grid-cols-2 split-screen">
    {{-- Left Side dengan Background Image Blur --}}
    <div
        class="illustration-container hidden lg:flex items-center justify-center p-8 slide-in-left relative overflow-hidden">
        {{-- Background Image dengan Blur --}}
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: url('{{ asset('img/Foto MPP Bone Bolango.jpeg') }}'); filter: blur(8px); transform: scale(1.1);">
        </div>

        {{-- Overlay Gradient untuk meningkatkan keterbacaan teks --}}
        <div class="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-emerald-900/80"></div>

        {{-- Content --}}
        <div class="relative z-10 max-w-md text-center text-white">
            <h2 class="text-2xl font-bold mb-4 drop-shadow-lg">
                Dinas Penanaman Modal Pelayanan Terpadu Satu Pintu Bone Bolango
            </h2>

            <p class="text-white/90 leading-relaxed text-base drop-shadow-md bg-black/20 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                Platform digital untuk mengelola, menyimpan, dan mengakses arsip izin
                kesehatan secara terstruktur dan aman.
            </p>

            {{-- Decorative Elements --}}
            <div class="mt-8 flex justify-center gap-2">
                <div class="w-12 h-1 bg-white/40 rounded-full"></div>
                <div class="w-12 h-1 bg-white/60 rounded-full"></div>
                <div class="w-12 h-1 bg-white/40 rounded-full"></div>
            </div>
        </div>
    </div>

    {{-- Right Side - Register Form --}}
    <div class="flex items-center justify-center p-4 lg:p-8 slide-in-right compact-form">
        <div class="w-full max-w-md">
            <div class="text-center mb-6 fade-in">
                <img src="{{ asset('img/logo.jpg') }}"
                     alt="Logo"
                     class="mx-auto w-20 h-25"/>
                <h1 id="system-title" class="text-xl font-bold text-gray-900 mb-1 compact-title">
                    Sistem Kearsipan Izin Kesehatan
                </h1>
                <p id="welcome-text" class="text-gray-600 text-sm">
                    Silakan mendaftar untuk membuat akun baru
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

                <form id="register-form" action="{{ route('register.process') }}" method="POST"
                      class="space-y-4">
                    @csrf

                    <div class="input-group">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama
                            Lengkap</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input type="text" id="name" name="name" required
                                   value="{{ old('name') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Masukkan nama lengkap"/>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input type="email" id="email" name="email" required
                                   value="{{ old('email') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Masukkan email"/>
                        </div>
                    </div>

                    {{-- =============================================== --}}
                    {{-- MULAI FIELD TAMBAHAN --}}
                    {{-- =============================================== --}}

                    <div class="input-group">
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                            Telepon</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" required
                                   value="{{ old('nomor_telepon') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Contoh: 08123456789"/>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="institusi" class="block text-sm font-medium text-gray-700 mb-1">Institusi /
                            Afiliasi</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0v-4m0 4h5m0 0v-4m0 4h5m0 0v-4m-5 0h-5m-2-16l7-4 7 4M5 5l7 4 7-4"/>
                            </svg>
                            <input type="text" id="institusi" name="institusi" required
                                   value="{{ old('institusi') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Nama rumah sakit/universitas"/>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="nomor_identitas"
                               class="block text-sm font-medium text-gray-700 mb-1">Nomor Identitas
                            (Opsional)</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <input type="text" id="nomor_identitas" name="nomor_identitas"
                                   value="{{ old('nomor_identitas') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="NIP / STR / KTP"/>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="gelar_jabatan"
                               class="block text-sm font-medium text-gray-700 mb-1">Gelar / Jabatan
                            (Opsional)</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m10 0h0M6 6h0m-2 2h16a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2v-7a2 2 0 012-2h0z">
                                </path>
                            </svg>
                            <input type="text" id="gelar_jabatan" name="gelar_jabatan"
                                   value="{{ old('gelar_jabatan') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Contoh: Dokter / Perawat / Peneliti"/>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="department"
                               class="block text-sm font-medium text-gray-700 mb-1">Departemen / Spesialisasi
                            (Opsional)</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20v-2m0 2H5v-2a3 3 0 015.356-1.857M17 20H5m12 0v-2m0 2c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm-6 0c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z">
                                </path>
                            </svg>
                            <input type="text" id="department" name="department"
                                   value="{{ old('department') }}"
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Contoh: Kardiologi / Gizi Klinik"/>
                        </div>
                    </div>

                    {{-- =============================================== --}}
                    {{-- AKHIR FIELD TAMBAHAN --}}
                    {{-- =============================================== --}}


                    <div class="input-group">
                        <label for="password"
                               class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password" name="password" required
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Masukkan password"/>
                            <button type="button" id="toggle-password"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation"
                               class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative input-field-container">
                            <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor"
                                 viewbox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   required
                                   class="input-field w-full px-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-0 transition-all compact-input"
                                   placeholder="Ulangi password Anda"/>
                            <button type="button" id="toggle-password-confirm"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="register-btn"
                            class="btn-primary w-full py-2 px-4 text-white font-semibold rounded-lg shadow-lg focus:outline-none focus:ring-4 focus:ring-green-300 mt-2">
                        <span id="register-text">Daftar Akun</span>
                        <svg id="register-spinner"
                             class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white inline"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </button>
                </form>

                <div class="text-center mt-4 text-xs text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="font-medium text-green-600 hover:text-green-800 transition-colors">
                        Masuk di sini
                    </a>
                </div>
            </div>

            <div class="text-center mt-4 text-xs text-gray-500 fade-in compact-footer">
                <p>
                    © 2025 <span id="institution-name">Kementerian Kesehatan Republik Indonesia</span>
                </p>
                <p class="mt-1">Sistem Kearsipan Izin Kesehatan v2.0</p>
            </div>
        </div>
    </div>
</div>

<script>
    function setupEventListeners() {
        // Toggle untuk password pertama
        const togglePassword = document.getElementById("toggle-password");
        const passwordInput = document.getElementById("password");

        if (togglePassword) {
            togglePassword.addEventListener("click", () => {
                const type =
                    passwordInput.getAttribute("type") === "password" ?
                        "text" :
                        "password";
                passwordInput.setAttribute("type", type);

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
        }

        // Toggle untuk password konfirmasi (kedua)
        const togglePasswordConfirm = document.getElementById("toggle-password-confirm");
        const passwordConfirmInput = document.getElementById("password_confirmation");

        if (togglePasswordConfirm) {
            togglePasswordConfirm.addEventListener("click", () => {
                const type =
                    passwordConfirmInput.getAttribute("type") === "password" ?
                        "text" :
                        "password";
                passwordConfirmInput.setAttribute("type", type);

                const icon = togglePasswordConfirm.querySelector("svg");
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
        }

        // Script untuk input field focus/blur (Sama seperti login)
        const inputs = document.querySelectorAll(".input-field");
        inputs.forEach((input) => {
            input.addEventListener("focus", () => {
                input.parentElement.classList.add("focused");
            });
            input.addEventListener("blur", () => {
                input.parentElement.classList.remove("focused");
            });
        });

        // Script submit form diupdate ke ID register
        const registerForm = document.getElementById("register-form");
        const registerBtn = document.getElementById("register-btn");
        const registerText = document.getElementById("register-text");
        const registerSpinner = document.getElementById("register-spinner");

        if (registerForm) {
            registerForm.addEventListener("submit", function () {
                // Tampilkan status loading saat form di-submit
                registerText.textContent = "Mendaftarkan..."; // Teks diubah
                registerSpinner.classList.remove("hidden");
                registerBtn.disabled = true;

                // Form akan di-submit secara normal ke server (PHP)
            });
        }
    }

    // Jalankan event listeners saat halaman dimuat
    document.addEventListener('DOMContentLoaded', setupEventListeners);
</script>
</body>

</html>
