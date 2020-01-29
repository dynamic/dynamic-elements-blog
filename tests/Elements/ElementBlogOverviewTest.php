<?php

namespace Dynamic\Elements\Blog\Tests\Elements;

use DNADesign\Elemental\Extensions\ElementalPageExtension;
use Dynamic\Elements\Blog\Elements\ElementBlogOverview;
use Page;
use SilverStripe\Blog\Model\Blog;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Widgets\Extensions\WidgetPageExtension;
use SilverStripe\Widgets\Model\Widget;
use SilverStripe\Widgets\Model\WidgetArea;

class ElementBlogOverviewTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'ElementBlogOverviewTest.yml';

    /**
     * @var array
     */
    protected static $required_extensions = [
        Page::class => [
            WidgetPageExtension::class,
            ElementalPageExtension::class,
        ],
    ];

    public function testPopulateDefaults(): void
    {
        $block = ElementBlogOverview::create();
        $this->assertEmpty($block->Title);
        $this->assertTrue((bool) $block->ShowPagination);
        $this->assertFalse((bool) $block->ShowWidgets);

        // Update our config settings
        ElementBlogOverview::config()->update('set_default_title', true);
        ElementBlogOverview::config()->update('pagination_field_default', 0);
        ElementBlogOverview::config()->update('widgets_field_default', 1);

        $block = ElementBlogOverview::create();
        $this->assertEquals(ElementBlogOverview::config()->get('default_title'), $block->Title);
        $this->assertFalse((bool) $block->ShowPagination);
        $this->assertTrue((bool) $block->ShowWidgets);
    }

    public function testGetBlogPosts(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block1');

        $this->assertNotNull($block->getBlogPosts());
        $this->assertCount(4, $block->getBlogPosts());
    }

    /**
     * This Block is on a non Blog page, and we have not allowed this type of Block to be used on other page types, so,
     * it should just return null
     *
     * @throws ValidationException
     */
    public function testGetBlogPostsDenied(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block4');

        $this->assertNull($block->getBlogPosts());
    }

    /**
     * This Block has been added to a (test only) Page, and our config is going to be set to allow that. We should
     * expect that the custom getBlogPosts() method on the (test only) Page returns an empty DataList
     *
     * @throws ValidationException
     */
    public function testGetBlogPostsCustom(): void
    {
        // Update our config to allow this Block type to be used outside of the Blog
        ElementBlogOverview::config()->update('allow_use_outside_of_blog', true);

        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block4');

        $this->assertNotNull($block->getBlogPosts());
        $this->assertCount(0, $block->getBlogPosts());
    }

    /**
     * This Block has been added to a standard Page, and our config is going to be set to allow that. We should
     * expect that the we are simply returned a DataList of all available BlogPosts in the DB
     *
     * @throws ValidationException
     */
    public function testGetBlogPostsDefault(): void
    {
        // Update our config to allow this Block type to be used outside of the Blog
        ElementBlogOverview::config()->update('allow_use_outside_of_blog', true);

        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block3');

        $this->assertNotNull($block->getBlogPosts());
        $this->assertCount(4, $block->getBlogPosts());
    }

    /**
     * Test that the Block successfully returns the Widgets when it is assigned to a page which is using it's own
     * SideBar and widgets
     *
     * @throws ValidationException
     */
    public function testSideBarView(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block2');
        /** @var Blog|WidgetPageExtension $blog */
        $blog = $this->objFromFixture(Blog::class, 'blog2');

        /** @var WidgetArea $sideBar */
        $sideBar = $blog->SideBarView();

        $this->assertNotNull($sideBar);

        /** @var DataList|Widget[] $widgets */
        $widgets = $sideBar->Widgets();

        $expectedWidgets = $widgets->map('ID', 'Title')->toArray();

        $this->assertNotNull($block->SideBarView());

        $this->assertEquals(
            $expectedWidgets,
            $block->SideBarView()->Widgets()->map('ID', 'Title')->toArray()
        );
    }

    /**
     * Test that the Block successfully returns the Widgets when it is assigned to a page which is inheriting its
     * SideBar from its Parent page
     *
     * @throws ValidationException
     */
    public function testSideBarViewInheriting(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block1');
        /** @var Blog|WidgetPageExtension $blog */
        $blog = $this->objFromFixture(Blog::class, 'blog1');

        /** @var WidgetArea $sideBar */
        $sideBar = $blog->SideBarView();

        $this->assertNotNull($sideBar);

        /** @var DataList|Widget[] $widgets */
        $widgets = $sideBar->Widgets();

        $expectedWidgets = $widgets->map('ID', 'Title')->toArray();

        $this->assertNotNull($block->SideBarView());

        $this->assertEquals(
            $expectedWidgets,
            $block->SideBarView()->Widgets()->map('ID', 'Title')->toArray()
        );
    }

    /**
     * This Block has been added to a standard Page, but our config states that these Block types are not allowed
     * outside of the Blog, so, we should simply get null
     *
     * @throws ValidationException
     */
    public function testSideBarDenied(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block3');

        $this->assertNull($block->SideBarView());
    }

    /**
     * This Block has been added to a (test only) Page, and our config is going to be set to allow that. We should
     * expect that the custom SideBarView() method on the (test only) Page returns a WidgetArea
     *
     * @throws ValidationException
     */
    public function testSideBarCustom(): void
    {
        // Update our config to allow this Block type to be used outside of the Blog
        ElementBlogOverview::config()->update('allow_use_outside_of_blog', true);

        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block4');

        $this->assertNotNull($block->SideBarView());
    }

    /**
     * @throws ValidationException
     */
    public function testSideBarNoParent(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block5');

        $this->assertNull($block->SideBarView());
    }

    /**
     * @throws ValidationException
     */
    public function testCacheKey(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block1');
        /** @var Blog $blog */
        $blog = $this->objFromFixture(Blog::class, 'blog1');

        $expectedCacheKey = implode(
            '-',
            [
                ElementBlogOverview::class,
                $block->ID,
                $block->LastEdited,
                $blog->getBlogPosts()->count(),
                $blog->getBlogPosts()->max('LastEdited')
            ]
        );

        $this->assertEquals($expectedCacheKey, $block->getCacheKey());
    }
}
