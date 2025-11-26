<footer>
    <div class="container footer-grid">
        <div class="footer-branding" data-aos="fade-up" data-aos-delay="0">
            <h3 class="footer-brand">Anagata Executive</h3>
            <p class="footer-tagline">{{ __('app.footer.tagline') }}</p>
        </div>
        
        <nav class="footer-links" aria-label="{{ __('app.footer.nav_label') }}" data-aos="fade-up" data-aos-delay="50">
            <a href="{{ route('home') }}">{{ __('app.nav.home') }}</a>
            <a href="{{ route('about') }}">{{ __('app.nav.about') }}</a>
            <a href="{{ route('services') }}">{{ __('app.nav.services') }}</a>
            <a href="{{ route('why-us') }}">{{ __('app.nav.why_us') }}</a>
            <a href="{{ route('contact') }}">{{ __('app.nav.contact') }}</a>
        </nav>
        
        <div class="footer-contacts" data-aos="fade-up" data-aos-delay="100">
            <div class="footer-contact">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                <a href="mailto:info@anagataexecutive.co.id">info@anagataexecutive.co.id</a>
            </div>
            <div class="footer-contact">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <a href="https://wa.me/6282125518551" target="_blank" rel="noopener">+62 821-2551-8551</a>
            </div>
            <div class="footer-contact">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <span>Multimedia Nusantara University, New Media Tower Lv.11 & 12, Jl. Boulevard Raya Gading Serpong, Kec. Kelapa Dua, Kab. Tangerang, Banten 15811</span>
            </div>
        </div>
    </div>
    
    <div class="container footer-bottom" data-aos="fade-up" data-aos-delay="150">
        <p>&copy; {{ date('Y') }} Anagata Executive. {{ __('app.footer.rights') }}</p>
    </div>
</footer>


