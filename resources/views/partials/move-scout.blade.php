<div v-if="isScoutType" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ trans('messages.movement.scout') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>
    </div>
    @include('partials.time')
    <div class="modal-body separator">
        <div class="row">
            <div class="col-md-6 text-center">
                <span class="item" :class="scoutUnit | item('unit')"></span>
            </div>
            <div class="col-md-6">
                <h5>
                    @{{ scoutUnit.name }}
                </h5>
                <p>
                    @{{ scoutUnit.description }}
                </p>
                <div class="attribute-row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-primary btn-block"
                                            type="button"
                                            @click="setTotalUnit(scoutUnit)">
                                        <i class="fas fa-clipboard-check"></i>
                                    </button>
                                </div>
                                <input class="form-control"
                                       type="number"
                                       min="1"
                                       :max="unitQuantity(scoutUnit)"
                                       :placeholder="unitQuantity(scoutUnit) | bracket"
                                       v-model.number="quantity[scoutUnit.id]">
                                <span class="input-group-append">
                                    <button class="btn btn-primary btn-block"
                                            type="button"
                                            @click="scout()"
                                            :disabled="!canScout">
                                        {{ trans('messages.movement.scout') }}
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
