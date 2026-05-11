<header class="h-[80px] bg-surface border-b border-custom flex items-center justify-between px-6 shrink-0 sticky top-0 z-10">

    {{-- LEFT: Toggle + Title --}}
    <div class="flex items-center gap-4">

        {{-- ☰ Toggle Sidebar (Mobile) --}}
        <button onclick="toggleSidebar()"
            class="btn-icon md:hidden text-[var(--text-main)]">
            <iconify-icon icon="solar:hamburger-menu-bold-duotone" width="26"></iconify-icon>
        </button>

        {{-- Title --}}
        <div>
            <h3 class="m-0 leading-tight text-[var(--text-main)] font-bold">
                {{ $header ?? 'Dashboard' }}
            </h3>
            <div class="text-xs font-semibold text-[var(--text-tertiary)] mt-0.5 uppercase tracking-wide hidden sm:block">
                {{ $subheader ?? '' }}
            </div>
        </div>
    </div>

    {{-- RIGHT: Actions --}}
    <div class="flex items-center gap-3 sm:gap-5">

        {{-- 🔍 Search --}}
        <div class="relative items-center hidden md:flex">
            <iconify-icon icon="solar:magnifier-linear"
                class="absolute left-3.5 text-[var(--text-tertiary)]"
                width="20"></iconify-icon>

            <input type="text"
                placeholder="Cari..."
                class="input-solid pl-11 py-2 w-64 rounded-full border-transparent bg-[var(--bg)] focus:bg-surface">
        </div>

        {{-- 🌙 Theme Toggle --}}
        <button onclick="toggleTheme()"
            class="btn-icon bg-[var(--bg)] rounded-full hover:bg-[var(--border)]"
            id="theme-toggle">
            <iconify-icon icon="solar:moon-bold-duotone" width="22"></iconify-icon>
        </button>

        {{-- 🔔 Notification --}}
        <button class="btn-icon relative bg-[var(--bg)] rounded-full hover:bg-[var(--border)]">
            <iconify-icon icon="solar:bell-bold-duotone" width="22"
                class="text-[var(--text-main)]"></iconify-icon>

            {{-- badge --}}
            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-surface"></span>
        </button>

        {{-- 👤 User Dropdown --}}
        <div class="relative">
            <button onclick="toggleUserDropdown()"
                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[var(--bg)] transition">

                <div class="flex items-center justify-center font-bold rounded-full w-9 h-9 text-primary-custom"
                     style="background-color: var(--primary-light);">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>

                <div class="hidden text-left sm:block">
                    <div class="text-sm font-bold text-[var(--text-main)]">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-xs text-[var(--text-tertiary)]">
                        @php
                            $roleLabels = [
                                'siswa' => 'Siswa',
                                'guru' => 'Guru',
                                'orang_tua' => 'Orang Tua',
                                'superadmin' => 'Superadmin',
                            ];
                        @endphp

                        {{ $roleLabels[Auth::user()->getRoleNames()->first()] ?? '-' }}

                    </div>
                </div>

                <iconify-icon icon="solar:alt-arrow-down-bold-duotone" width="18"></iconify-icon>
            </button>

            {{-- Dropdown --}}
            <div id="userDropdown"
                class="absolute right-0 z-50 hidden w-48 mt-2 overflow-hidden border shadow-md bg-surface border-custom rounded-xl">

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-sm hover:bg-[var(--bg)]">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-[var(--bg)] text-red-500">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>
</header>