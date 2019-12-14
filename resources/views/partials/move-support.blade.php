<div v-if="isSupportType" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('messages.movement.support') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>
    </div>

    @include('partials.time')
    @include('partials.support')
</div>
