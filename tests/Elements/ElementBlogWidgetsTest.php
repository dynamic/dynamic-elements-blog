<?php

namespace Dynamic\Elements\Blog\Tests\Elements;

use Dynamic\Elements\Blog\Elements\ElementBlogWidgets;
use SilverStripe\Dev\SapphireTest;

/**
 * Class ElementBlogPaginationTest
 *
 * @package Dynamic\Elements\Blog\Elements\Tests
 */
class ElementBlogWidgetsTest extends SapphireTest
{
    /**
     * @var bool
     */
    protected $usesDatabase = true;

    public function testPopulateDefaults(): void
    {
        $block = ElementBlogWidgets::create();

        $this->assertFalse((bool) $block->ShowTitle);
        $this->assertFalse((bool) $block->ShowPagination);
        $this->assertTrue((bool) $block->ShowWidgets);
    }
}
