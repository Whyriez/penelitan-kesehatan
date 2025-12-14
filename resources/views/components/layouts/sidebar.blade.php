<div id="sidebar"
     class="sidebar-transition bg-white border-r border-gray-200 w-64 h-full flex flex-col fixed md:relative z-20 md:translate-x-0 sidebar-hidden md:sidebar-visible shadow-sm">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('img/logo.jpg') }}"
                 alt="Logo"
                 class="mx-auto w-10 h-15" />
            <div>
                <h2 id="system-name" class="text-lg font-medium text-gray-900">
                    Penelitian Kesehatan
                </h2>
                <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }} Panel</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4">
        <ul class="space-y-1">
            @php
                $dashboardRoute = 'user.index';
                if (Auth::user()->role === 'admin') {
                    $dashboardRoute = 'admin.index';
                } elseif (Auth::user()->role === 'operator') {
                    $dashboardRoute = 'operator.index';
                }

                $dashboardRoutes = ['admin.index', 'operator.index', 'user.index'];
            @endphp
            <li>
                <a href="{{ route($dashboardRoute) }}" @class([
                    'flex items-center space-x-3 px-3 py-2 rounded-md transition-colors',
                    'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                        $dashboardRoutes),

                    'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs(
                        $dashboardRoutes),
                ])>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->role === 'admin')
                <li>
                    <a href="{{ route('admin.dokumen_masuk') }}" @class([
                        'flex items-center space-x-3 px-3 py-2 rounded-md',
                        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                            'admin.dokumen_masuk'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                            'admin.dokumen_masuk'),
                    ])>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="text-sm">Data Dokumen Masuk</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.jenis_izin.index') }}" @class([
        'flex items-center space-x-3 px-3 py-2 rounded-md',
        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs('admin.jenis_izin.*'),
        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs('admin.jenis_izin.*'),
    ])>
                        {{-- Icon Dokumen / Sertifikat --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="text-sm">Jenis Izin</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}" @class([
                        'flex items-center space-x-3 px-3 py-2 rounded-md',
                        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                            'admin.users.index'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                            'admin.users.index'),
                    ])>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span class="text-sm">Kelola User</span>
                    </a>
                </li>

            @elseif(Auth::user()->role === 'operator')
                <li>
                    <a href="{{ route('operator.dokumen_masuk') }}" @class([
                        'flex items-center space-x-3 px-3 py-2 rounded-md',
                        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                            'operator.dokumen_masuk'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                            'operator.dokumen_masuk'),
                    ])>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="text-sm">Data Dokumen Masuk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('operator.validasi_dokumen') }}" @class([
                        'flex items-center space-x-3 px-3 py-2 rounded-md',
                        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                            'operator.validasi_dokumen'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                            'operator.validasi_dokumen'),
                    ])>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">Validasi Dokumen</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('user.upload') }}" @class([
                        'flex items-center space-x-3 px-3 py-2 rounded-md',
                        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                            'user.upload'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                            'user.upload'),
                    ])>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <span class="text-sm">Upload Dokumen</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.riwayat') }}" @class([
                        'flex items-center space-x-3 px-3 py-2 rounded-md',
                        'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                            'user.riwayat'),
                        'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                            'user.riwayat'),
                    ])>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">Riwayat</span>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('profile') }}" @class([
                    'flex items-center space-x-3 px-3 py-2 rounded-md',
                    'bg-green-50 text-green-700 border border-green-100' => request()->routeIs(
                        'profile'),
                    'text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors' => !request()->routeIs(
                        'profile'),
                ])>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm">Profil</span>
                </a>
            </li>

        </ul>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="flex w-full items-center space-x-3 px-3 py-2 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="text-sm">Logout</span>
            </button>
        </form>
    </div>
</div>
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-25 z-10 hidden md:hidden"></div>
