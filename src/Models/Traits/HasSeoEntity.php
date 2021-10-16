<?php

namespace NovaSeoEntity\Models\Traits;

use Illuminate\Support\Str;

trait HasSeoEntity
{

    /**
     * Get seo information relationship.
     */
    public function seo_info(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(\NovaSeoEntity\Models\SEOInfo::class, 'seoptimisable');
    }

    /**
     * Get default value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getNewInstanceSeoValueFor(string $key): mixed
    {
        $method = 'getNewInstanceSeoValueFor' . Str::ucfirst(Str::camel($key));
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        if (method_exists($this, 'getAttribute')) {
            return $this->getAttribute($key);
        }

        return null;
    }

    /**
     * Get value for.
     *
     * @param string $key
     * @param mixed|null $value
     *
     * @return mixed
     */
    public function getSEOFieldValue(string $key, mixed $value = null): mixed
    {
        $method = 'getSEO' . Str::ucfirst(Str::camel($key)) . 'FieldValue';
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        return $value;
    }
}
