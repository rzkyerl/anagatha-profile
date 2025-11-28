<section id="services" aria-labelledby="services-title" class="page-section page-section--flush-top">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading" data-aos="fade-up">
            <h2 id="services-title" class="section-heading__title">{{ __('app.services.title') }}</h2>
            <p class="section-heading__text">{{ __('app.services.description') }}</p>
        </div>

        <div class="grid-cards grid-cards--wide">
            <article class="card card--service" data-service-card data-service-key="executive" role="button" tabindex="0"
                aria-haspopup="dialog" aria-controls="service-modal" data-aos="fade-up" data-aos-delay="50">
                <div class="card--service__content">
                    <div class="card--service__icon">
                        <img src="/assets/scope.svg" alt="Executive Search & Leadership Placement" srcset="">
                    </div>
                    <div class="card--service__divider"></div>
                    <div class="card--service__text">
                        <h3>{{ __('app.services.cards.executive') }}</h3>
                        <button type="button" class="card--service__button" data-service-card-trigger>
                            {{ __('app.services.cards.view_details') }}
                        </button>
                    </div>
                </div>
            </article>
            <article class="card card--service" data-service-card data-service-key="culture_fit" role="button" tabindex="0"
                aria-haspopup="dialog" aria-controls="service-modal" data-aos="fade-up" data-aos-delay="150">
                <div class="card--service__content">
                    <div class="card--service__icon">
                        <img src="/assets/data-up.svg" alt="Culture Fit Recruitment for Growing Startups" srcset="">
                    </div>
                    <div class="card--service__divider"></div>
                    <div class="card--service__text">
                        <h3>{{ __('app.services.cards.culture_fit') }}</h3>
                        <button type="button" class="card--service__button" data-service-card-trigger>
                            {{ __('app.services.cards.view_details') }}
                        </button>
                    </div>
                </div>
            </article>
            <article class="card card--service" data-service-card data-service-key="pipeline" role="button" tabindex="0"
                aria-haspopup="dialog" aria-controls="service-modal" data-aos="fade-up" data-aos-delay="250">
                <div class="card--service__content">
                    <div class="card--service__icon">
                        <img src="/assets/analytic-chart.svg" alt="Talent Pipeline Development for Specialized Roles" srcset="">
                    </div>
                    <div class="card--service__divider"></div>
                    <div class="card--service__text">
                        <h3>{{ __('app.services.cards.pipeline') }}</h3>
                        <button type="button" class="card--service__button" data-service-card-trigger>
                            {{ __('app.services.cards.view_details') }}
                        </button>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

<div class="service-modal" id="service-modal" data-service-modal hidden aria-hidden="true">
    <div class="service-modal__backdrop" data-service-modal-close></div>
    <div class="service-modal__dialog" data-service-modal-dialog role="dialog" aria-modal="true"
        aria-labelledby="service-modal-title" tabindex="-1">
        <div class="service-modal__header">
            <div class="service-modal__header-left">
                <p class="service-modal__eyebrow" data-service-modal-subtitle>{{ __('app.services.modal_copy.subtitle') }}</p>
                <div class="service-modal__title-wrapper">
                    <h3 class="service-modal__title" id="service-modal-title" data-service-modal-title></h3>
                    <div class="service-modal__divider"></div>
                    <div class="service-modal__icon">
                        <img src="" alt="" class="service-modal__header-icon is-hidden" data-service-modal-image>
                    </div>
                </div>
            </div>
            <button type="button" class="service-modal__close" aria-label="{{ __('app.aria.close_toast') }}"
                data-service-modal-close>
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <div class="service-modal__body">
            <div class="service-modal__content">
                <p class="service-modal__summary" data-service-modal-summary></p>
                <ul class="service-modal__list" data-service-modal-list hidden></ul>
            </div>
        </div>
        <div class="service-modal__footer">
            <a class="cta-primary service-modal__cta" href="{{ route('contact') }}" data-service-modal-cta>
                {{ __('app.services.modal_copy.cta') }}
            </a>
        </div>
    </div>
</div>

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
                'cta' => '/contact',
            ],
            'culture_fit' => [
                'title' => __('app.services.cards.culture_fit'),
                'summary' => __('app.services.modal.culture_fit.summary'),
                'details' => __('app.services.modal.culture_fit.details'),
                'image' => '/assets/data-up.svg',
                'imageAlt' => __('app.services.cards.culture_fit'),
                'cta' => '/contact',
            ],
            'pipeline' => [
                'title' => __('app.services.cards.pipeline'),
                'summary' => __('app.services.modal.pipeline.summary'),
                'details' => __('app.services.modal.pipeline.details'),
                'image' => '/assets/analytic-chart.svg',
                'imageAlt' => __('app.services.cards.pipeline'),
                'cta' => '/contact',
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

