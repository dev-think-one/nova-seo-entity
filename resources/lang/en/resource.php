<?php

return [
    'label'  => 'SEO',
    'panels' => [
        'general' => 'General information',
    ],
    'fields' => [
        'seoptimisable'  => 'SEOptimisable',
        'robots'         => 'Robots meta tag',
        'title'          => 'Title',
        'description'    => 'Description',
        'canonical'      => 'Canonical',
        'image'          => 'Image',
    ],
    'placeholder' => [
        'robots'         => 'noindex, nofollow',
    ],
    'help'   => [
        'seoptimisable'  => 'Related entity',
        'robots'         => 'Id empty, no tag will be present.',
        'title'          => 'Optimal size up to about 50–60 characters.',
        'description'    => 'Optimal size up to about 155 characters.',
        'canonical'      => 'If link not starts with "http" than app url will be added automatically',
        'image'          => 'Please use image: 2400x1200 or 1200x(>=628) (All required images will be cropped)',
    ],
];
