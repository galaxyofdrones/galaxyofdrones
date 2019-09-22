<donation inline-template>
    <div class="donation modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('messages.donation.singular') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-3 text-center">
                            <img class="img-fluid mb-3 mb-lg-0"
                                 src="{{ mix('images/singleplayer.png') }}"
                                 alt="{{ setting('title') }}">
                        </div>
                        <div class="col-lg-9">
                            <p>
                                {{ __('messages.donation.description') }}
                            </p>
                            <ul>
                                <li>
                                    {{ __('messages.donation.buy') }}
                                    <a href="http://store.steampowered.com/app/672940/Galaxy_of_Drones/"
                                       target="_blank">
                                        {{ __('messages.donation.steam') }}
                                    </a>
                                </li>
                                <li>
                                    {{ __('messages.donation.buy_in_app') }}
                                    <a href="https://play.google.com/store/apps/details?id=com.koodilab.galaxyofdrones"
                                       target="_blank">
                                        {{ __('messages.donation.android') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-body separator">
                    <h5 class="text-center mb-5">
                        {{ __('messages.donation.how') }}
                        <small class="ml-1 highlight-warning"
                               v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('messages.solarion.name') }}'}">
                            <i class="far fa-sun"></i>
                            {{ config('donation.reward') }}
                        </small>
                    </h5>
                    <div class="row">
                        <div class="col-lg-3 text-center">
                            <img class="step img-fluid"
                                 src="{{ mix('images/donation-1.png') }}"
                                 alt="{{ __('messages.donation.step_1') }}">
                            <p class="mt-3">
                                {{ __('messages.donation.step_1') }}
                            </p>
                        </div>
                        <div class="col-lg-3 text-center">
                            <img class="step img-fluid"
                                 src="{{ mix('images/donation-2.png') }}"
                                 alt="{{ __('messages.donation.step_2') }}">
                            <p class="mt-3">
                                {{ __('messages.donation.step_2') }}
                            </p>
                        </div>
                        <div class="col-lg-3 text-center">
                            <img class="step img-fluid"
                                 src="{{ mix('images/donation-3.png') }}"
                                 alt="{{ __('messages.donation.step_3') }}">
                            <p class="mt-3">
                                {{ __('messages.donation.step_3') }}
                            </p>
                        </div>
                        <div class="col-lg-3 text-center">
                            <img class="step img-fluid"
                                 src="{{ mix('images/donation-4.png') }}"
                                 alt="{{ __('messages.donation.step_4') }}">
                            <p class="mt-3">
                                {{ __('messages.donation.step_4') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</donation>
