<armory :is-enabled="isEnabled && isSelectedTab('armory')"
        url="{{ route('api_expedition') }}"
        store-url="{{ route('api_expedition_store', '__expedition__') }}" inline-template>
    <div v-if="isEnabled" class="armory">
        <div class="armory-summary modal-body separator text-center">
            <span v-for="unit in data.units"
                  class="item item-sm"
                  :class="unit | item('unit')"
                  v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.quantity}">
                @{{ unit.quantity | number }}
            </span>
        </div>
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ __('messages.expedition.empty') }}
            </p>
        </div>
        <completion v-else v-for="expedition in data.expeditions"
                    :key="expedition.id"
                    :completion="expedition"
                    :is-completable="isCompletable"
                    :store="store" inline-template>
            <div class="modal-body separator">
                <div class="row">
                    <div class="col-lg-6 text-center text-lg-left">
                        <span class="item item-sm star">
                            @{{ completion.star }}
                        </span>
                        <span v-for="unit in completion.units"
                              class="item item-sm"
                              :class="unit | item('unit')"
                              v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.quantity}">
                            @{{ unit.quantity | number }}
                        </span>
                    </div>
                    <div class="col-lg-3 pt-lg-2 text-center">
                        <h5>
                            <i class="far fa-clock"></i>
                        </h5>
                        <h5>
                            @{{ remaining | timer }}
                        </h5>
                    </div>
                    <div class="col-lg-3 text-center">
                        <ul class="list-inline">
                            <li class="list-inline-item" v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.experience') }}'}">
                                <i class="fas fa-flask"></i>
                                @{{ completion.experience | number | sign(completion.experience) }}
                            </li>
                            <li class="list-inline-item highlight-warning"
                                v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('messages.solarion.name') }}'}">
                                <i class="far fa-sun"></i>
                                @{{ completion.solarion | number| sign(completion.solarion) }}
                            </li>
                        </ul>
                        <button class="btn btn-success btn-block"
                                :disabled="!isCompletable(completion)"
                                @click="store(completion)">
                            {{ __('messages.complete') }}
                        </button>
                    </div>
                </div>
            </div>
        </completion>
    </div>
</armory>
