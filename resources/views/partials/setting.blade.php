<setting url="{{ route('api_user_update') }}" inline-template>
    <div class="setting modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ trans('messages.setting.plural') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                @can('viewDeveloperSetting')
                    <div class="modal-body">
                        <ul class="nav nav-tabs nav-fill">
                            <li class="nav-item">
                                <a class="nav-link"
                                   :class="{active: isSelectedTab('profile')}"
                                   href="#"
                                   @click.prevent="selectTab('profile')">
                                    {{ trans('messages.profile') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                   :class="{active: isSelectedTab('developer')}"
                                   href="#"
                                   @click.prevent="selectTab('developer')">
                                    {{ trans('messages.developer') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                @endcan

                @include('partials.setting-profile')

                @can('viewDeveloperSetting')
                    @include('partials.setting-developer')
                @endcan
            </div>
        </div>
    </div>
</setting>
