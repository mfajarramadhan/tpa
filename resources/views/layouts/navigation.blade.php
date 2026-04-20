<aside id="sidebar"
    class="w-[280px] bg-surface border-r border-custom flex flex-col h-full
           fixed md:relative z-30 transform -translate-x-full md:translate-x-0 transition-all duration-300">

    @php
        $user = Auth::user();
    @endphp

    <!-- 🔷 LOGO -->
    <div class="h-[80px] flex items-center px-6 border-b border-custom shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-primary-custom">
            <div class="w-10 h-10 rounded-xl bg-[var(--primary)] text-white flex items-center justify-center shadow-md">
                <iconify-icon icon="solar:moon-stars-bold-duotone" width="24"></iconify-icon>
            </div>
            <div>
                <h2 class="text-[1.1rem] font-bold leading-tight">TPA/DTA</h2>
                <span class="text-[0.65rem] font-bold text-[var(--text-tertiary)] uppercase tracking-widest">
                    Miftahul Jannah
                </span>
            </div>
        </a>
    </div>

    <!-- 🔷 MENU -->
    <div class="flex-1 px-4 py-6 overflow-y-auto">

        <div class="px-4 mb-3 text-caption">Menu Utama</div>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <iconify-icon icon="solar:home-2-bold-duotone"></iconify-icon>
            Dashboard
        </a>

        <!-- ================= SUPERADMIN ================= -->
        @if($user->hasRole('superadmin'))

            <a href="/approval/students"
               class="nav-item {{ request()->is('approval/*') ? 'active' : '' }}">
                <iconify-icon icon="solar:user-check-bold-duotone"></iconify-icon>
                Approval
            </a>

            <a href="/payments"
               class="nav-item {{ request()->is('payments*') ? 'active' : '' }}">
                <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                Iuran
            </a>

            <a href="/users"
               class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                Kelola User
            </a>

            <a href="/students/rejected"
               class="nav-item {{ request()->is('students/rejected') ? 'active' : '' }}">
                <iconify-icon icon="solar:user-cross-bold-duotone"></iconify-icon>
                Siswa Ditolak
            </a>

            <a href="/fees"
               class="nav-item {{ request()->is('fees*') ? 'active' : '' }}">
                <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                Pengaturan Biaya
            </a>

        @endif

        <!-- ================= GURU ================= -->
        @if($user->hasRole('guru'))

            <a href="/attendances/create"
               class="nav-item {{ request()->is('attendances/*') ? 'active' : '' }}">
                <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>
                Absensi
            </a>

            <a href="/assignments"
               class="nav-item {{ request()->is('assignments*') ? 'active' : '' }}">
                <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
                Tugas
            </a>

        @endif

        <!-- ================= ORANG TUA ================= -->
        @if($user->hasRole('orang_tua'))

            <a href="/students"
               class="nav-item {{ request()->is('students*') ? 'active' : '' }}">
                <iconify-icon icon="solar:file-check-bold-duotone"></iconify-icon>
                Anak
            </a>

            <a href="/payments"
               class="nav-item {{ request()->is('payments*') ? 'active' : '' }}">
                <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                Iuran
            </a>

        @endif

        <!-- ================= SISWA ================= -->
        @if($user->hasRole('siswa'))

            <a href="/assignments"
               class="nav-item {{ request()->is('assignments*') ? 'active' : '' }}">
                <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
                Tugas
            </a>

        @endif

    </div>

    <!-- 🔷 USER PROFILE -->
    <div class="p-5 border-t border-custom bg-[var(--bg)] m-4 rounded-xl">

        <div class="flex items-center gap-3">

            <!-- Avatar -->
            <div class="flex items-center justify-center w-10 h-10 font-bold border-2 rounded-full text-primary-custom border-surface"
                 style="background-color: var(--primary-light);">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <!-- Info -->
            <div class="flex-1 overflow-hidden">
                <div class="text-sm font-bold truncate text-[var(--text-main)]">
                    {{ $user->name }}
                </div>
                <div class="text-xs text-[var(--text-tertiary)]">
                    {{ $user->getRoleNames()->first() }}
                </div>
            </div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-8 h-8 btn-icon text-danger hover:bg-danger-light">
                    <iconify-icon icon="solar:logout-bold-duotone" width="20"></iconify-icon>
                </button>
            </form>

        </div>
    </div>

</aside>