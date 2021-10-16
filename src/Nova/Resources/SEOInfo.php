<?php

namespace NovaSeoEntity\Nova\Resources;

use App\Nova\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Resource as NovaResource;
use NovaSeoEntity\Models\SEOInfo as SEOInfoModel;

/** @psalm-suppress UndefinedClass **/
class SEOInfo extends NovaResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = SEOInfoModel::class;

    public static $displayInNavigation = false;

    /**
     * @var Model|null
     */
    protected ?Model $cachedViaModel = null;

    /**
     * @inerhitDoc
     */
    public static function label()
    {
        return 'SEO';
    }

    /**
     * @inerhitDoc
     */
    public static function uriKey()
    {
        return 'seo-entities';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static array $morphToTypes = [];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];

    /**
     * Set morph types.
     *
     * @param array $morphToTypes
     */
    public static function morphToTypes(array $morphToTypes = [])
    {
        static::$morphToTypes = $morphToTypes;
    }

    /**
     * Get morph types.
     *
     * @return array
     */
    public function getMorphToTypes(): array
    {
        return array_unique(array_merge(static::$morphToTypes, config('nova-seo-entity.morph.types')));
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),

            MorphTo::make('SEOptimisable', 'seoptimisable')
                   ->types($types = $this->getMorphToTypes())
                   ->searchable(),

            new Panel('General information', $this->generalInformationFields($request)),
        ];
    }

    /**
     * @param Request $request
     * @param bool $fresh
     *
     * @return Model|null
     */
    protected function getViaModel(Request $request, bool $fresh = false): ?Model
    {
        if (!$fresh && $this->cachedViaModel) {
            return $this->cachedViaModel;
        }
        if ($request instanceof NovaRequest
             && $request->viaRelationship()
             && $request->isCreateOrAttachRequest()) {
            /** @var Model $viaModel */
            $viaModel = $request->newViaResource()->model()::find($request->viaResourceId);

            return $this->cachedViaModel = $viaModel;
        }

        return null;
    }

    protected function getDefaultSeoValueFor(Request $request, string $key): mixed
    {
        $viaModel = $this->getViaModel($request);
        if ($viaModel && method_exists($viaModel, 'getNewInstanceSeoValueFor')) {
            return $viaModel->getNewInstanceSeoValueFor($key, $request);
        }

        return null;
    }

    /**
     * Get the general information fields.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function generalInformationFields(Request $request): array
    {
        return [
            Text::make('Title', 'title')
                ->rules('nullable', 'string', 'max:255')
                ->default($this->getDefaultSeoValueFor($request, 'title'))
                ->help('Optimal size up to about 50–60 characters.')
                ->hideFromIndex(),

            Textarea::make('Description', 'description')
                    ->rules('nullable', 'string', 'max:255')
                    ->default($this->getDefaultSeoValueFor($request, 'description'))
                    ->help('Optimal size up to about 155 characters.')
                    ->hideFromIndex(),

            Text::make('Canonical', 'canonical')
                ->rules('nullable', 'string', 'max:255')
                ->default($this->getDefaultSeoValueFor($request, 'canonical'))
                ->help('If link not starts with "http" than app url will be added automatically')
                ->hideFromIndex(),

            Image::make('Image', 'image')
                 ->store(function ($request, SEOInfoModel $model, $attribute, $requestAttribute, $storageDisk, $storageDir) {
                     return function () use ($request, $model, $attribute, $requestAttribute, $storageDisk, $storageDir) {
                         $model->$attribute = $model->seoImage()
                                                    ->upload(
                                                        $request->file($requestAttribute),
                                                        "{$model->getKey()}/" . Str::uuid(),
                                                        $model->$attribute
                                                    );
                         $model->save();
                     };
                 })
                 ->maxWidth(300)
                 ->preview(fn ($value, $storageDisk, SEOInfoModel $model) => $model->seoImage()->url('thumbnail'))
                 ->thumbnail(fn ($value, $storageDisk, SEOInfoModel $model) => $model->seoImage()->url('thumbnail'))
                 ->delete(fn ($request, SEOInfoModel $model, $storageDisk, $storagePath) => $model->seoImage()->delete())
                 ->help('Please use image: 2400x1200 or 1200x(>=628) (All required images will be cropped)'),

            // image
            // meta
            // open_graph
            // twitter_card
            // json_ld
            // json_ld_multi
        ];
    }
}
