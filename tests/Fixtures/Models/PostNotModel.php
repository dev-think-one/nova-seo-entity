<?php

namespace NovaSeoEntity\Tests\Fixtures\Models;

use NovaSeoEntity\Contracts\WithSeoEntity;
use NovaSeoEntity\Models\Traits\HasSeoEntity;

class PostNotModel implements WithSeoEntity
{
    use HasSeoEntity;
}
