<div v-if="isTransportType" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('messages.movement.transport') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>
    </div>

    @include('partials.time')
    @include('partials.transport')
</div>
