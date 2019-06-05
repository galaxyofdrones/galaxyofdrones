<mailbox inline-template>
    <div class="mailbox modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ trans('messages.mailbox') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('mission-log')}"
                               href="#"
                               @click.prevent="selectTab('mission-log')">
                                {{ trans('messages.mission_log.plural') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('expedition-log')}"
                               href="#"
                               @click.prevent="selectTab('expedition-log')">
                                {{ trans('messages.expedition_log.plural') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('battle-log')}"
                               href="#"
                               @click.prevent="selectTab('battle-log')">
                                {{ trans('messages.battle_log.plural') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('message-log')}"
                               href="#"
                               @click.prevent="selectTab('message-log')">
                                {{ trans('messages.message.plural') }}
                            </a>
                        </li>
                    </ul>
                </div>

                @include('partials.mission-log')
                @include('partials.expedition-log')
                @include('partials.battle-log')
                @include('partials.message-log')
            </div>
        </div>
    </div>
</mailbox>
