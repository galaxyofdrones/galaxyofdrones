<?php

return [

    'extensions' => [

        /*
        |--------------------------------------------------------------------------
        | Extensions
        |--------------------------------------------------------------------------
        |
        | Enabled extensions.
        |
        | `Twig_Extension_Debug` is enabled automatically if twig.debug is TRUE.
        |
        */

        'enabled' => [
            TwigBridge\Extension\Loader\Facades::class,
            TwigBridge\Extension\Loader\Filters::class,
            TwigBridge\Extension\Loader\Functions::class,
            TwigBridge\Extension\Laravel\Auth::class,
            TwigBridge\Extension\Laravel\Config::class,
            TwigBridge\Extension\Laravel\Dump::class,
            TwigBridge\Extension\Laravel\Input::class,
            TwigBridge\Extension\Laravel\Session::class,
            TwigBridge\Extension\Laravel\Str::class,
            TwigBridge\Extension\Laravel\Translator::class,
            TwigBridge\Extension\Laravel\Url::class,
            TwigBridge\Extension\Laravel\Gate::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Functions
        |--------------------------------------------------------------------------
        |
        | Available functions. Access like `{{ secure_url(...) }}`.
        |
        | Each function can take an optional array of options. These options are
        | passed directly to `Twig_SimpleFunction`.
        |
        | So for example, to mark a function as safe you can do the following:
        |
        | <code>
        |     'link_to' => [
        |         'is_safe' => ['html']
        |     ]
        | </code>
        |
        | The options array also takes a `callback` that allows you to name the
        | function differently in your Twig templates than what it's actually called.
        |
        | <code>
        |     'link' => [
        |         'callback' => 'link_to'
        |     ]
        | </code>
        |
        */

        'functions' => [
            'head',
            'last',
            'mix',
            'setting',
            'vue',

            'shield_expiration' => [
                'callback' => 'Koodilab\Models\Shield::expiration',
            ],

            'user_role_options' => [
                'callback' => 'Koodilab\Models\User::roleOptions',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        |
        | Available filters. Access like `{{ variable|filter }}`.
        |
        | Each filter can take an optional array of options. These options are
        | passed directly to `Twig_SimpleFilter`.
        |
        | So for example, to mark a filter as safe you can do the following:
        |
        | <code>
        |     'studly_case' => [
        |         'is_safe' => ['html']
        |     ]
        | </code>
        |
        | The options array also takes a `callback` that allows you to name the
        | filter differently in your Twig templates than what is actually called.
        |
        | <code>
        |     'snake' => [
        |         'callback' => 'snake_case'
        |     ]
        | </code>
        |
        */

        'filters' => [
            'get' => 'data_get',
        ],

    ],

];
