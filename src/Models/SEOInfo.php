<?php

namespace NovaSeoEntity\Models;

use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Database\Eloquent\Model;
use NovaSeoEntity\Contracts\WithSeoEntity;
use SimpleImageManager\Eloquent\HasThinkImage;
use SimpleImageManager\Eloquent\ThinkImage;

class SEOInfo extends Model
{
    use HasThinkImage;

    public static string $thinkSeoImgDriver = 'seo_image';

    /**
     * @inheritdoc
     */
    protected $guarded = [];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'meta'          => 'json',
        'open_graph'    => 'json',
        'twitter_card'  => 'json',
        'json_ld'       => 'json',
        'json_ld_multi' => 'json',
    ];

    /**
     * @inheritDoc
     */
    public function getTable()
    {
        return config('nova-seo-entity.table', parent::getTable());
    }

    /**
     * Get model used token
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function seoptimisable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('seoptimisable');
    }

    /** @inerhitDoc */
    public function thinkImagesMap(): array
    {
        return [
            'image' => ( new ThinkImage(static::$thinkSeoImgDriver, $this->image) ),
        ];
    }

    public function seoImage(): ThinkImage
    {
        return $this->thinkImage('image');
    }

    /**
     * Prepare seo data for view.
     *
     * @return static
     */
    public function seoPrepare(): static
    {
        /** @var $model WithSeoEntity|null */
        $model = ($this->seoptimisable instanceof WithSeoEntity) ? $this->seoptimisable : null;

        if ($value = $model?->getSEOFieldValue('robots', $this->robots) ?? $this->robots) {
            SEOMeta::setRobots($value);
        }

        if ($value = $model?->getSEOFieldValue('title', $this->title) ?? $this->title) {
            SEOTools::setTitle($value);
        }

        if ($value = $model?->getSEOFieldValue('description', $this->description) ?? $this->description) {
            SEOTools::setDescription($value);
        }

        if ($value = $model?->getSEOFieldValue('canonical', $this->canonical) ?? $this->canonical) {
            SEOTools::setCanonical(
                preg_match('/^http(s)?:\/\//', $value) ?
                    $value :
                    (rtrim(config('nova-seo-entity.canonical_url'), '/') . '/' . ltrim($value, '/'))
            );
        }

        $twitterImage = $this->seoImage()->exists('2x1') ? $this->seoImage()->url('2x1'):null;
        if ($value = $model?->getSEOFieldValue('img_twitter', $twitterImage) ?? $twitterImage) {
            SEOTools::twitter()->setImage($value);
        }

        $opengraphImage = $this->seoImage()->exists('1_91x1') ? $this->seoImage()->url('1_91x1'):null;
        if ($value = $model?->getSEOFieldValue('img_opengraph', $opengraphImage) ?? $opengraphImage) {
            SEOTools::opengraph()->addImage($value);
        }

        $ldImages = array_filter([
            $this->seoImage()->exists('1x1') ? $this->seoImage()->url('1x1'):null,
            $this->seoImage()->exists('4x3') ? $this->seoImage()->url('4x3'):null,
            $this->seoImage()->exists('16x9') ? $this->seoImage()->url('16x9'):null,
        ]);
        if (($value = $model?->getSEOFieldValue('img_json_ld', $ldImages) ?? $ldImages) && !empty($value)) {
            SEOTools::jsonLd()->setImages($value);
        }

        return $this;
    }
}
