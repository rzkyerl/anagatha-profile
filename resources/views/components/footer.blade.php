<footer>
    <div class="container footer-grid">
        <div class="footer-branding">
            <p class="footer-brand">Anagata Executive</p>
            <p>Where Data Meet Talent</p>
            <p class="footer-description">Kami menjadi partner strategis untuk organisasi yang ingin menghubungkan data dan manusia dalam proses rekrutmen.</p>
        </div>
        <nav class="footer-links" aria-label="Navigasi footer">
            <a href="{{ url('/#hero') }}">Home</a>
            <a href="{{ url('/#about') }}">About Us</a>
            <a href="{{ url('/#services') }}">Services</a>
            <a href="{{ url('/#why-us') }}">Why Choose Us</a>
            <a href="{{ url('/#contact') }}">Contact</a>
        </nav>
        <div class="footer-contacts">
            <p class="footer-contact">
                <span class="footer-icon" aria-hidden="true">
                    <i class="fa-solid fa-phone"></i>
                </span>
                <span>+62 [Nomor]</span>
            </p>
            <p class="footer-contact">
                <span class="footer-icon" aria-hidden="true">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <span>info@anagataexecutive.com</span>
            </p>
            <p class="footer-contact">
                <span class="footer-icon" aria-hidden="true">
                    <i class="fa-solid fa-location-dot"></i>
                </span>
                <span>Jl. [Nama Jalan], Jakarta, Indonesia</span>
            </p>
        </div>
        <div class="footer-cta">
            <p class="footer-cta__title">Perlu bantuan cepat?</p>
            <p class="footer-cta__text">Hubungi tim kami untuk sesi konsultasi singkat dan temukan solusi terbaik.</p>
            <a class="footer-cta__button" href="{{ url('/#contact') }}">Jadwalkan Konsultasi</a>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>&copy; {{ date('Y') }} Anagata Executive. All rights reserved.</p>
        <div class="footer-meta">
            <a href="{{ url('/#hero') }}">Kembali ke atas</a>
            <a href="mailto:info@anagataexecutive.com">Email kami</a>
        </div>
    </div>
</footer>


