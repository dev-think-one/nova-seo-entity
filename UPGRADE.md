# Upgrade Guide

## General Notes

## Upgrading To 4.0 From 3.x

### Support robots tag

System now has support `robots` tag. Because of this change, you must add a `robots` column to the seo database table:

```php
Schema::table(config('nova-seo-entity.table'), function (Blueprint $table) {
    $table->string('robots')->after('seoptimisable_id')->nullable();
});
```
