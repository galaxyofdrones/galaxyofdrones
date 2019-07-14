@php
    $settings = setting()->all()->map->translation('value');
@endphp
<developer-setting v-if="isSelectedTab('developer')"
                   url="{{ route('api_setting_update') }}"
                   :settings='@json($settings)' inline-template>
    <form method="post" @submit.prevent="submit()" novalidate>
        <div class="modal-body">
            <div class="form-group">
                <label class="required" for="title">
                    {{ trans('validation.attributes.title') }}
                </label>
                <input id="title"
                       class="form-control form-control-lg"
                       :class="{'is-invalid': hasError('title')}"
                       type="text"
                       name="title"
                       v-model="form.title"
                       placeholder="{{ trans('validation.attributes.title') }}" required>
                <span v-if="hasError('title')" class="invalid-feedback">
                    @{{ error('title') }}
                </span>
            </div>
            <div class="form-group">
                <label for="description">
                    {{ trans('validation.attributes.description') }}
                </label>
                <input id="description"
                       class="form-control form-control-lg"
                       :class="{'is-invalid': hasError('description')}"
                       type="text"
                       name="description"
                       v-model="form.description"
                       placeholder="{{ trans('validation.attributes.description') }}">
                <span v-if="hasError('description')" class="invalid-feedback">
                    @{{ error('description') }}
                </span>
            </div>
            <div class="form-group">
                <label for="author">
                    {{ trans('validation.attributes.author') }}
                </label>
                <input id="author"
                       class="form-control form-control-lg"
                       :class="{'is-invalid': hasError('author')}"
                       type="text"
                       name="author"
                       v-model="form.author"
                       placeholder="{{ trans('validation.attributes.author') }}">
                <span v-if="hasError('author')" class="invalid-feedback">
                    @{{ error('author') }}
                </span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary btn-lg btn-block"
                    type="submit"
                    :disabled="isSubmitted">
                {{ trans('messages.save') }}
            </button>
        </div>
    </form>
</developer-setting>
