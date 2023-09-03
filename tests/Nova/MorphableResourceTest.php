<?php

namespace NovaSeoEntity\Tests\Nova;

use Illuminate\Http\UploadedFile;
use NovaSeoEntity\Tests\Fixtures\Models\Post;
use NovaSeoEntity\Tests\Fixtures\Models\User;
use NovaSeoEntity\Tests\TestCase;

class MorphableResourceTest extends TestCase
{
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();

        $this->actingAs($this->admin);
    }

    /** @test */
    public function get_form()
    {
        Post::factory()->count(12)->create();
        $post = Post::factory()->create();

        $uriKey = \NovaSeoEntity\Tests\Fixtures\Nova\Resources\Post::uriKey();

        $response = $this->get("nova-api/{$uriKey}/{$post->getKey()}/update-fields");

        $this->assertEquals('seo_info', $response->json('fields.2.hasOneRelationship'));
        $this->assertEquals('morphOne', $response->json('fields.2.relationshipType'));
    }

    /** @test */
    public function get_morph_form()
    {
        Post::factory()->count(12)->create();
        /** @var Post $post */
        $post = Post::factory()->create();

        $seoInfo = $post->seo_info()->create([
            'title'       => 'Foo',
            'description' => 'Bar',
            'canonical'   => 'http://test.baz',
            'image'       => 'foo/qux.png',
            'robots'      => 'no-follow foo',
        ]);

        $uriKey = \NovaSeoEntity\Nova\Resources\SEOInfo::uriKey();

        $response = $this->get("nova-api/{$uriKey}/{$seoInfo->getKey()}/update-fields", [
            'viaResource'     => \NovaSeoEntity\Tests\Fixtures\Nova\Resources\Post::uriKey(),
            'viaResourceId'   => $post->getKey(),
            'viaRelationship' => 'seo_info',
        ]);

        $this->assertIsArray($response->json('fields'));
        $this->assertCount(6, $response->json('fields'));

        $this->assertEquals('title', $response->json('fields.2.attribute'));
        $this->assertEquals('Foo', $response->json('fields.2.value'));

        $this->assertEquals('description', $response->json('fields.3.attribute'));
        $this->assertEquals('Bar', $response->json('fields.3.value'));

        $this->assertEquals('canonical', $response->json('fields.4.attribute'));
        $this->assertEquals('http://test.baz', $response->json('fields.4.value'));

        $this->assertEquals('image', $response->json('fields.5.attribute'));
        $this->assertEquals('/storage/foo/qux-thumbnail.png', $response->json('fields.5.thumbnailUrl'));

        $this->assertEquals('robots', $response->json('fields.6.attribute'));
        $this->assertEquals('no-follow foo', $response->json('fields.6.value'));
    }

    /** @test */
    public function get_morph_empty_form()
    {
        Post::factory()->count(12)->create();
        /** @var Post $post */
        $post = Post::factory()->create();

        $uriKey = \NovaSeoEntity\Nova\Resources\SEOInfo::uriKey();

        $response = $this->get("nova-api/{$uriKey}/creation-fields?" . http_build_query([
                'viaResource'      => \NovaSeoEntity\Tests\Fixtures\Nova\Resources\Post::uriKey(),
                'viaResourceId'    => $post->getKey(),
                'viaRelationship'  => 'seo_info',
                'relationshipType' => 'morphOne',
                'editing'          => true,
                'editMode'         => 'update',
            ]));

        $this->assertIsArray($response->json('fields'));
        $this->assertCount(6, $response->json('fields'));

        $this->assertEquals('title', $response->json('fields.1.attribute'));
        $this->assertEquals($post->title, $response->json('fields.1.value'));

        $this->assertEquals('description', $response->json('fields.2.attribute'));
        $this->assertEquals("{$post->title} - My Description.", $response->json('fields.2.value'));

        $this->assertEquals('canonical', $response->json('fields.3.attribute'));
        $this->assertNull($response->json('fields.3.value'));

        $this->assertEquals('image', $response->json('fields.4.attribute'));
        $this->assertNull($response->json('fields.4.value'));

        $this->assertEquals('robots', $response->json('fields.5.attribute'));
        $this->assertNull($response->json('fields.5.value'));
    }

    /** @test */
    public function update_morph_form()
    {
        Post::factory()->count(12)->create();
        /** @var Post $post */
        $post    = Post::factory()->create();
        $seoInfo = $post->seo_info()->create([
            'title'       => 'Foo',
            'description' => 'Bar',
            'canonical'   => 'http://test.baz',
            'image'       => 'foo/qux.png',
            'robots'      => 'no-follow foo',
        ]);

        $uriKey = \NovaSeoEntity\Tests\Fixtures\Nova\Resources\Post::uriKey();

        $response = $this->put("nova-api/{$uriKey}/{$post->getKey()}", [
            'seo_info' => [
                'title'       => 'New Foo title',
                'robots'      => 'Newbar-follow',
                'description' => 'new baz',
                'canonical'   => 'other',
                'image'       => UploadedFile::fake()->image('avatar.jpg'),
            ],
        ]);

        $response->assertSuccessful();

        $seoInfo->refresh();

        $this->assertEquals('New Foo title', $seoInfo->title);
        $this->assertEquals('Newbar-follow', $seoInfo->robots);
        $this->assertEquals('new baz', $seoInfo->description);
        $this->assertEquals('other', $seoInfo->canonical);
        $this->assertStringStartsWith("seo-image/{$seoInfo->getKey()}/", $seoInfo->image);
    }
}
