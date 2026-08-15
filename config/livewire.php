<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root class namespace for Livewire component classes in your
    | application. This value will change where component auto-discovery look.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path where Livewire component views are stored.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Layout View
    |--------------------------------------------------------------------------
    |
    | The default layout view used when rendering full-page components.
    |
    */

    'layout' => 'layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    |
    | Livewire allows you to lazy load components that would otherwise slow down
    | page render times. By default, Livewire will render a generic loading
    | indicator while the component loads. You can customize this layout here.
    |
    */

    'lazy_placeholder' => null,

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploaded files in a temporary
    | directory before they are validated and stored permanently.
    |
    */

    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'
        'rules' => null,       // Example: ['file', 'mimes:png,jpg', 'max:1024']
        'directory' => null,   // Example: 'tmp'
        'middleware' => null,  // Example: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for preview.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a', 'jpg',
            'jpeg', 'mpga', 'webp', 'wma', 'ogg',
            'oga', 'flac', 'aac', 'calc', 'doc',
            'docx', 'dotx', 'dotm', 'elo', 'elm',
            'ods', 'odt', 'pdf', 'potx', 'ppt',
            'pptx', 'rtf', 'xls', 'xlsx',
        ],
        'max_upload_time' => 5, // Max duration (in minutes) an upload is valid.
    ],

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines if Livewire will render the component when redirecting
    | to a new page or if it should perform a standard HTTP redirect.
    |
    */

    'render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Eloquent Model Binding
    |--------------------------------------------------------------------------
    |
    | Livewire supports binding Eloquent models directly to component properties.
    | You can configure model binding options here.
    |
    */

    'legacy_model_binding' => false,

    /*
    |--------------------------------------------------------------------------
    | Auto-inject Frontend Assets
    |--------------------------------------------------------------------------
    |
    | By default, Livewire injects JavaScript and CSS assets into your pages.
    | If you prefer to manually include assets, set this to false.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigate Options
    |--------------------------------------------------------------------------
    |
    | Configure options for Livewire's wire:navigate feature.
    |
    */

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Morph Options
    |--------------------------------------------------------------------------
    |
    | Configure morph options for DOM updates.
    |
    */

    'inject_morph_markers' => true,

    'pagination_theme' => 'tailwind',

];
