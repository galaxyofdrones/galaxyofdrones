<trophy inline-template>
    <div class="trophy modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ trans('messages.trophy') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-fill">
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('pve')}"
                               href="#"
                               @click.prevent="selectTab('pve')">
                                {{ trans('messages.pve') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               :class="{active: isSelectedTab('pvp')}"
                               href="#"
                               @click.prevent="selectTab('pvp')">
                                {{ trans('messages.pvp') }}
                            </a>
                        </li>
                    </ul>
                </div>

                @include('partials.pve')
                @include('partials.pvp')
            </div>
        </div>
    </div>
</trophy>
