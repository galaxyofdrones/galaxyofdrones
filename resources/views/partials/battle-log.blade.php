<battle-log :is-enabled="isEnabled && isSelectedTab('battle-log')"
            :open-after-hidden="openAfterHidden"
            url="{{ route('api_battle_log') }}" inline-template>
    <div v-if="isEnabled" class="battle-log">
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ trans('messages.battle_log.empty') }}
            </p>
        </div>
        <template v-else>
            <template v-for="battle_log in data.data">
                <div class="modal-body separator">
                    <div class="row">
                        <template v-if="battle_log.is_attack">
                            <div class="col-lg-2 text-center text-lg-left">
                                <span class="item item-sm"
                                      :class="battle_log.start.resource_id | item('planet')">
                                    @{{ battle_log.start.name }}
                                </span>
                            </div>
                            <div class="col-lg-4 pt-lg-2 text-center">
                                <h5 class="highlight-warning">
                                    <i class="fas fa-arrow-right"></i>
                                </h5>
                                <h5>
                                    @{{ battle_log.created_at | fromNow }}
                                </h5>
                            </div>
                            <div class="col-lg-2 text-center">
                                <span class="item item-sm"
                                      :class="battle_log.end.resource_id | item('planet')">
                                    @{{ battle_log.end.name }}
                                </span>
                            </div>
                            <div class="col-lg-2 pt-lg-2 text-center">
                                <h5>
                                    <i class="fas fa-user"></i>
                                </h5>
                                <h5 v-if="battle_log.defender.id">
                                    <a href="#" @click.prevent="openUser(battle_log.defender.username)">
                                        @{{ battle_log.defender.username }}
                                    </a>
                                </h5>
                                <h5 v-else>
                                    @{{ battle_log.defender.username }}
                                </h5>
                            </div>
                        </template>
                        <template v-if="battle_log.is_defense">
                            <div class="col-lg-2 text-center text-lg-left">
                                <span class="item item-sm"
                                      :class="battle_log.end.resource_id | item('planet')">
                                    @{{ battle_log.end.name }}
                                </span>
                            </div>
                            <div class="col-lg-4 pt-lg-2 text-center">
                                <h5 class="highlight-danger">
                                    <i class="fas fa-arrow-left"></i>
                                </h5>
                                <h5>
                                    @{{ battle_log.created_at | fromNow }}
                                </h5>
                            </div>
                            <div class="col-lg-2 text-center">
                                <span class="item item-sm"
                                      :class="battle_log.start.resource_id | item('planet')">
                                    @{{ battle_log.start.name }}
                                </span>
                            </div>
                            <div class="col-lg-2 pt-lg-2 text-center">
                                <h5>
                                    <i class="fas fa-user"></i>
                                </h5>
                                <h5>
                                    <a href="#" @click.prevent="openUser(battle_log.attacker.username)">
                                        @{{ battle_log.attacker.username }}
                                    </a>
                                </h5>
                            </div>
                        </template>
                        <div class="col-lg-2 pt-lg-3">
                            <button class="btn btn-primary btn-block"
                                    type="button"
                                    @click.prevent="collapse(battle_log)">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body separator collapse" :class="{show: isCollapsed(battle_log)}">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>
                                {{ trans('validation.attributes.attacker_units') }}
                            </h5>
                            <div class="attribute-row">
                                <div v-for="unit in battle_log.attacker_units" class="col-lg-3">
                                    <div class="attribute">
                                        <h6>
                                            <span class="item item-sm"
                                                  :class="unit | item('unit')"
                                                  v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.description}">
                                            </span>
                                        </h6>
                                        <h5>
                                            @{{ unit.quantity | number }}
                                        </h5>
                                        <h5 class="highlight-danger">
                                            @{{ unit.losses | number }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <h5 v-if="battle_log.resources.length">
                                {{ trans('validation.attributes.defender_resources') }}
                            </h5>
                            <div v-if="battle_log.resources.length" class="attribute-row">
                                <div v-for="resource in battle_log.resources" class="col-lg-3">
                                    <div class="attribute">
                                        <h6>
                                            <span class="item item-sm"
                                                  :class="resource | item('resource')"
                                                  v-popover="{placement: 'top', trigger: 'hover', title: resource.name, content: resource.description}">
                                            </span>
                                        </h6>
                                        <h5>
                                            @{{ resource.quantity | number }}
                                        </h5>
                                        <h5 class="highlight-danger">
                                            @{{ resource.losses | number }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h5 v-if="battle_log.defender_units.length">
                                {{ trans('validation.attributes.defender_units') }}
                            </h5>
                            <div v-if="battle_log.defender_units.length" class="attribute-row">
                                <div v-for="unit in battle_log.defender_units" class="col-lg-3">
                                    <div class="attribute">
                                        <h6>
                                            <span class="item item-sm"
                                                  :class="unit | item('unit')"
                                                  v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.description}">
                                            </span>
                                        </h6>
                                        <h5>
                                            @{{ unit.quantity | number }}
                                        </h5>
                                        <h5 class="highlight-danger">
                                            @{{ unit.losses | number }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <h5 v-if="battle_log.buildings.length">
                                {{ trans('validation.attributes.defender_buildings') }}
                            </h5>
                            <div v-if="battle_log.buildings.length" class="attribute-row">
                                <div v-for="building in battle_log.buildings" class="col-lg-3">
                                    <div class="attribute">
                                        <h6>
                                            <span class="item item-sm"
                                                  :class="building | item('building')"
                                                  v-popover="{placement: 'top', trigger: 'hover', title: building.name, content: building.description}">
                                            </span>
                                        </h6>
                                        <h5>
                                            @{{ building.level | number }}
                                        </h5>
                                        <h5 class="highlight-danger">
                                            @{{ building.losses | number }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            @include('partials.pager')
        </template>
    </div>
</battle-log>
