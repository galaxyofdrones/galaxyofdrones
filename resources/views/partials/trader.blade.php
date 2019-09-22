<trader :type="{{ Koodilab\Models\Building::TYPE_TRADER }}"
        :building="building"
        :grid="grid"
        :close="close"
        url="{{ route('api_planet_capital') }}" inline-template>
    <div v-if="isEnabled" class="trader">
        <div class="modal-body">
            <ul class="nav nav-tabs nav-fill">
                <li class="nav-item">
                    <a class="nav-link"
                       :class="{active: isSelectedTab('trade')}"
                       href="#"
                       @click.prevent="selectTab('trade')">
                        {{ __('messages.resource.singular') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       :class="{active: isSelectedTab('patrol')}"
                       href="#"
                       @click.prevent="selectTab('patrol')">
                        {{ __('messages.unit.singular') }}
                    </a>
                </li>
            </ul>
        </div>

        @include('partials.trade')
        @include('partials.patrol')
    </div>
</trader>
