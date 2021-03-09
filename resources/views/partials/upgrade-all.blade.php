<upgrade-all url="{{ route('api_upgrade_all') }}" store-url="{{ route('api_upgrade_store_all') }}" inline-template>
    <div class="upgrade-all modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('messages.upgrade.all') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div v-if="data.upgrade_cost" class="modal-body text-center">
                    <p v-if="canStore">
                        {{ __('messages.warning.upgrade') }}
                    </p>
                    <p v-else>
                        {{ __('messages.warning.resource') }}
                    </p>
                    <ul class="list-inline">
                        <li class="list-inline-item highlight-warning"
                            v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('messages.energy') }}'}">
                            <i class="fas fa-bolt"></i>
                            @{{ data.upgrade_cost }}
                        </li>
                        <li class="list-inline-item highlight-warning"
                            v-popover="{placement: 'top', trigger: 'hover', content: '{{ __('messages.solarion.name') }}'}">
                            <i class="far fa-sun"></i>
                            {{ App\Models\Upgrade::SOLARION_COUNT }}
                        </li>
                    </ul>
                </div>
                <div v-else class="modal-body text-center">
                    <p v-if="data.upgrade_count">
                        {{ __('messages.warning.upgrade_in_progress') }}
                    </p>
                    <p v-else>
                        {{ __('messages.warning.upgraded') }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success"
                            type="button"
                            @click="store()"
                            :disabled="!canStore">
                        {{ __('messages.upgrade.all') }}
                    </button>
                    <button class="btn btn-warning"
                            type="button"
                            @click="close()">
                        {{ __('messages.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</upgrade-all>
