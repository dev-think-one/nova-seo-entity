<?php

namespace NovaSeoEntity\Tests\Models;

use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\UploadedFile;
use NovaSeoEntity\Models\SEOInfo;
use NovaSeoEntity\Tests\Fixtures\Models\Post;
use NovaSeoEntity\Tests\TestCase;

class SEOInfoModelTest extends TestCase
{
    /** @test */
    public function prepare_seo()
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var SEOInfo $seoInfo */
        $seoInfo = $post->seo_info()->create([
            'title'       => 'Foo',
            'description' => 'Bar',
            'canonical'   => 'http://test.baz',
            'image'       => 'foo/qux.png',
            'robots'      => 'no-follow foo',
        ]);

        $seoInfo->image =  $seoInfo->seoImage()->upload(UploadedFile::fake()->image('avatar.jpg'));
        $seoInfo->save();

        $value = SEOTools::generate();

        $seoModel = $post->seo_info_forced->seoPrepare();

        $newValue = SEOTools::generate();

        $this->assertStringNotContainsString('content="Overridden: Bar"', $value);
        $this->assertStringContainsString('content="Overridden: Bar"', $newValue);
    }

}
