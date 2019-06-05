<div class="modal-body separator">
    <div class="item-input-row">
        <div class="col-md-4 col-lg-3" v-for="unit in planet.units">
            <span class="item item-sm"
                  :class="unit | item('unit')"
                  v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.description}">
                @{{ unit.name }}
            </span>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend" @click="setTotalUnit(unit)">
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
            <ul v-if="!isMove" class="list-inline">
                <li v-if="planet.is_capital"
                    class="list-inline-item"
                    v-popover="{placement: 'top', trigger: 'hover', content: '{{ trans('validation.attributes.trade_time') }}'}">
                    <i class="far fa-clock"></i>
                    {{ trans('messages.instant') }}
                </li>
                <li v-else
                    class="list-inline-item"
                    v-popover="{placement: 'top', trigger: 'hover', content: '{{ trans('validation.attributes.trade_time') }}'}">
                    <i class="far fa-clock"></i>
                    @{{ travelTime | timer }}
                </li>
            </ul>
            <button class="btn btn-success btn-block" :disabled="!hasUnits" @click="support()">
                {{ trans('messages.movement.support') }}
            </button>
        </div>
    </div>
</div>
