<bookmark url="{{ route('api_bookmark') }}"
          destroy-url="{{ route('api_bookmark_destroy', '__bookmark__') }}" inline-template>
    <div class="bookmark modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('messages.bookmark.plural') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <div v-if="isEmpty" class="modal-body">
                    <p class="text-center">
                        {{ __('messages.bookmark.empty') }}
                    </p>
                </div>
                <template v-else>
                    <div v-for="(bookmark, index) in data.data"
                         class="modal-body"
                         :class="{separator: index > 0}">
                        <div class="row">
                            <div class="col-lg-5 text-center text-lg-left">
                                <span class="item item-sm star">
                                    @{{ bookmark.name }}
                                </span>
                            </div>
                            <div class="col-lg-3 pt-lg-2 text-center">
                                <h5>
                                    <i class="far fa-clock"></i>
                                </h5>
                                <h5>
                                    @{{ bookmark.created_at | fromNow }}
                                </h5>
                            </div>
                            <div class="col-lg-2 pt-lg-3">
                                <button class="btn btn-primary btn-block"
                                        type="button"
                                        @click="move(bookmark)">
                                    {{ __('messages.move') }}
                                </button>
                            </div>
                            <div class="col-lg-2 pt-2 pt-lg-3">
                                <button class="btn btn-danger btn-block"
                                        type="button"
                                        @click="destroy(bookmark)">
                                    {{ __('messages.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    @include('partials.pager')
                </template>
            </div>
        </div>
    </div>
</bookmark>
