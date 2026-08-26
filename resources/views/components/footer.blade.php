<footer class="bg-surface-container-low text-on-surface-variant border-t border-outline-variant pt-14 pb-10 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Info -->
            <div class="space-y-3.5 md:col-span-1">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('flash.png') }}" alt="FlashDev Logo" class="h-8 w-auto">
                    <span class="text-lg font-bold tracking-tight text-on-surface">FLASH<span class="text-primary">DEV</span></span>
                </div>
                <p class="text-xs text-on-surface-variant leading-relaxed">
                    Solusi rekayasa perangkat lunak, sistem web custom, dan platform digital berkinerja tinggi untuk akselerasi bisnis Anda.
                </p>
                <div class="flex space-x-2 pt-1">
                    <a href="#" class="w-8 h-8 rounded-lg bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:text-primary hover:border-primary/40 flex items-center justify-center transition-colors">
                        <span class="text-xs font-bold">X</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:text-primary hover:border-primary/40 flex items-center justify-center transition-colors">
                        <span class="text-xs font-bold">in</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-surface-container-lowest border border-outline-variant text-on-surface-variant hover:text-primary hover:border-primary/40 flex items-center justify-center transition-colors">
                        <span class="text-xs font-bold">IG</span>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-on-surface font-semibold mb-3 text-xs uppercase tracking-wider">
                    Navigasi
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-primary transition-colors">Layanan Spesialis</a></li>
                    <li><a href="{{ route('portfolio') }}" class="hover:text-primary transition-colors">Portofolio Proyek</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-primary transition-colors">Profil Tim & Perusahaan</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary transition-colors">Kontak & Konsultasi</a></li>
                </ul>
            </div>

            <!-- Services Links -->
            <div>
                <h4 class="text-on-surface font-semibold mb-3 text-xs uppercase tracking-wider">
                    Layanan
                </h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="{{ route('services') }}" class="hover:text-primary transition-colors">Aplikasi Web Enterprise</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-primary transition-colors">Sistem E-Commerce B2B/B2C</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-primary transition-colors">Custom SaaS Platform</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-primary transition-colors">UI/UX & Design Systems</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-primary transition-colors">Maintenance & Cloud Ops</a></li>
                </ul>
            </div>

            <!-- Contact Summary -->
            <div>
                <h4 class="text-on-surface font-semibold mb-3 text-xs uppercase tracking-wider">
                    Kontak
                </h4>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-primary">location_on</span>
                        <span>Karawang, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-primary">mail</span>
                        <span>hallo.flashdev@flash14.id</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-primary">call</span>
                        <span>+62 821-2861-6647</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-outline-variant mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-on-surface-variant">
            <p>&copy; {{ date('Y') }} FlashDev. All rights reserved.</p>
            <div class="flex space-x-5 mt-3 sm:mt-0">
                <a href="#" class="hover:text-on-surface transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-on-surface transition-colors">Syarat & Ketentuan</a>
                @guest
                    <a href="{{ route('login') }}" class="hover:text-primary transition-colors">Admin Login</a>
                @endguest
            </div>
        </div>
    </div>
</footer>
