<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&amp;display=swap" rel="stylesheet" />
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
