<?php

namespace NovaSeoEntity\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NovaSeoEntity\Contracts\WithSeoEntity;
use NovaSeoEntity\Models\Traits\HasSeoEntity;
use NovaSeoEntity\Tests\Fixtures\Factories\PostFactory;

class Post extends Model implements WithSeoEntity
{
    use HasSeoEntity;
    use HasFactory;

    protected $table = 'posts';

    protected $guarded = [];

    public function getNewInstanceSeoValueForDescription(): string
    {
        return $this->title . ' - My Description.';
    }

    public function getSEODescriptionFieldValue(mixed $value = null): string
    {
        return 'Overridden: ' . $value;
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}
