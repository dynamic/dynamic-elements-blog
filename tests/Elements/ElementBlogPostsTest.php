<?php

namespace Dynamic\Elements\Blog\Tests\Elements;

use Dynamic\Elements\Blog\Elements\ElementBlogPosts;
use SilverStripe\Blog\Model\Blog;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\SS_List;

class ElementBlogPostsTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'ElementBlogPostsTest.yml';

    /**
     *
     */
    public function testGetSummary()
    {
        $object = $this->objFromFixture(ElementBlogPosts::class, 'one');
        $count = $object->getPostsList()->count();
        $this->assertEquals(
            $object->getSummary(),
            _t(
                BlogPost::class . 'PLURALS',
                'A Blog Post|{count} Blog Posts',
                ['count' => $count]
            )
        );
    }

    /**
     *
     */
    public function testGetType()
    {
        $object = $this->objFromFixture(ElementBlogPosts::class, 'one');
        $this->assertEquals($object->getType(), 'Blog Posts');
    }

    /**
     *
     */
    public function testGetCMSFields()
    {
        $object = $this->objFromFixture(ElementBlogPosts::class, 'one');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
        $this->assertNotNull($fields->dataFieldByName('BlogID'));
    }

    /**
     *
     */
    public function testGetPostsList()
    {
        $object = $this->objFromFixture(ElementBlogPosts::class, 'one');
        $this->compareList(
            $object->Blog()->getBlogPosts()->limit($object->Limit),
            $object->getPostsList(),
            'Should only return blog post assign to blog page'
        );

        $object = $this->objFromFixture(ElementBlogPosts::class, 'targetCategory');
        $category = $this->objFromFixture(BlogCategory::class, 'category');
        $this->compareList(
            $category->BlogPosts()->limit($object->Limit),
            $object->getPostsList(),
            'Should only return blog post assign to blog category'
        );

        $object = $this->objFromFixture(ElementBlogPosts::class, 'noTarget');
        $this->compareList(
            BlogPost::get()->limit($object->Limit),
            $object->getPostsList(),
            'Should return all blog posts'
        );

        $object = $this->objFromFixture(ElementBlogPosts::class, 'badTarget');
        $this->compareList(
            BlogPost::get()->limit($object->Limit),
            $object->getPostsList(),
            'When ElementalBlogPost is misconfigured shoudl return all blog post'
        );
    }

    /**
     * Compare entries in $expected to entries in actual
     * @param DataList $expected
     * @param DataList $actual
     * @param string $message
     */
    private function compareList(DataList $expected, DataList $actual, $message = '')
    {
        $expectedArray = $expected->map('ID', 'ClassName')->toArray();
        $actualArray = $expected->map('ID', 'ClassName')->toArray();
        $this->assertEquals($expectedArray, $actualArray, $message);
    }
}
