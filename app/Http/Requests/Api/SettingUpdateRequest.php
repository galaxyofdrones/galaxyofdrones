<?php

namespace Koodilab\Http\Requests\Api;

use Koodilab\Http\Requests\Request;
use Koodilab\Models\Setting;

class SettingUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
        ];
    }

    /**
     * Persist the request.
     *
     * @param Setting[] $settings
     */
    public function persist($settings)
    {
        foreach ($settings as $setting) {
            $setting->setFallbackLocale()
                ->setTranslation('value', $this->get($setting->key))
                ->save();
        }
    }
}
