<planet url="{{ route('api_planet_show', '__planet__') }}" inline-template>
    <div class="planet modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @{{ properties.name }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 text-center">
                            <span class="item" :class="data.resource_id | item('planet')"></span>
                        </div>
                        <div class="col-lg-6">
                            <div class="attribute-row separator">
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ __('messages.coordinate.x') }}
                                        </h6>
                                        <h5>
                                            @{{ geometry.coordinates[0] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ __('messages.coordinate.y') }}
                                        </h6>
                                        <h5>
                                            @{{ geometry.coordinates[1] }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ __('messages.resource.count') }}
                                        </h6>
                                        <h5>
                                            @{{ data.resource_count }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="attribute">
                                        <h6>
                                            {{ __('messages.owner') }}
                                        </h6>
                                        <h5 v-if="data.user_id">
                                            <a href="#" @click.prevent="openUser()">
                                                @{{ data.username }}
                                            </a>
                                        </h5>
                                        <h5 v-else>
                                            @{{ data.username }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div v-if="isCurrent" class="attribute-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <router-link :to="{name: 'home'}" class="btn btn-primary btn-block">
                                            {{ __('messages.planet.jump') }}
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="isFriendly" class="attribute-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block"
                                                type="button"
                                                @click="changePlanet()">
                                            {{ __('messages.planet.change') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block"
                                                type="button"
                                                @click="openMove({{ App\Models\Movement::TYPE_SUPPORT }})">
                                            {{ __('messages.movement.support') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block"
                                                type="button"
                                                @click="openMove({{ App\Models\Movement::TYPE_TRANSPORT }})">
                                            {{ __('messages.movement.transport') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="attribute-row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-block"
                                                type="button"
                                                @click="openMove({{ App\Models\Movement::TYPE_SCOUT }})"
                                                :disabled="data.has_shield">
                                            {{ __('messages.movement.scout') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button class="btn btn-danger btn-block"
                                                type="button"
                                                @click="openMove({{ App\Models\Movement::TYPE_ATTACK }})"
                                                :disabled="data.has_shield">
                                            {{ __('messages.movement.attack') }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block"
                                                type="button"
                                                @click="openMove({{ App\Models\Movement::TYPE_OCCUPY }})"
                                                :disabled="data.has_shield || !data.can_occupy">
                                            {{ __('messages.movement.occupy') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</planet>
