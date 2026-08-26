<footer class="bg-slate-50 dark:bg-[#0B0F17] text-slate-600 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800/80 pt-14 pb-10 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Info -->
            <div class="space-y-3.5 md:col-span-1">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('flash.png') }}" alt="FlashDev Logo" class="h-8 w-auto">
                    <span class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">FLASH<span class="text-amber-600 dark:text-amber-400">DEV</span></span>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Solusi rekayasa perangkat lunak, sistem web custom, dan platform digital berkinerja tinggi untuk akselerasi bisnis Anda.
                </p>
                <div class="flex space-x-2 pt-1">
                    <a href="#" class="w-8 h-8 rounded-lg bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-400 flex items-center justify-center transition-colors">
                        <span class="text-xs font-bold">X</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-400 flex items-center justify-center transition-colors">
                        <span class="text-xs font-bold">in</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-white dark:bg-[#161F2E] border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-400 flex items-center justify-center transition-colors">
                        <span class="text-xs font-bold">IG</span>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-slate-900 dark:text-slate-100 font-semibold mb-3 text-xs uppercase tracking-wider">
                    Navigasi
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Beranda</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Layanan Spesialis</a></li>
                    <li><a href="{{ route('portfolio') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Portofolio Proyek</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Profil Tim & Perusahaan</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Kontak & Konsultasi</a></li>
                </ul>
            </div>

            <!-- Services Links -->
            <div>
                <h4 class="text-slate-900 dark:text-slate-100 font-semibold mb-3 text-xs uppercase tracking-wider">
                    Layanan
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Aplikasi Web Enterprise</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Sistem E-Commerce B2B/B2C</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Custom SaaS Platform</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">UI/UX & Design Systems</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Maintenance & Cloud Ops</a></li>
                </ul>
            </div>

            <!-- Contact Summary -->
            <div>
                <h4 class="text-slate-900 dark:text-slate-100 font-semibold mb-3 text-xs uppercase tracking-wider">
                    Kontak
                </h4>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-amber-600 dark:text-amber-400">location_on</span>
                        <span>Karawang, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-amber-600 dark:text-amber-400">mail</span>
                        <span>hallo.flashdev@flash14.id</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-amber-600 dark:text-amber-400">call</span>
                        <span>+62 821-2861-6647</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-200 dark:border-slate-800 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} FlashDev. All rights reserved.</p>
            <div class="flex space-x-5 mt-3 sm:mt-0">
                <a href="#" class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">Syarat & Ketentuan</a>
                @guest
                    <a href="{{ route('login') }}" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors">Admin Login</a>
                @endguest
            </div>
        </div>
    </div>
</footer>
