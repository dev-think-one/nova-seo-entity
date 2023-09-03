<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Seo table name
    |--------------------------------------------------------------------------
    |
    */
    'table' => env('NOVA_SEO_TABLE', 'cms_seo'),

    /*
    |--------------------------------------------------------------------------
    | Seo table name
    |--------------------------------------------------------------------------
    |
    */
    'morph' => [
        'types' => array_filter(array_map('trim', explode(',', env('NOVA_SEO_MORPH_TYPES', '')))),
    ],

    'canonical_url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Seo image crop configuration
    |--------------------------------------------------------------------------
    | See https://github.com/think.studio/laravel-simple-image-manager
    */
    'seo_image' => [
    'disk'                 => 'cms-images',
    'immutable_extensions' => [ '.svg', '.gif' ],
    'prefix'               => 'seo-image' . DIRECTORY_SEPARATOR,
    'original'             => [
        'methods' => [
            'fit'      => [ \Spatie\Image\Manipulations::FIT_MAX, 2400, 1200 ],
            'optimize' => [],
        ],
    ],
    'deletedFormats'       => [],
    'formats'              => [
        /* StructuredData, Facebook */
        '1x1'       => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 1200, 1200 ],
                'optimize' => [],
            ],
        ],
        /* StructuredData */
        '4x3'       => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 1200, 900 ],
                'optimize' => [],
            ],
        ],
        /* StructuredData */
        '16x9'      => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 1200, 676 ],
                'optimize' => [],
            ],
        ],
        /* Facebook */
        '1_91x1'    => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 1200, 628 ],
                'optimize' => [],
            ],
        ],
        /* Twitter */
        '2x1'       => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 1200, 600 ],
                'optimize' => [],
            ],
        ],
        /* Twitter */
        '1x1-small' => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 600, 600 ],
                'optimize' => [],
            ],
        ],
        /* Thumbnail */
        'thumbnail' => [
            'methods' => [
                'fit'      => [ \Spatie\Image\Manipulations::FIT_CROP, 300, 300 ],
                'optimize' => [],
            ],
        ],
    ],
],

];
