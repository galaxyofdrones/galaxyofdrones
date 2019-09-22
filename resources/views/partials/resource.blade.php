<div class="row">
    <div class="col-lg-6 text-center">
        <span class="item" :class="resource | item('resource')"></span>
    </div>
    <div class="col-lg-6">
        <h5>
            @{{ resource.name }}
        </h5>
        <p>
            @{{ resource.description }}
        </p>
        <div class="attribute-row">
            <div class="col-lg-6">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.frequency') }}
                    </h6>
                    <h5>
                        @{{ resource.frequency | percent }}
                    </h5>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="attribute">
                    <h6>
                        {{ __('validation.attributes.efficiency') }}
                    </h6>
                    <h5>
                        @{{ resource.efficiency | percent }}
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
            <li class="list-inline-item" v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('validation.attributes.transmute_time') }}'}">
                <i class="far fa-clock"></i>
                {{ __('messages.instant') }}
            </li>
        </ul>
        <div v-if="remaining" class="attribute-row">
            <div class="col-lg-6">
                <div class="form-group">
                    <button class="btn btn-warning btn-block" @click="destroy()">
                        {{ __('messages.cancel') }}
                    </button>
                </div>
            </div>
            <div class="col-lg-6">
                <h5>
                    @{{ remaining | timer }}
                </h5>
            </div>
        </div>
        <div v-else-if="isResearch" class="attribute-row">
            <div class="col-lg-6">
                <div class="form-group">
                    <button class="btn btn-success btn-block"
                            @click="store()"
                            :disabled="!isResearchable(resource)">
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
                               :max="transmutableQuantity"
                               :placeholder="transmutableQuantity | bracket"
                               v-model.number="quantity">
                        <span class="input-group-append">
                            <button class="btn btn-success btn-block"
                                    @click="store()"
                                    :disabled="!isTransmutable">
                                {{ __('messages.transmute') }}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <h5 class="highlight-warning">
                    <i class="fas fa-bolt"></i>
                    @{{ transmutableEnergy | number | sign(transmutableEnergy) }}
                </h5>
            </div>
        </div>
    </div>
</div>
