<aside id="sidebar"
    class="w-[280px] bg-surface border-r border-custom flex flex-col h-full
           fixed md:relative z-30 transform -translate-x-full md:translate-x-0 transition-all duration-300">

    @php
        $user = Auth::user();
    @endphp

    <!-- 🔷 LOGO -->
    <div class="h-[80px] flex items-center justify-between px-6 border-b border-custom shrink-0">

        {{-- LEFT --}}
        <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 text-primary-custom">

            <div class="w-10 h-10 rounded-xl bg-[var(--primary)] text-white flex items-center justify-center shadow-md">
                <iconify-icon
                    icon="solar:moon-stars-bold-duotone"
                    width="24">
                </iconify-icon>
            </div>

            <div>

                <h2 class="text-[1.1rem] font-bold leading-tight">
                    TPA/DTA
                </h2>

                <span class="text-[0.65rem] font-bold text-[var(--text-tertiary)] uppercase tracking-widest">
                    Al-Barokah
                </span>

            </div>

        </a>

        {{-- MOBILE CLOSE --}}
        <button class="md:hidden btn-icon text-[var(--text-main)]"
            onclick="toggleSidebar()">

            <iconify-icon
                icon="solar:close-circle-bold-duotone"
                width="24">
            </iconify-icon>

        </button>

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

        
            <a href="/users"
            class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                Kelola User
            </a>

            <a href="/payments"
                class="nav-item {{ request()->is('payments*') || request()->is('students/*/payments') ? 'active' : '' }}">
                <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                Iuran Bulanan
            </a>

            <div class="px-4 mt-6 mb-3 text-caption">Pendaftaran</div>
            
            <a href="/approval/students"
            class="nav-item {{ request()->is('approval/students') || request()->is('approval/students/*') && !request()->is('approval/students/rejected*') ? 'active' : '' }}">
                <iconify-icon icon="solar:user-check-bold-duotone"></iconify-icon>
                Approval
            </a>

            <a href="/approval/students/rejected"
            class="nav-item {{ request()->is('approval/students/rejected*') ? 'active' : '' }}">
                <iconify-icon icon="solar:user-cross-bold-duotone"></iconify-icon>
                Ditolak
            </a>

            <div class="px-4 mt-6 mb-3 text-caption">Akademik</div>

            <a href="/learning"
                class="nav-item {{ request()->is('learning*') ? 'active' : '' }}">
                <iconify-icon icon="solar:book-bookmark-bold-duotone"></iconify-icon>
                Pembelajaran
            </a>
            
            <a href="{{ route('promotions.index') }}"
                class="nav-item {{ request()->is('promotions*') ? 'active' : '' }}">
                <iconify-icon icon="solar:medal-ribbons-star-bold-duotone"></iconify-icon>
                Kenaikan Kelas
            </a>

            <a href="{{ route('academic-years.index') }}"
                class="nav-item {{ request()->is('academic-years*') ? 'active' : '' }}">
                <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                Tahun Akademik
            </a>
        
            <div class="px-4 mt-6 mb-3 text-caption">Pengaturan</div>

            <a href="/fees"
            class="nav-item {{ request()->is('fees*') ? 'active' : '' }}">
                <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                Biaya
            </a>

        @endif

        <!-- ================= GURU ================= -->
        @if($user->hasRole('guru'))

            <div class="px-4 mt-6 mb-3 text-caption">AKADEMIK</div>

            <a href="/attendances"
                class="nav-item {{ request()->is('attendances/*') ? 'active' : '' }}">
                <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>
                Absensi
            </a>

            <a href="{{ route('attendance.recap') }}"
                class="nav-item {{ request()->is('attendance-recaps*') ? 'active' : '' }}">
                <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                Rekap Absensi
            </a>

            <a href="/learning"
                class="nav-item {{ request()->is('learning*') ? 'active' : '' }}">
                <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
                Pembelajaran
            </a>

        @endif

        <!-- ================= ORANG TUA ================= -->
        @if($user->hasRole('orang_tua'))

            <a href="/students"
               class="nav-item {{ request()->is('students*') ? 'active' : '' }}">
                <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                Kelola Anak
            </a>

            <a href="/payments"
               class="nav-item {{ request()->is('payments*') ? 'active' : '' }}">
                <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                Iuran Bulanan
            </a>

            <div class="px-4 mt-6 mb-3 text-caption">Akademik</div>

            <a href="{{ route('attendance.recap') }}"
                class="nav-item {{ request()->is('attendance-recaps*') ? 'active' : '' }}">
                <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                Rekap Absensi
            </a>

            <a href="/learning"
                class="nav-item {{ request()->is('learning*') ? 'active' : '' }}">
                <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
                Pembelajaran
            </a>

        @endif

        <!-- ================= SISWA ================= -->
        @if($user->hasRole('siswa'))
        
{{--         
            <a href="{{ route('attendance.recap') }}"
                class="nav-item {{ request()->is('attendance-recaps*') ? 'active' : '' }}">
                <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                Rekap Absensi
            </a> --}}

            <a href="/learning"
                class="nav-item {{ request()->is('learning*') ? 'active' : '' }}">
                <iconify-icon icon="solar:notebook-bold-duotone"></iconify-icon>
                Pembelajaran
            </a>

        @endif

    </div>

    <!-- 🔷 USER PROFILE -->
    <div class="relative p-3 m-4 border-t border-custom bg-[var(--bg)] rounded-xl"
        id="sidebarUserDropdownWrapper">

        {{-- TRIGGER --}}
        <button onclick="toggleSidebarUserDropdown()"
            class="flex items-center w-full gap-3 transition rounded-xl hover:bg-[var(--primary-light)] p-2">

            {{-- AVATAR --}}
            <div class="flex items-center justify-center w-10 h-10 font-bold border-2 rounded-full text-primary-custom border-surface"
                style="background-color: var(--primary-light);">

                {{ strtoupper(substr($user->name, 0, 2)) }}

            </div>

            {{-- INFO --}}
            <div class="flex-1 overflow-hidden text-left">

                <div class="text-sm font-bold truncate text-[var(--text-main)]">
                    {{ $user->name }}
                </div>

                <div class="text-xs capitalize text-[var(--text-tertiary)]">
                    {{ str_replace('_', ' ', $user->getRoleNames()->first()) }}
                </div>

            </div>

            {{-- ICON --}}
            <iconify-icon
                icon="solar:alt-arrow-up-bold-duotone"
                width="18"
                class="text-[var(--text-tertiary)]">
            </iconify-icon>

        </button>

        {{-- DROPDOWN --}}
        <div id="sidebarUserDropdown"
            class="absolute left-0 right-0 z-50 hidden mx-2 mt-3 overflow-hidden border shadow-lg bottom-full bg-surface border-custom rounded-xl">

            {{-- PROFILE --}}
            <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-2 px-4 py-3 text-sm transition text-[var(--text-main)] hover:bg-[var(--primary-light)] hover:text-[var(--primary)]">

                <iconify-icon
                    icon="solar:user-bold-duotone"
                    width="18">
                </iconify-icon>

                Profile

            </a>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="flex items-center w-full gap-2 px-4 py-3 text-sm text-left text-red-500 transition hover:bg-red-500/10">

                    <iconify-icon
                        icon="solar:logout-bold-duotone"
                        width="18">
                    </iconify-icon>

                    Logout

                </button>

            </form>

        </div>

    </div>

</aside>
{{-- SCRIPT --}}
<script>

    function toggleSidebarUserDropdown() {

        document
            .getElementById('sidebarUserDropdown')
            .classList
            .toggle('hidden');

    }

    document.addEventListener('click', function(e) {

        const wrapper = document.getElementById('sidebarUserDropdownWrapper');

        const dropdown = document.getElementById('sidebarUserDropdown');

        if (!wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }

    });

</script>