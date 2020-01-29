<?php

namespace Dynamic\Elements\Blog\Tests\Elements;

use DNADesign\Elemental\Extensions\ElementalPageExtension;
use Dynamic\Elements\Blog\Elements\ElementBlogOverview;
use Dynamic\Elements\Blog\Tests\Fake\PageController;
use Page;
use PageController as BasePageController;
use SilverStripe\Blog\Model\BlogController;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Widgets\Extensions\WidgetPageExtension;

/**
 * Class ElementBlogOverviewFunctionalTest
 *
 * @package Dynamic\Elements\Blog\Tests\Elements
 */
class ElementBlogOverviewFunctionalTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = 'ElementBlogOverviewFunctionalTest.yml';

    /**
     * @var bool
     */
    protected static $use_draft_site = true;

    /**
     * @var array
     */
    protected static $required_extensions = [
        Page::class => [
            WidgetPageExtension::class,
            ElementalPageExtension::class,
        ],
    ];

    /**
     * @throws ValidationException
     */
    public function testPaginatedListAvailable(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block1');
        $controller = new BlogController();

        // Pass the controller through for the first get
        $this->assertNotNull($block->getPaginatedList($controller));
        // Our cache should now be set, so we shouldn't need the controller this time
        $this->assertNotNull($block->getPaginatedList());
    }

    /**
     * @throws ValidationException
     */
    public function testPaginatedListDenied(): void
    {
        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block2');
        $controller = new PageController();

        // We have not allowed usage outside of BlogController, so this should be null
        $this->assertNull($block->getPaginatedList($controller));
    }

    /**
     * @throws ValidationException
     */
    public function testPaginatedListCustom(): void
    {
        // Update our config to allow this Block type to be used outside of the Blog
        ElementBlogOverview::config()->update('allow_use_outside_of_blog', true);

        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block2');
        $controller = new PageController();

        // We have not allowed usage outside of BlogController, so this should be null
        $this->assertNotNull($block->getPaginatedList($controller));
    }

    /**
     * @throws ValidationException
     */
    public function testPaginatedListDefault(): void
    {
        // Update our config to allow this Block type to be used outside of the Blog
        ElementBlogOverview::config()->update('allow_use_outside_of_blog', true);

        /** @var ElementBlogOverview $block */
        $block = $this->objFromFixture(ElementBlogOverview::class, 'block2');
        $controller = new BasePageController();

        // We have not allowed usage outside of BlogController, so this should be null
        $this->assertNotNull($block->getPaginatedList($controller));
    }
}
