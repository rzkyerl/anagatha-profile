<section id="contact" class="page-section page-section--flush-top">
    <div class="container section-shell contact-section">
        <div class="section-heading contact-section__heading" data-aos="fade-up">
            <h2 class="section-heading__title">{{ __('app.contact.title') }}</h2>
            <p class="section-heading__text">{{ __('app.contact.subtitle') }}</p>
        </div>
        <div class="contact-section__grid">
            <div class="card card--form" data-aos="fade-up" data-aos-delay="50">
                <form action="{{ url('/contact') }}" method="POST" novalidate>
                    @csrf
                    {{-- Honeypot field for spam bots --}}
                    <div class="sr-only" aria-hidden="true">
                        <label for="company">{{ __('app.contact.form.honeypot_label') }}</label>
                        <input id="company" type="text" name="company" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="first_name">{{ __('app.contact.form.first_name_label') }}</label>
                            <div class="input-with-icon">
                                <span class="input-icon" aria-hidden="true">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input id="first_name" type="text" name="first_name"
                                    value="{{ old('first_name') }}" placeholder="{{ __('app.contact.form.first_name_placeholder') }}"
                                    required autocomplete="given-name">
                            </div>
                            @error('first_name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-field">
                            <label for="last_name">{{ __('app.contact.form.last_name_label') }}</label>
                            <div class="input-with-icon">
                                <span class="input-icon" aria-hidden="true">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input id="last_name" type="text" name="last_name"
                                    value="{{ old('last_name') }}" placeholder="{{ __('app.contact.form.last_name_placeholder') }}"
                                    autocomplete="family-name">
                            </div>
                            @error('last_name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="email">{{ __('app.contact.form.email_label') }}</label>
                            <div class="input-with-icon">
                                <span class="input-icon" aria-hidden="true">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}"
                                    placeholder="{{ __('app.contact.form.email_placeholder') }}" required autocomplete="email">
                            </div>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-field">
                            <label for="phone">{{ __('app.contact.form.phone_label') }}</label>
                            <div class="input-with-icon">
                                <span class="input-icon" aria-hidden="true">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                    placeholder="{{ __('app.contact.form.phone_placeholder') }}" autocomplete="tel">
                            </div>
                            @error('phone')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="message">{{ __('app.contact.form.message_label') }}</label>
                        <div class="input-with-icon textarea-with-icon">
                            <span class="input-icon" aria-hidden="true">
                                <i class="fa-solid fa-comment-dots"></i>
                            </span>
                            <textarea id="message" name="message" placeholder="{{ __('app.contact.form.message_placeholder') }}" required rows="4">{{ old('message') }}</textarea>
                        </div>
                        @error('message')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="cta-primary">{{ __('app.contact.form.submit') }}</button>
                </form>
            </div>

        </div>
    </div>
</section>

@push('scripts')
        @php
            $contactValidationMessages = __('app.contact.validation');
            $serviceModalCopy = [
                'subtitle' => __('app.services.modal_copy.subtitle'),
                'cta' => __('app.services.modal_copy.cta'),
            ];
            $serviceModalData = [
                'executive' => [
                    'title' => __('app.services.cards.executive'),
                    'summary' => __('app.services.modal.executive.summary'),
                    'details' => __('app.services.modal.executive.details'),
                    'image' => '/assets/scope.svg',
                    'imageAlt' => __('app.services.cards.executive'),
                    'cta' => '#contact',
                ],
                'culture_fit' => [
                    'title' => __('app.services.cards.culture_fit'),
                    'summary' => __('app.services.modal.culture_fit.summary'),
                    'details' => __('app.services.modal.culture_fit.details'),
                    'image' => '/assets/data-up.svg',
                    'imageAlt' => __('app.services.cards.culture_fit'),
                    'cta' => '#contact',
                ],
                'pipeline' => [
                    'title' => __('app.services.cards.pipeline'),
                    'summary' => __('app.services.modal.pipeline.summary'),
                    'details' => __('app.services.modal.pipeline.details'),
                    'image' => '/assets/analytic-chart.svg',
                    'imageAlt' => __('app.services.cards.pipeline'),
                    'cta' => '#contact',
                ],
            ];
        @endphp
        <script nonce="{{ $cspNonce ?? '' }}">
            window.contactFormMessages = @json($contactValidationMessages);
            window.serviceModalCopy = @json($serviceModalCopy);
            window.serviceCardData = @json($serviceModalData);
        </script>
        <script src="/js/services-modal.js"></script>
        <script src="/js/contact-form.js"></script>
    @endpush