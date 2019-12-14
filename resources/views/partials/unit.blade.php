<div class="row">
    <div class="col-lg-6 text-center">
        <span class="item" :class="unit | item('unit')"></span>
    </div>
    <div class="col-lg-6">
        <h5 v-if="!isResearch && remaining">
            @{{ trainName }}
        </h5>
        <h5 v-else>
            @{{ unit.name }}
        </h5>
        <p>
            @{{ unit.description }}
        </p>
        <div class="attribute-row">
            <div class="col-lg-6">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.speed') }}
                    </h6>
                    <h5>
                        @{{ unit.speed }}
                    </h5>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.attack') }}
                    </h6>
                    <h5>
                        @{{ unit.attack }}
                    </h5>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.defense') }}
                    </h6>
                    <h5>
                        @{{ unit.defense }}
                    </h5>
                </div>
            </div>
            <div class="col-lg-6" v-if="unit.detection !== null">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.detection') }}
                    </h6>
                    <h5>
                        @{{ unit.detection }}
                    </h5>
                </div>
            </div>
            <div class="col-lg-6" v-if="unit.capacity !== null">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.capacity') }}
                    </h6>
                    <h5>
                        @{{ unit.capacity }}
                    </h5>
                </div>
            </div>
        </div>
        <ul v-if="isResearch" class="list-inline">
            <li class="list-inline-item" v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.research_experience') }}'}">
                <i class="fas fa-flask"></i>
                @{{ unit.research_experience | number }}
            </li>
            <li class="list-inline-item highlight-warning"
                v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.research_cost') }}'}">
                <i class="fas fa-bolt"></i>
                @{{ unit.research_cost | number }}
            </li>
            <li class="list-inline-item" v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.research_time') }}'}">
                <i class="far fa-clock"></i>
                @{{ unit.research_time | timer }}
            </li>
        </ul>
        <ul v-else class="list-inline">
            <li class="list-inline-item" v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.supply') }}'}">
                <i class="fas fa-warehouse"></i>
                @{{ unit.supply | number }}
            </li>
            <li class="list-inline-item highlight-warning"
                v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.train_cost') }}'}">
                <i class="fas fa-bolt"></i>
                @{{ unit.train_cost | number }}
            </li>
            <li class="list-inline-item" v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.train_time') }}'}">
                <i class="far fa-clock"></i>
                @{{ unit.train_time | timer }}
            </li>
        </ul>
        <div v-if="remaining" class="attribute-row">
            <div class="col-lg-6">
                <div class="form-group">
                    <button class="btn btn-warning btn-block" @click="destroy(unit)">
                        {{ __('messages.cancel') }}
                    </button>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <h5>
                    @{{ remaining | timer }}
                </h5>
            </div>
        </div>
        <div v-else-if="isResearch" class="attribute-row">
            <div class="col-lg-6">
                <div class="form-group">
                    <button class="btn btn-success btn-block"
                            @click="store(unit)"
                            :disabled="!isResearchable(unit)">
                        {{ __('messages.research.singular') }}
                    </button>
                </div>
            </div>
        </div>
        <div v-else class="attribute-row">
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control"
                               type="number"
                               min="1"
                               :max="trainableQuantity"
                               :placeholder="trainableQuantity | bracket"
                               v-model.number="quantity">
                        <span class="input-group-append">
                            <button class="btn btn-success btn-block"
                                    @click="store()"
                                    :disabled="!isTrainable">
                                {{ __('messages.training.train') }}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <h5>
                    @{{ trainTime | timer }}
                </h5>
            </div>
        </div>
    </div>
</div>
