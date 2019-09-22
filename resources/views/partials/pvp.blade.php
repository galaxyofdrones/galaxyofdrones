<leaderboard :is-enabled="isEnabled && isSelectedTab('pvp')"
             :open-after-hidden="openAfterHidden"
             url="{{ route('api_rank_pvp') }}" inline-template>
    <div v-if="isEnabled" class="pvp">
        <div v-if="isEmpty" class="modal-body separator">
            <p class="text-center">
                {{ __('messages.user.empty') }}
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
                                {{ __('validation.attributes.username') }}
                            </th>
                            <th class="text-center">
                                {{ __('validation.attributes.planet') }}
                            </th>
                            <th class="text-center">
                                {{ __('validation.attributes.winning_battle') }}
                            </th>
                            <th class="text-center">
                                {{ __('validation.attributes.losing_battle') }}
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
                                @{{ user.planet_count | number }}
                            </td>
                            <td class="text-center highlight-success">
                                @{{ user.winning_battle_count | number }}
                            </td>
                            <td class="text-center highlight-danger">
                                @{{ user.losing_battle_count | number }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @include('partials.pager')
        </template>
    </div>
</leaderboard>
