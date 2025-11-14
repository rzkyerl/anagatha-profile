@extends('layouts.app')

@section('title', 'Anagata Executive | Where Data Meet Talent')
@section('body_class', 'page home-page')

@section('content')
<section id="hero" class="hero home-hero">
    <div class="container hero__layout">
        <div class="hero-text">
            <span class="hero-eyebrow">Talent Intelligence Partner</span>
            <h1>Where Data Meet Talent</h1>
            <p class="text-lead">Menemukan talenta terbaik kini lebih cepat, akurat, dan berbasis data. Anagata Executive menghadirkan solusi rekrutmen end-to-end yang menggabungkan kecerdasan buatan, analitik, dan sentuhan manusiawi.</p>
            <div class="cta-group">
                <a class="cta-primary" href="{{ url('/#contact') }}">Hubungi Kami</a>
                <a class="cta-secondary" href="{{ url('/#about') }}">Pelajari Lebih Lanjut</a>
            </div>
        </div>
        <div class="hero-visual">
            <img class="hero-visual__image" src="/assets/hero-section.jpeg" alt="Ilustrasi tim Anagata Executive yang berkolaborasi">
        </div>
    </div>
</section>

<section id="about">
    <div class="container section-shell section-shell--center section-shell--narrow">
        <h1>Manusia Tetap Pusat Dari Segalanya</h1>
        <p class="text-lead">Kami membantu organisasi menghubungkan data dan bakat, sehingga keputusan rekrutmen menjadi lebih strategis dan berkelanjutan.</p>
    </div>
</section>

<section>
    <div class="container">
        <article class="card quote-card">
            <blockquote>
                “Kami percaya, keberhasilan rekrutmen bukan hanya tentang menemukan orang yang tepat, tetapi juga membangun hubungan jangka panjang antara talenta dan perusahaan. Teknologi hanyalah alat; manusia tetap pusat dari segalanya.”
            </blockquote>
            <p class="quote-card__attribution">— Founder, Anagata Executive</p>
        </article>
    </div>
</section>

<section aria-labelledby="vision-mission">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading section-heading--left">
            <h2 id="vision-mission" class="section-heading__title">Visi & Misi</h2>
            <p class="section-heading__text">Fondasi yang memandu kami membantu klien mencapai pertumbuhan berkelanjutan.</p>
        </div>
        <div class="grid-columns grid-columns--balanced">
            <article class="content-block">
                <h3>Visi</h3>
                <p>Menjadi mitra strategis terpercaya dalam penyediaan talenta terbaik di berbagai industri.</p>
            </article>
            <article class="content-block">
                <h3>Misi</h3>
                <ol class="list list--numbered">
                    <li><span>1</span><div>Menghubungkan talenta unggul dengan peluang terbaik.</div></li>
                    <li><span>2</span><div>Membangun hubungan jangka panjang yang saling menguntungkan.</div></li>
                    <li><span>3</span><div>Menggunakan teknologi dan data sebagai penguat keputusan.</div></li>
                    <li><span>4</span><div>Memberikan nilai tambah nyata bagi klien.</div></li>
                    <li><span>5</span><div>Mendukung pengembangan karier kandidat.</div></li>
                </ol>
            </article>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <article class="card card--muted card--narrow">
            <h2>Sejarah Perusahaan</h2>
            <p>Didirikan tahun 2025 oleh profesional di bidang teknologi dan SDM, Anagata Executive hadir untuk mengubah cara rekrutmen dilakukan. Dengan menggabungkan <strong>AI, analitik data, dan pemahaman manusia</strong>, kami membantu perusahaan menemukan kandidat yang tidak hanya kompeten, tetapi juga cocok secara budaya dan visi.</p>
        </article>
    </div>
</section>

<section aria-labelledby="culture">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading">
            <span class="section-heading__eyebrow">Cara Kami Bekerja</span>
            <h2 id="culture" class="section-heading__title">Budaya Kami</h2>
            <p class="section-heading__text">Nilai yang menuntun setiap interaksi dengan klien maupun kandidat.</p>
        </div>
        <div class="grid-cards">
            <article class="card card--lift">
                <h3>Kolaborasi Tanpa Batas</h3>
                <p>Kami percaya inovasi lahir dari kolaborasi lintas disiplin untuk memecahkan tantangan rekrutmen kompleks.</p>
            </article>
            <article class="card card--lift">
                <h3>Empati & Integritas</h3>
                <p>Setiap interaksi dengan kandidat dan klien kami dasarkan pada empati, kepercayaan, dan tanggung jawab.</p>
            </article>
            <article class="card card--lift">
                <h3>Data-Driven Mindset</h3>
                <p>Keputusan kami ditunjang oleh data yang kuat tanpa mengabaikan intuisi dan pengalaman profesional.</p>
            </article>
        </div>
    </div>
</section>

<section id="services" aria-labelledby="services-title">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading">
            <span class="section-heading__eyebrow">Solusi Talent Terintegrasi</span>
            <h2 id="services-title" class="section-heading__title">Layanan Kami</h2>
            <p class="section-heading__text">Kami menggabungkan keahlian headhunter dengan teknologi analitik untuk memastikan setiap layanan menghadirkan nilai strategis paling tinggi bagi organisasi Anda.</p>
        </div>

        <div class="grid-cards grid-cards--wide">
            <article class="card card--service">
                <h3>Head Hunter</h3>
                <p>Kami membantu Anda mendapatkan kandidat terbaik untuk posisi strategis dengan metode pencarian dan seleksi berbasis data, mempercepat waktu rekrutmen tanpa mengorbankan kualitas.</p>
                <a class="cta-secondary" href="{{ url('/#contact') }}">Diskusikan Kebutuhan</a>
            </article>
            <article class="card card--service">
                <h3>Training</h3>
                <p>Program pelatihan yang dirancang untuk meningkatkan kemampuan profesional dan kepemimpinan, disesuaikan dengan kebutuhan industri dan budaya organisasi.</p>
                <a class="cta-secondary" href="{{ url('/#contact') }}">Rancang Program</a>
            </article>
            <article class="card card--service">
                <h3>Outsourcing</h3>
                <p>Solusi efisien untuk kebutuhan tenaga kerja sementara maupun tetap, dilengkapi sistem pemantauan kinerja dan manajemen HR digital.</p>
                <a class="cta-secondary" href="{{ url('/#contact') }}">Cari Talenta</a>
            </article>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="cta-banner cta-banner--split">
            <div>
                <h2>Butuh kombinasi layanan?</h2>
                <p>Kami dapat menyesuaikan paket layanan sesuai kebutuhan unik tim Anda.</p>
            </div>
            <a class="cta-primary" href="{{ url('/#contact') }}">Hubungi Konsultan</a>
        </div>
    </div>
</section>

<section id="why-us">
    <div class="container section-shell section-shell--split">
        <article>
            <h2 class="section-heading__title">Integrasi Teknologi & Empati</h2>
            <p class="text-lead">Kami tidak sekadar menemukan kandidat. Kami membangun kemitraan jangka panjang untuk memastikan talenta yang hadir mampu membawa dampak nyata bagi perusahaan Anda.</p>
        </article>
        <article class="card card--muted">
            <h3 class="card__title">Value Proposition</h3>
            <p>Kami menyatukan data, pengalaman industri, dan pemahaman mendalam terhadap manusia untuk memberikan keputusan rekrutmen yang lebih cerdas dan cepat.</p>
        </article>
    </div>
</section>

<section aria-labelledby="advantages">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading">
            <span class="section-heading__eyebrow">Alasan Memilih Kami</span>
            <h2 id="advantages" class="section-heading__title">Keunggulan Perusahaan</h2>
        </div>
        <ul class="list list--grid list--numbered">
            <li><span>1</span><div>Proses cepat dan efisien untuk mengisi posisi kritikal.</div></li>
            <li><span>2</span><div>Standar kualitas kandidat yang tinggi dan relevan.</div></li>
            <li><span>3</span><div>Pengambilan keputusan berbasis data dan insight pasar terkini.</div></li>
            <li><span>4</span><div>Jangkauan kandidat luas lintas industri dan wilayah.</div></li>
            <li><span>5</span><div>Personalisasi yang memperhatikan kecocokan budaya.</div></li>
            <li><span>6</span><div>Transparansi dan akurasi tinggi dalam setiap proses.</div></li>
            <li><span>7</span><div>Analisis pasar tenaga kerja untuk strategi rekrutmen jangka panjang.</div></li>
            <li><span>8</span><div>Integrasi mulus dengan sistem HR klien.</div></li>
            <li><span>9</span><div>Efisiensi biaya berkat proses seleksi yang terukur.</div></li>
            <li><span>10</span><div>Continuous learning system untuk meningkatkan kualitas layanan.</div></li>
        </ul>
    </div>
</section>

<section>
    <div class="container">
        <div class="cta-banner cta-banner--home">
            <span class="cta-banner__badge">Talent Intelligence Partner</span>
            <h2>Siap menemukan talenta terbaik?</h2>
            <p>Kami hadir sebagai mitra strategis untuk mendukung transformasi talent acquisition perusahaan Anda.</p>
            <a class="cta-primary cta-primary--glow" href="{{ url('/#contact') }}">Konsultasikan Sekarang</a>
        </div>
    </div>
</section>

<section id="contact">
    <div class="container section-shell contact-section">
        <div class="section-heading section-heading--left contact-section__heading">
            <span class="section-heading__eyebrow">Hubungi Kami</span>
            <h2 class="section-heading__title">Mulai Kolaborasi Bersama Anagata Executive</h2>
            <p class="section-heading__text">Kirimkan kebutuhan talent acquisition Anda. Tim kami siap membantu menyusun solusi yang paling tepat untuk organisasi Anda.</p>
        </div>
        <div class="contact-section__grid">
            <div class="card card--form">
            @if (session('status'))
                <div class="alert alert--success" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ url('/contact') }}" method="POST" novalidate>
                @csrf
                <div class="form-field">
                    <label for="name">Nama</label>
                    <div class="input-with-icon">
                        <span class="input-icon" aria-hidden="true">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required autocomplete="name">
                    </div>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <span class="input-icon" aria-hidden="true">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" required autocomplete="email">
                    </div>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-grid">
                    <div class="form-field">
                        <label for="phone">Telepon (opsional)</label>
                        <div class="input-with-icon">
                            <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Nomor yang bisa dihubungi" autocomplete="tel">
                        </div>
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="form-field">
                    <label for="message">Pesan</label>
                    <div class="input-with-icon textarea-with-icon">
                        <span class="input-icon" aria-hidden="true">
                            <i class="fa-solid fa-comment-dots"></i>
                        </span>
                        <textarea id="message" name="message" placeholder="Ceritakan kebutuhan talent Anda" required rows="4">{{ old('message') }}</textarea>
                    </div>
                    @error('message')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <p class="form-helper">Kami biasanya merespons dalam 1 hari kerja.</p>
                <button type="submit" class="cta-primary">Kirim Pesan</button>
            </form>
            </div>
            <aside class="info-card contact-info" aria-label="Informasi kontak perusahaan">
                <div>
                    <h3>Kantor Pusat</h3>
                    <p>Jl. [Nama Jalan], Jakarta, Indonesia</p>
                </div>
                <div class="contact-list">
                    <p><strong>Telepon:</strong> +62 [Nomor]</p>
                    <p><strong>Email:</strong> info@anagataexecutive.com</p>
                </div>
                <div>
                    <h3>Lokasi Kantor</h3>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.812526675196!2d106.81666651086821!3d-6.151341759427834!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMDknMDQuOCJTIDEwNsKwNDknMDQuMCJF!5e0!3m2!1sen!2sid!4v1731234567890" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi kantor Anagata Executive"></iframe>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection