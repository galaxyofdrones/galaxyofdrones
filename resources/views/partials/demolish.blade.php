<demolish url="{{ route('api_planet_demolish', '__grid__') }}" inline-template>
    <div class="demolish modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('messages.demolish') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>
                        {{ __('messages.warning.demolish_building') }}
                    </p>
                    <p>
                        {{ __('messages.warning.lose_planet') }}
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger"
                            type="button"
                            @click="demolish()">
                        {{ __('messages.demolish') }}
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
</demolish>
