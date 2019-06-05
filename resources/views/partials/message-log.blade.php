<message-log :is-enabled="isEnabled && isSelectedTab('message-log')"
             :open-after-hidden="openAfterHidden"
             url="{{ route('api_message') }}" inline-template>
    <div v-if="isEnabled" class="message-log">
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ trans('messages.message.empty') }}
            </p>
        </div>
        <template v-else>
            <template v-for="message in data.data">
                <div class="modal-body separator">
                    <div class="row">
                        <div class="col-lg-2 text-center">
                            <h5>
                                <i class="fas fa-user"></i>
                            </h5>
                            <h5>
                                <a href="#" @click.prevent="openUser(message.sender.username)">
                                    @{{ message.sender.username }}
                                </a>
                            </h5>
                        </div>
                        <div class="col-lg-6 text-center">
                            <h5>
                                <i class="far fa-clock"></i>
                            </h5>
                            <h5>
                                @{{ message.created_at | fromNow }}
                            </h5>
                        </div>
                        <div class="col-lg-2 pt-lg-3">
                            <button class="btn btn-success btn-block"
                                    :disabled="message.sender.is_blocked || message.sender.is_blocked_by"
                                    @click="sendMessage(message.sender.username)">
                                {{ trans('messages.message.reply') }}
                            </button>
                        </div>
                        <div class="col-lg-2 pt-2 pt-lg-3">
                            <button class="btn btn-primary btn-block" @click="collapse(message)">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body separator collapse"
                     :class="{show: isCollapsed(message)}"
                     v-html="message.message"></div>
            </template>

            @include('partials.pager')
        </template>
    </div>
</message-log>
