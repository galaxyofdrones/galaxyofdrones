<producer :type="{{ App\Models\Building::TYPE_PRODUCER }}"
           :building="building"
           :grid="grid"
           url="{{ route('api_producer', '__grid__') }}"
           store-url="{{ route('api_producer_store', ['__grid__', '__resource__']) }}" inline-template>
    <div v-if="isEnabled" class="producer">
        <div class="modal-body separator">
            <div class="tab-content" v-if="data.resources.length">
                <div v-for="resource in data.resources" class="tab-pane" :class="{active: isSelected(resource)}">
                    @include('partials.resource')
                </div>
            </div>
        </div>
        <div class="modal-body separator">
            <ul class="nav nav-pills">
                <li class="nav-item" v-for="resource in data.resources">
                    <a class="nav-link"
                       :class="{active: isSelected(resource)}"
                       href="#"
                       @click.prevent="select(resource)">
                        <span class="item item-sm" :class="resource | item('resource')">
                            @{{ resource.name }}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</producer>
