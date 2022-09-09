<?php

namespace NovaSeoEntity\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NovaSeoEntity\Tests\Fixtures\Models\Post;
use NovaSeoEntity\Tests\Fixtures\Models\PostNotModel;

class HasSeoEntityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function has_relation()
    {
        /** @var Post $post */
        $post = Post::newFake()->create();

        $this->assertNull($post->seo_info);

        $data = [
            'robots'       => 'nofoo,nobar',
            'title'        => 'Test title',
            'description'  => 'Test description',
            'canonical'    => 'test-canonical',
            'image'        => 'test-image',
        ];

        $post->seo_info()->create($data);

        $post->refresh();

        $this->assertEquals($data['robots'], $post->seo_info->robots);
        $this->assertEquals($data['title'], $post->seo_info->title);
        $this->assertEquals($data['description'], $post->seo_info->description);
        $this->assertEquals($data['canonical'], $post->seo_info->canonical);
        $this->assertEquals($data['image'], $post->seo_info->image);
    }

    /** @test */
    public function get_new_instance_seo_value_for()
    {
        /** @var Post $post */
        $post = Post::newFake()->create();

        $this->assertNull($post->seo_info);

        $this->assertEquals($post->title, $post->getNewInstanceSeoValueFor('title'));
        $this->assertEquals($post->title . ' - My Description.', $post->getNewInstanceSeoValueFor('description'));


        $postNotModel = new PostNotModel();
        $this->assertNull($postNotModel->getNewInstanceSeoValueFor('title'));
    }

    /** @test */
    public function get_seo_field_value()
    {
        /** @var Post $post */
        $post = Post::newFake()->create();

        $this->assertNull($post->seo_info);

        $data = [
            'title'       => 'Test title',
            'description' => 'Test description',
        ];

        $post->seo_info()->create($data);

        $post->refresh();

        $this->assertEquals('Test title', $post->getSEOFieldValue('title', $post->seo_info->title));
        $this->assertEquals($post->getSEODescriptionFieldValue($post->seo_info->description), $post->getSEOFieldValue('description', $post->seo_info->description));
        $this->assertNull($post->getSEOFieldValue('canonical', $post->seo_info->canonical));
    }
}
