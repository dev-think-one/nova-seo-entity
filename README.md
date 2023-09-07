# Laravel nova SEO Entity relationship

![Packagist License](https://img.shields.io/packagist/l/think.studio/nova-seo-entity?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/think.studio/nova-seo-entity)](https://packagist.org/packages/think.studio/nova-seo-entity)
[![Total Downloads](https://img.shields.io/packagist/dt/think.studio/nova-seo-entity)](https://packagist.org/packages/think.studio/nova-seo-entity)
[![Build Status](https://scrutinizer-ci.com/g/dev-think-one/nova-seo-entity/badges/build.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-seo-entity/build-status/main)
[![Code Coverage](https://scrutinizer-ci.com/g/dev-think-one/nova-seo-entity/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-seo-entity/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/dev-think-one/nova-seo-entity/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/dev-think-one/nova-seo-entity/?branch=main)

Add to any model relation with SEO data.

| Nova | Package |
|------|---------|
| V1   | V1,V2   |
| V4   | V3,V4   |

## Installation

You can install the package via composer:

```bash
composer require think.studio/nova-seo-entity

# optional publish configs
php artisan vendor:publish --provider="NovaSeoEntity\ServiceProvider" --tag="config"
# as current package wrap "artesaos/seotools" package, will be useful publish internal config:
php artisan vendor:publish --provider="Artesaos\SEOTools\Providers\SEOToolsServiceProvider"

# publish translations
php artisan vendor:publish --provider="NovaSeoEntity\ServiceProvider" --tag="lang"
```

## Usage

### Add seo table

```shell
php artisan make:migration create_cms_seo_table
```

```php
public function up()
{
    Schema::create(config('nova-seo-entity.table'), function (Blueprint $table) {
        \NovaSeoEntity\Database\MigrationHelper::defaultColumns($table);
    });
}

public function down()
{
    Schema::dropIfExists(config('nova-seo-entity.table'));
}
```

### Amend models

```php
class Article extends Model implements \NovaSeoEntity\Contracts\WithSeoEntity
{
    use \NovaSeoEntity\Models\Traits\HasSeoEntity;
    // ...
    
    /**
     * Example how set default value for nova "creation" screen
     */
    public function getNewInstanceSeoValueForDescription( ): ?string {
        return Str::limit( WysiwygHelper::html2Text( $this->content ), 150 );
    }
    
    /**
     * Override canonical value if not set
     */
    public function getSEOCanonicalFieldValue( mixed $value ): mixed {
        return $value ?: ($this->slug ? route( 'front.article.single', $this->slug ) : null);
    }

}
```

### Amend nova resource

Add new field to your resource

```php
MorphOne::make('SEO', 'seo_info', SEOInfo::class),
```

### Amend app service provider

You can add resource from package or extend it in your app.

```php
// NovaServiceProvider.php
use NovaSeoEntity\Nova\Resources\SEOInfo;

public function boot() {
    // ...

    SEOInfo::morphToTypes([
        \App\Nova\Resources\CMS\Article::class,
        \App\Nova\Resources\CMS\Page::class
        // ...
    ]);

    parent::boot();
}

protected function resources() {
    parent::resources();
    // ...
    Nova::resources( [
        SEOInfo::class,
        // ...
    ] );
}
```

### Add related filesystem disk (or change default disc in config file)

```php
'cms-images'  => [
    'driver'     => 'local',
    'root'       => storage_path('app/public/cms-images'),
    'url'        => env('APP_URL').'/storage/cms-images',
    'visibility' => 'public',
],
```

### Display meta data

```html

<head>
    {!! \Artesaos\SEOTools\Facades\SEOTools::generate(!config('app.debug')) !!}
</head>
```

### Implement

```php
$article = Article::find($articleId);

// Get seoinfo with default value
$article?->seo_info_forced->seoPrepare();

// Get seoinfo without defaults
// $article?->seo_info?->seoPrepare();
```

## Useful links

Facebook: [sharing](https://developers.facebook.com/docs/sharing/webmasters/)
, [best practices](https://developers.facebook.com/docs/sharing/best-practices#images).
Facebook [debuger](https://developers.facebook.com/tools/debug)

Twitter: [summary card](https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/summary)
, [summary card with large image](https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/summary-card-with-large-image)
. Twitter [validator](https://cards-dev.twitter.com/validator)

JsonLd: [intro](https://developers.google.com/search/docs/advanced/structured-data/intro-structured-data)
, [recommendations](https://developers.google.com/search/docs/advanced/structured-data/sd-policies)
, [image license](https://developers.google.com/search/docs/advanced/structured-data/image-license-metadata)
, [example](https://developers.google.com/search/docs/advanced/structured-data/article)

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)






