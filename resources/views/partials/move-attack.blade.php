<div v-if="isAttackType" class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">
            {{ trans('messages.movement.attack') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>
    </div>
    @include('partials.time')
    <div class="modal-body separator">
        <div class="item-input-row">
            <div class="col-md-4 col-lg-3" v-for="unit in fighterUnits">
                <span class="item item-sm"
                      :class="unit | item('unit')"
                      v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.description}">
                    @{{ unit.name }}
                </span>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-btn" @click="setTotalUnit(unit)">
                            <button class="btn btn-primary btn-block">
                                <i class="fas fa-clipboard-check"></i>
                            </button>
                        </div>
                        <input class="form-control"
                               type="number"
                               min="1"
                               :max="unitQuantity(unit)"
                               :placeholder="unitQuantity(unit) | bracket"
                               v-model.number="quantity[unit.id]">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-body separator">
        <div class="row">
            <div class="col-lg-4 offset-lg-4">
                <button class="btn btn-danger btn-block"
                        type="button"
                        @click="attack()"
                        :disabled="!hasFighterUnits">
                    {{ trans('messages.movement.attack') }}
                </button>
            </div>
        </div>
    </div>
</div>
