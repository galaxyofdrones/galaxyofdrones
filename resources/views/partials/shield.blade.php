<shield :is-enabled="isEnabled && isSelectedTab('shield')"
        url="{{ route('api_shield') }}"
        store-url="{{ route('api_shield_store', '__planet__') }}"
        :close="close"
        :planets="data.planets" inline-template>
    <div v-if="isEnabled" class="shield">
        <div class="shield-form modal-body separator">
            <div class="row">
                <div class="col-lg-6 offset-lg-3 text-center">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            @php
                                $expiration = App\Models\Shield::expiration();
                            @endphp
                            {{ trans_choice('messages.shield.time', $expiration, ['value' => $expiration]) }}
                        </li>
                        <li class="list-inline-item highlight-warning"
                            v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('messages.solarion.name') }}'}">
                            <i class="far fa-sun"></i>
                            {{ App\Models\Shield::SOLARION_COUNT }}
                        </li>
                    </ul>
                    <div class="input-group">
                        <select class="form-control"
                                v-model="selected"
                                :disabled="!data.can_store">
                            <option :value="planet.id" v-for="planet in planets">
                                @{{ planet.name }}
                            </option>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-success"
                                    :disabled="!data.can_store"
                                    @click="store()">
                                {{ __('messages.shield.add') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ __('messages.shield.empty') }}
            </p>
        </div>
        <completion v-else v-for="shield in data.shields"
                    :key="shield.id"
                    :completion="shield"
                    :is-completable="isRouteName('starmap')"
                    :store="move" inline-template>
            <div class="modal-body separator shield-item">
                <div class="row">
                    <div class="col-lg-6 text-center text-lg-left">
                        <span class="item item-sm" :class="completion.planet.resource_id | item('planet')">
                            @{{ completion.planet.name }}
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
                    <div class="col-lg-3 pt-lg-3 text-center">
                        <button class="btn btn-primary btn-block"
                                :disabled="!isCompletable"
                                @click="store(completion)">
                            {{ __('messages.move') }}
                        </button>
                    </div>
                </div>
            </div>
        </completion>
    </div>
</shield>
