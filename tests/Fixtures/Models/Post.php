<?php

namespace NovaSeoEntity\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NovaSeoEntity\Contracts\WithSeoEntity;
use NovaSeoEntity\Models\Traits\HasSeoEntity;

class Post extends Model implements WithSeoEntity
{
    use HasSeoEntity;

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


    public static function newFake()
    {
        return new static([
            'title'   => 'Title ' . Str::random(),
            'content' => implode(' ', array_fill(0, 50, Str::random())),
        ]);
    }
}
