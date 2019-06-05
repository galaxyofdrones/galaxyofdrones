<message url="{{ route('api_message_store') }}" inline-template>
    <div class="setting modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ trans('messages.message.singular') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </div>
                <form method="post" @submit.prevent="submit()">
                    <div class="modal-body">
                        <div class="form-group" :class="{'has-error': hasError('recipient')}">
                            <label class="required" for="recipient">
                                {{ trans('validation.attributes.recipient') }}
                            </label>
                            <input id="recipient"
                                   class="form-control form-control-lg"
                                   :class="{'is-invalid': hasError('recipient')}"
                                   type="text"
                                   name="recipient"
                                   :value="form.recipient"
                                   placeholder="{{ trans('validation.attributes.recipient') }}" disabled>
                            <span v-if="hasError('recipient')" class="invalid-feedback">
                                @{{ error('recipient') }}
                            </span>
                        </div>
                        <div class="form-group" :class="{'has-error': hasError('message')}">
                            <label class="required" for="message">
                                {{ trans('validation.attributes.message') }}
                            </label>
                            <textarea id="message"
                                      class="form-control form-control-lg"
                                      :class="{'is-invalid': hasError('message')}"
                                      name="message"
                                      rows="6"
                                      v-model="form.message"
                                      placeholder="{{ trans('validation.attributes.message') }}" required></textarea>
                            <span v-if="hasError('message')" class="invalid-feedback">
                                @{{ error('message') }}
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">
                            {{ trans('messages.message.send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</message>
