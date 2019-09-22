<div v-if="isOccupyType" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ __('messages.movement.occupy') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>
    </div>
    @include('partials.time')
    <div class="modal-body separator">
        <div class="row">
            <div class="col-lg-6 text-center">
                <span class="item" :class="settlerUnit | item('unit')"></span>
            </div>
            <div class="col-lg-6">
                <h5>
                    @{{ settlerUnit.name }}
                </h5>
                <p>
                    @{{ settlerUnit.description }}
                </p>
                <div class="attribute-row separator">
                    <div class="col-lg-6">
                        <div class="attribute">
                            <h6>
                                {{ __('messages.required_quantity') }}
                            </h6>
                            <h5>
                                {{ Koodilab\Models\Planet::SETTLER_COUNT }} / @{{ unitQuantity(settlerUnit) }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="attribute-row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <button class="btn btn-success btn-block"
                                    type="button"
                                    @click="occupy()"
                                    :disabled="!canOccupy">
                                {{ __('messages.movement.occupy') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
