<player url="{{ route('api_user') }}" inline-template>
    <div class="player">
        <div class="player-info">
            <div class="player-top" v-cloak>
                <a href="#" @click.prevent="openUser()">
                    <img class="player-avatar"
                         :src="data.gravatar"
                         :alt="data.username">
                </a>
                <div class="player-nav">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{unread: hasUnread}"
                               href="#"
                               @click.prevent="openMailbox()">
                                <i class="fas fa-envelope"></i>
                                <span class="notification">
                                    @{{ data.notification_count }}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#"
                               @click.prevent="openSetting()">
                                <i class="fas fa-cog"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">
                                <i class="fas fa-power-off"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="player-progress" v-cloak>
                <div class="text"
                     v-popover="{placement: 'left', trigger: 'hover', title: '{{ __('validation.attributes.level') }}', content: data.level}">
                    <i class="fas fa-star"></i>
                </div>
                <div class="progress flex-fill"
                     v-popover="{placement: 'left', trigger: 'hover', title: '{{ __('validation.attributes.experience') }}', content: experienceLabel}">
                    <div class="progress-bar" :style="{width: experienceProgress}"></div>
                </div>
                <div class="text"
                      v-popover="{placement: 'left', trigger: 'hover', title: '{{ __('validation.attributes.level') }}', content: data.level}">
                    @{{ data.level | number }}
                </div>
            </div>
        </div>
        <div class="player-energy"
             v-popover="{placement: 'left', trigger: 'hover', title: '{{ __('validation.attributes.energy') }}', content: energyValue}" v-cloak>
            <i class="fas fa-bolt"></i>
            @{{ energy | number }}
        </div>
    </div>
</player>
