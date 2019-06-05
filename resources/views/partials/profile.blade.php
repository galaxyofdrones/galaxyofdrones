@php
    $translations = [
        'joined' => trans('messages.user.joined', [
            'datetime' => '__datetime__',
        ]),
    ];
@endphp
<profile url="{{ route('api_user_show', '__user__') }}"
         block-url="{{ route('api_block_update', '__user__') }}"
         :translations='@json($translations)' inline-template>
    <div class="profile modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @{{ username }}
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
                                @{{ data.username_with_level }}
                            </h5>
                            <p>
                                @{{ joined }}
                            </p>
                            <div class="attribute-row" :class="{separator: data.can_block}">
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ trans('validation.attributes.experience') }}
                                        </h6>
                                        <h5>
                                            @{{ data.experience | number }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ trans('validation.attributes.mission') }}
                                        </h6>
                                        <h5>
                                            @{{ data.mission_count | number }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ trans('validation.attributes.expedition') }}
                                        </h6>
                                        <h5>
                                            @{{ data.expedition_count | number }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ trans('validation.attributes.planet') }}
                                        </h6>
                                        <h5>
                                            @{{ data.planet_count | number }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ trans('validation.attributes.winning_battle') }}
                                        </h6>
                                        <h5 class="highlight-success">
                                            @{{ data.winning_battle_count | number }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ trans('validation.attributes.losing_battle') }}
                                        </h6>
                                        <h5 class="highlight-danger">
                                            @{{ data.losing_battle_count | number }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.can_block" class="attribute-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block"
                                                :disabled="isBlocked || data.is_blocked_by"
                                                @click="sendMessage()">
                                            {{ trans('messages.message.send') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <button class="btn btn-block"
                                                :class="{'btn-danger': !isBlocked, 'btn-warning': isBlocked}"
                                                @click="toggleBlock()">
                                            <template v-if="!isBlocked">
                                                {{ trans('messages.block.singular') }}
                                            </template>
                                            <template v-else>
                                                {{ trans('messages.block.unblock') }}
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <planet-list :is-enabled="isEnabled"
                             url="{{ route('api_planet_all', '__user__') }}"
                             :username="username"
                             :can-move="isRouteName('starmap')"
                             :close="close" inline-template>
                    <div v-if="isEnabled" class="planet-list">
                        <div class="modal-body separator" v-for="planet in data.data">
                            <div class="row">
                                <div class="col-lg-3 text-center text-lg-left">
                                    <span class="item item-sm" :class="planet.resource_id | item('planet')">
                                        @{{ planet.name }}
                                    </span>
                                </div>
                                <div class="col-lg-3 pt-lg-2 text-center">
                                    <h5>
                                        {{ trans('messages.coordinate.x') }}
                                    </h5>
                                    <h5>
                                        @{{ planet.x }}
                                    </h5>
                                </div>
                                <div class="col-lg-3 pt-lg-2 text-center">
                                    <h5>
                                        {{ trans('messages.coordinate.y') }}
                                    </h5>
                                    <h5>
                                        @{{ planet.y }}
                                    </h5>
                                </div>
                                <div class="col-lg-3 pt-lg-3">
                                    <button class="btn btn-primary btn-block"
                                            :disabled="!canMove"
                                            @click="move(planet)">
                                        {{ trans('messages.move') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        @include('partials.pager')
                    </div>
                </planet-list>
            </div>
        </div>
    </div>
</profile>
