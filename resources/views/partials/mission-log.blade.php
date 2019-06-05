<completion-log :is-enabled="isEnabled && isSelectedTab('mission-log')"
                url="{{ route('api_mission_log') }}" inline-template>
    <div v-if="isEnabled" class="mission-log">
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ trans('messages.mission_log.empty') }}
            </p>
        </div>
        <template v-else>
            <div v-for="mission_log in data.data" class="modal-body separator">
                <div class="row">
                    <div class="col-lg-6 text-center text-lg-left">
                        <span v-for="resource in mission_log.resources"
                              class="item item-sm"
                              :class="resource | item('resource')"
                              v-popover="{placement: 'top', trigger: 'hover', title: resource.name, content: resource.quantity}">
                            @{{ resource.quantity | number }}
                        </span>
                    </div>
                    <div class="col-lg-2 pt-lg-2 text-center">
                        <h5>
                            <i class="far fa-clock"></i>
                        </h5>
                        <h5>
                            @{{ mission_log.created_at | fromNow }}
                        </h5>
                    </div>
                    <div class="col-lg-2 pt-lg-2 text-center">
                        <h5>
                            <i class="fas fa-flask"></i>
                        </h5>
                        <h5>
                            @{{ mission_log.experience | number }}
                        </h5>
                    </div>
                    <div class="col-lg-2 pt-lg-2 text-center highlight-warning">
                        <h5>
                            <i class="fas fa-bolt"></i>
                        </h5>
                        <h5>
                            @{{ mission_log.energy | number }}
                        </h5>
                    </div>
                </div>
            </div>

            @include('partials.pager')
        </template>
    </div>
</completion-log>
