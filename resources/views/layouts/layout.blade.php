<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet"/>
    <style>
        body {
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .sidebar-hidden {
                transform: translateX(-100%);
            }
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 400;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-valid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-revisi {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .info-banner {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
            border: 1px solid #bae6fd;
        }
    </style>
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
</head>

<body class="h-screen bg-gray-50 overflow-hidden">
<div class="h-full flex">
    @include('components.layouts.sidebar')
    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-25 z-10 hidden md:hidden"></div>
    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full">
        <!-- Header -->
        @include('components.layouts.header')
        <!-- Main Content Area -->
        @yield('content')

    </div>
</div>


@auth
    @if (!Auth::user()->is_profile_complete)
        {{-- CONTAINER UTAMA --}}
        <div class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900 bg-opacity-95 backdrop-blur-sm p-4">

            {{-- MODAL CARD (Sticky Header & Footer) --}}
            <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden ring-1 ring-gray-200">

                {{-- 1. HEADER (Ringkas & Jelas) --}}
                <div class="flex-none px-6 py-5 border-b border-gray-100 bg-gray-50 z-10">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 bg-sky-100 p-2 rounded-full text-sky-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Lengkapi Profil Anda</h3>
                            <p class="text-sm text-gray-600">
                                Data ini <strong>wajib</strong> dilengkapi untuk keperluan <strong>validasi</strong> dan <strong>pencetakan dokumen resmi</strong> secara otomatis.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 2. FORM CONTENT (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6 bg-white custom-scrollbar">
                    <form id="profile-complete-form" action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        {{-- Hidden Fields --}}
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- No HP --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. Telepon / WA <span class="text-red-500">*</span></label>
                                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon', Auth::user()->nomor_telepon) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500" placeholder="08xxx">
                                @error('nomor_telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Institusi --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Institusi / Instansi <span class="text-red-500">*</span></label>
                                <input type="text" name="institusi" value="{{ old('institusi', Auth::user()->institusi) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500" placeholder="Nama Instansi">
                                @error('institusi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Identitas --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIP / NIK / Identitas <span class="text-red-500">*</span></label>
                                <input type="text" name="nomor_identitas" value="{{ old('nomor_identitas', Auth::user()->nomor_identitas) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500">
                                @error('nomor_identitas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jabatan --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Gelar / Jabatan <span class="text-red-500">*</span></label>
                                <input type="text" name="gelar_jabatan" value="{{ old('gelar_jabatan', Auth::user()->gelar_jabatan) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500">
                                @error('gelar_jabatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Unit Kerja --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Unit Kerja / Departemen <span class="text-red-500">*</span></label>
                                <input type="text" name="department" value="{{ old('department', Auth::user()->department) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500" placeholder="Contoh: Bagian Umum / Fakultas Teknik">
                                @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="alamat" rows="2" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-sky-500 focus:border-sky-500">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                                @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </form>
                </div>

                {{-- 3. FOOTER (Action) --}}
                <div class="flex-none px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center gap-3">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600 font-medium">
                            Keluar
                        </button>
                    </form>

                    <button type="submit" form="profile-complete-form"
                            class="px-6 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-lg shadow transition-colors">
                        Simpan Data
                    </button>
                </div>

            </div>
        </div>

        {{-- Prevent Escape Key --}}
        <script>
            document.addEventListener('keydown', e => { if(e.key === "Escape") e.preventDefault(); });
        </script>
    @endif
@endauth

<script>
    // Initialize application
    async function initializeApp() {
        // Set current date
        const today = new Date();
        document.getElementById("current-date").textContent =
            today.toLocaleDateString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            });

        // Initialize Data SDK
        if (window.dataSdk) {
            const initResult = await window.dataSdk.init(dataHandler);
            if (!initResult.isOk) {
                console.error("Failed to initialize data SDK");
            }
        }

        // Initialize Element SDK
        if (window.elementSdk) {
            await window.elementSdk.init({
                defaultConfig,
                onConfigChange,
                mapToCapabilities,
                mapToEditPanelValues,
            });
        }

        setupEventListeners();
    }

    function setupEventListeners() {
        // Mobile menu toggle
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");

        menuToggle.addEventListener("click", () => {
            sidebar.classList.toggle("sidebar-hidden");
            overlay.classList.toggle("hidden");
        });

        overlay.addEventListener("click", () => {
            sidebar.classList.add("sidebar-hidden");
            overlay.classList.add("hidden");
        });

        // Filter controls
        document
            .getElementById("search")
            .addEventListener("input", applyFilters);
        document
            .getElementById("date-filter")
            .addEventListener("change", applyFilters);
    }

    function applyFilters() {
        const search = document.getElementById("search").value.toLowerCase();
        const dateFilter = document.getElementById("date-filter").value;

        filteredDocuments = documents.filter((doc) => {
            const matchesSearch =
                doc.nama.toLowerCase().includes(search) ||
                doc.deskripsi.toLowerCase().includes(search);
            const matchesDate = !dateFilter || doc.tanggal_upload === dateFilter;

            return matchesSearch && matchesDate;
        });

        renderDocuments();
        updateDocumentCount();
    }

    function renderDocuments() {
        const documentTable = document.getElementById("document-table");
        const documentMobile = document.getElementById("document-mobile");
        const emptyState = document.getElementById("empty-state");

        if (filteredDocuments.length === 0) {
            documentTable.innerHTML = "";
            documentMobile.innerHTML = "";
            emptyState.style.display = "block";
            return;
        }

        emptyState.style.display = "none";

        // Desktop table
        documentTable.innerHTML = filteredDocuments
            .map(
                (doc) => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${
                    doc.nama
                }</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 max-w-xs">${
                    doc.deskripsi
                }</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">${formatDate(
                    doc.tanggal_upload
                )}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center text-sm text-sky-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ${doc.file_name}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="status-badge status-${doc.status}">
                            ${getStatusText(doc.status)}
                        </span>
                    </td>
                </tr>
            `
            )
            .join("");

        // Mobile cards
        documentMobile.innerHTML = filteredDocuments
            .map(
                (doc) => `
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">${
                    doc.nama
                }</h3>
                            <p class="text-sm text-gray-600 mt-1">${
                    doc.deskripsi
                }</p>
                        </div>
                        <span class="status-badge status-${doc.status} ml-2">
                            ${getStatusText(doc.status)}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 space-y-1">
                        <p>Tanggal: ${formatDate(doc.tanggal_upload)}</p>
                        <div class="flex items-center text-sky-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ${doc.file_name}
                        </div>
                    </div>
                </div>
            `
            )
            .join("");
    }

    function updateDocumentCount() {
        document.getElementById("document-count").textContent =
            filteredDocuments.length;
    }

    function getStatusText(status) {
        const statusMap = {
            pending: "Pending",
            valid: "Valid",
            revisi: "Revisi",
        };
        return statusMap[status] || status;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString("id-ID", {
            year: "numeric",
            month: "short",
            day: "numeric",
        });
    }

    // Initialize the application
    initializeApp();
</script>


</body>

</html>
