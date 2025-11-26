<section id="about" class="page-section page-section--flush-top">
    <div class="container section-shell section-shell--stack">
        <div class="section-heading section-heading--left" data-aos="fade-up">
            <h2 class="section-heading__title">{{ __('app.about.title') }}</h2>
            <p class="section-heading__text">{{ __('app.about.description') }}</p>
        </div>
    </div>
</section>

<section aria-labelledby="vision-mission-heading" class="page-section">
        <div class="container vision-mission-container">
            <div class="section-heading section-heading--left" data-aos="fade-up">
                <h2 id="vision-mission-heading" class="section-heading__title">{{ __('app.vision_mission.title') }}</h2>
                <p class="section-heading__text">{{ __('app.vision_mission.subtitle') }}</p>
            </div>

            <div class="vision-mission-grid">
                <article class="vision-mission-card vision-mission-card--vision" data-aos="fade-up" data-aos-delay="50">
                    <div class="vision-mission-card__header">
                        <div class="vision-mission-card__icon">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <h3 class="vision-mission-card__title">{{ __('app.vision_mission.vision_title') }}</h3>
                    </div>
                    <p class="vision-mission-card__text">{{ __('app.vision_mission.vision_body') }}</p>
                </article>

                <article class="vision-mission-card vision-mission-card--mission" data-aos="fade-up" data-aos-delay="150">
                    <div class="vision-mission-card__header">
                        <div class="vision-mission-card__icon">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h3 class="vision-mission-card__title">{{ __('app.vision_mission.mission_title') }}</h3>
                    </div>
                    <p class="vision-mission-card__text">{{ __('app.vision_mission.mission_body') }}</p>
                </article>
            </div>
        </div>
    </section>

