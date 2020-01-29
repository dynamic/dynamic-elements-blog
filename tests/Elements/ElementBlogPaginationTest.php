<?php

namespace Dynamic\Elements\Blog\Tests\Elements;

use Dynamic\Elements\Blog\Elements\ElementBlogPagination;
use SilverStripe\Dev\SapphireTest;

/**
 * Class ElementBlogPaginationTest
 *
 * @package Dynamic\Elements\Blog\Elements\Tests
 */
class ElementBlogPaginationTest extends SapphireTest
{
    /**
     * @var bool
     */
    protected $usesDatabase = true;

    public function testPopulateDefaults(): void
    {
        $block = ElementBlogPagination::create();

        $this->assertFalse((bool) $block->ShowTitle);
        $this->assertTrue((bool) $block->ShowPagination);
        $this->assertFalse((bool) $block->ShowWidgets);
    }
}
