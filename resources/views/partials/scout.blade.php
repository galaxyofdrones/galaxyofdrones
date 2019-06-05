<scout :type="{{ Koodilab\Models\Building::TYPE_SCOUT }}"
          :building="building"
          :grid="grid"
          url="{{ route('api_scout', '__grid__') }}" inline-template>
    <div v-if="isEnabled" class="scout">
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ trans('messages.movement.empty') }}
            </p>
        </div>
        <template v-else>
            <movement v-for="movement in data.incoming_movements"
                      :key="movement.id"
                      :movement="movement" inline-template>
                <div class="modal-body separator">
                    <div class="row">
                        <div class="col-lg-6 text-center text-lg-left">
                            <span v-for="unit in movement.units"
                                  class="item item-sm"
                                  :class="unit | item('unit')"
                                  v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.quantity}">
                                @{{ unit.quantity | number }}
                            </span>
                            <span v-for="resource in movement.resources"
                                  class="item item-sm"
                                  :class="resource | item('resource')"
                                  v-popover="{placement: 'top', trigger: 'hover', title: resource.name, content: resource.quantity}">
                                @{{ resource.quantity | number }}
                            </span>
                        </div>
                        <div class="col-lg-3 pt-lg-2 text-center">
                            <h5 class="highlight-success" :class="{'highlight-danger': movement.type < 3}">
                                <i class="fas fa-arrow-left"></i>
                            </h5>
                            <h5>
                                @{{ remaining | timer }}
                            </h5>
                        </div>
                        <div class="col-lg-3 text-center text-lg-right">
                            <span class="item item-sm" :class="movement.start.resource_id | item('planet')">
                                @{{ movement.start.display_name }}
                            </span>
                        </div>
                    </div>
                </div>
            </movement>
            <movement v-for="movement in data.outgoing_movements"
                      :key="movement.id"
                      :movement="movement" inline-template>
                <div class="modal-body separator">
                    <div class="row">
                        <div class="col-lg-6 text-center text-lg-left">
                            <span v-for="unit in movement.units"
                                  class="item item-sm"
                                  :class="unit | item('unit')"
                                  v-popover="{placement: 'top', trigger: 'hover', title: unit.name, content: unit.quantity}">
                                @{{ unit.quantity | number }}
                            </span>
                            <span v-for="resource in movement.resources"
                                  class="item item-sm"
                                  :class="resource | item('resource')"
                                  v-popover="{placement: 'top', trigger: 'hover', title: resource.name, content: resource.quantity}">
                                @{{ resource.quantity | number }}
                            </span>
                        </div>
                        <div class="col-lg-3 pt-lg-2 text-center">
                            <h5 class="highlight-success" :class="{'highlight-warning': movement.type < 3}">
                                <i class="fas fa-arrow-right"></i>
                            </h5>
                            <h5>
                                @{{ remaining | timer }}
                            </h5>
                        </div>
                        <div class="col-lg-3 text-center text-lg-right">
                            <span class="item item-sm" :class="movement.end.resource_id | item('planet')">
                                @{{ movement.end.display_name }}
                            </span>
                        </div>
                    </div>
                </div>
            </movement>
        </template>
    </div>
</scout>
