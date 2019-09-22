<mothership url="{{ route('api_user_capital') }}"
            store-url="{{ route('api_user_capital_update', '__planet__') }}" inline-template>
    <div class="mothership modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('messages.mothership.singular') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <span class="item mothership"></span>
                        </div>
                        <div class="col-lg-6">
                            <h5>
                                {{ __('messages.mothership.singular') }}
                            </h5>
                            <p>
                                {{ __('messages.mothership.description') }}
                            </p>
                            <div class="attribute-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <select class="form-control"
                                                v-model="selected"
                                                :disabled="data.incoming_capital_movement_count || remaining">
                                            <option :value="planet.id" v-for="planet in data.planets">
                                                @{{ planet.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <h5 v-if="remaining">
                                            @{{ remaining | timer }}
                                        </h5>
                                        <button v-else class="btn btn-primary btn-block"
                                                :disabled="!canHyperjump"
                                                @click="store()">
                                            {{ __('messages.mothership.hyperjump') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('cargo')}"
                               href="#"
                               @click.prevent="selectTab('cargo')">
                                {{ __('messages.cargo') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('armory')}"
                               href="#"
                               @click.prevent="selectTab('armory')">
                                {{ __('messages.armory') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('laboratory')}"
                               href="#"
                               @click.prevent="selectTab('laboratory')">
                                {{ __('messages.laboratory') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('radar')}"
                               href="#"
                               @click.prevent="selectTab('radar')">
                                {{ __('messages.radar') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('shield')}"
                               href="#"
                               @click.prevent="selectTab('shield')">
                                {{ __('messages.shield.singular') }}
                            </a>
                        </li>
                    </ul>
                </div>

                @include('partials.cargo')
                @include('partials.armory')
                @include('partials.laboratory')
                @include('partials.radar')
                @include('partials.shield')
            </div>
        </div>
    </div>
</mothership>
