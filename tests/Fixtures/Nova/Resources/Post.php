<?php

namespace NovaSeoEntity\Tests\Fixtures\Nova\Resources;

use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use NovaSeoEntity\Nova\Resources\SEOInfo;

/**
 * @extends Resource<\NovaSeoEntity\Tests\Fixtures\Models\Post>
 */
class Post extends Resource
{

    public static $model = \NovaSeoEntity\Tests\Fixtures\Models\Post::class;

    public static $title = 'title';

    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Title', 'title'),
            Textarea::make('Content', 'content'),
            MorphOne::make('SEO', 'seo_info', SEOInfo::class),
        ];
    }

}
