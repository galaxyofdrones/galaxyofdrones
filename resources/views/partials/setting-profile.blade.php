<form v-if="isSelectedTab('profile')" method="post" @submit.prevent="submit()">
    <div class="modal-body {{ auth()->user()->can('viewDeveloperSetting') ? 'separator' : '' }}">
        <div class="form-group">
            <label class="required" for="email">
                {{ trans('validation.attributes.email') }}
            </label>
            <input id="email"
                   class="form-control form-control-lg"
                   :class="{'is-invalid': hasError('email')}"
                   type="email"
                   name="email"
                   v-model="form.email"
                   placeholder="{{ trans('validation.attributes.email') }}" required>
            <span v-if="hasError('email')" class="invalid-feedback">
                @{{ error('email') }}
            </span>
        </div>
        <div class="form-group">
            <label for="password">
                {{ trans('validation.attributes.password') }}
            </label>
            <input id="password"
                   class="form-control form-control-lg"
                   :class="{'is-invalid': hasError('password')}"
                   type="password"
                   name="password"
                   v-model="form.password"
                   placeholder="{{ trans('validation.attributes.password') }}">
            <span v-if="hasError('password')" class="invalid-feedback">
                @{{ error('password') }}
            </span>
        </div>
        <div class="form-group">
            <label for="password_confirmation">
                {{ trans('validation.attributes.password_confirmation') }}
            </label>
            <input id="password_confirmation"
                   class="form-control form-control-lg"
                   :class="{'is-invalid': hasError('password_confirmation')}"
                   type="password"
                   name="password_confirmation"
                   v-model="form.password_confirmation"
                   placeholder="{{ trans('validation.attributes.password_confirmation') }}">
            <span v-if="hasError('password_confirmation')" class="invalid-feedback">
                @{{ error('password_confirmation') }}
            </span>
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_notification_enabled"
                       v-model="form.is_notification_enabled">
                {{ trans('validation.attributes.is_notification_enabled') }}
            </label>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-lg btn-block" type="submit">
            {{ trans('messages.save') }}
        </button>
    </div>
</form>
