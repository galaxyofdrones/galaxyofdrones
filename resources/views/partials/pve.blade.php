<leaderboard :is-enabled="isEnabled && isSelectedTab('pve')"
             :open-after-hidden="openAfterHidden"
             url="{{ route('api_rank_pve') }}" inline-template>
    <div v-if="isEnabled" class="pve">
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ trans('messages.user.empty') }}
            </p>
        </div>
        <template v-else>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                {{ trans('validation.attributes.username') }}
                            </th>
                            <th class="text-center">
                                {{ trans('validation.attributes.experience') }}
                            </th>
                            <th class="text-center">
                                {{ trans('validation.attributes.mission') }}
                            </th>
                            <th class="text-center">
                                {{ trans('validation.attributes.expedition') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in data.data">
                            <td>
                                @{{ rank(index) }}
                            </td>
                            <td>
                                <a href="#" @click.prevent="openUser(user.username)">
                                    @{{ user.username }}
                                </a>
                            </td>
                            <td class="text-center">
                                @{{ user.experience | number }}
                            </td>
                            <td class="text-center">
                                @{{ user.mission_count | number }}
                            </td>
                            <td class="text-center">
                                @{{ user.expedition_count | number }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @include('partials.pager')
        </template>
    </div>
</leaderboard>
