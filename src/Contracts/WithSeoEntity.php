<?php

namespace NovaSeoEntity\Contracts;

interface WithSeoEntity
{

    /**
     * Get seo information relationship.
     */
    public function seo_info(): \Illuminate\Database\Eloquent\Relations\MorphOne;

    /**
     * Get default value.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getNewInstanceSeoValueFor(string $key): mixed;

    /**
     * Get value for.
     *
     * @param string $key
     * @param mixed|null $value
     *
     * @return mixed
     */
    public function getSEOFieldValue(string $key, mixed $value = null): mixed;
}
