<sidebar planet-url="{{ route('api_planet') }}"
         planet-name-url="{{ route('api_planet_name_update') }}"
         user-current-url="{{ route('api_user_current_update', '__planet__') }}" inline-template>
    <div class="sidebar" :class="{active: isActive}">
        <div class="sidebar-content" ref="scrollbar">
            <div class="sidebar-block" v-cloak>
                <input v-if="isEditActive"
                       class="form-control"
                       v-model="name"
                       ref="name"
                       :placeholder="data.name"
                       @blur="renamePlanet()"
                       @keyup.13="renamePlanet()">
                <div v-else class="input-group">
                    <select class="form-control" v-model="selected">
                        <option :value="planet.id" v-for="planet in data.planets">
                            @{{ planet.name }}
                        </option>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-success"
                                href="#"
                                @click.prevent="toggleEdit()">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="sidebar-block" v-cloak>
                <div class="attribute-row">
                    <div class="col-6">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.mining_rate') }}
                            </h6>
                            <h5>
                                @{{ data.mining_rate }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.production_rate') }}
                            </h6>
                            <h5>
                                @{{ data.production_rate }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="attribute-row">
                    <div class="col-6">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.incoming') }}
                            </h6>
                            <h5>
                                @{{ data.incoming_movement }}
                            </h5>
                            <h5 class="highlight-danger">
                                @{{ data.incoming_attack_movement }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.outgoing') }}
                            </h6>
                            <h5>
                                @{{ data.outgoing_movement }}
                            </h5>
                            <h5 class="highlight-warning">
                                @{{ data.outgoing_attack_movement }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="attribute-row">
                    <div class="col-6">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.construction') }}
                            </h6>
                            <h5>
                                @{{ data.construction }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.upgrade') }}
                            </h6>
                            <h5>
                                @{{ data.upgrade }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="attribute-row">
                    <div class="col-12">
                        <div class="attribute">
                            <h6>
                                {{ __('validation.attributes.training') }}
                            </h6>
                            <h5>
                                @{{ data.training }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar-block" v-cloak>
                <div class="progress"
                     v-popover="{trigger: 'hover', title: '{{ __('messages.resource.plural') }}', content: resourceLabel}">
                    <div class="progress-bar"
                         :class="{'bg-success': !isResourceFull, 'bg-danger': isResourceFull}"
                         :style="{width: resourceProgress}"></div>
                </div>
                <div class="item-row">
                    <div class="col-3" v-for="resource in data.resources">
                        <span class="item item-sm"
                              :class="resource | item('resource')"
                              v-popover="{trigger: 'hover', title: resource.name, content: resourceValue(resource)}">
                            @{{ resourceQuantity(resource) | number }}
                        </span>
                    </div>
                    <div class="col-3">
                        <span class="item item-sm solarion"
                              v-popover="{trigger: 'hover', title: '{{ __('messages.solarion.name') }}', content: data.solarion}">
                            @{{ data.solarion | number }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="sidebar-block" v-cloak>
                <div class="progress progress-unit"
                     v-popover="{trigger: 'hover', title: '{{ __('messages.unit.plural') }}', content: unitLabel}">
                    <div class="progress-bar bg-success"
                         :class="{'bg-success': !isUnitFull, 'bg-danger': isUnitFull}"
                         :style="{width: unitProgress}"></div>
                    <div class="progress-bar bg-warning"
                         :style="{width: unitTrainingProgress}"></div>
                </div>
                <div class="item-row">
                    <div class="col-3" v-for="unit in data.units">
                        <span class="item item-sm"
                              :class="unit | item('unit')"
                              v-popover="{trigger: 'hover', title: unit.name, content: unit.quantity}">
                            @{{ unit.quantity | number }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-nav">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link"
                       href="#"
                       @click.prevent="toggle()">
                        <i :class="{'fas fa-bars': !isActive, 'fas fa-times': isActive}"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'home'}"
                                 class="nav-link"
                                 :class="{active: isRouteName('home')}">
                        <i class="fas fa-globe-americas"></i>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'starmap'}"
                                 class="nav-link"
                                 :class="{active: isRouteName('starmap')}">
                        <i class="fas fa-satellite-dish"></i>
                    </router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="#"
                       @click.prevent="openMothership()">
                        <i class="fas fa-rocket"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       href="#"
                       @click.prevent="openTrophy()">
                        <i class="fas fa-trophy"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</sidebar>
