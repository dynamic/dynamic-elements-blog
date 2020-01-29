<?php

namespace Dynamic\Elements\Blog\Tests\Fake;

use PageController as BasePageController;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;

/**
 * Test only class used to test that the Overview block can be used outside of a Blog page
 *
 * Class Page
 *
 * @package Dynamic\Elements\Blog\Tests\Fake
 */
class PageController extends BasePageController implements TestOnly
{
    /**
     * @return PaginatedList
     */
    public function PaginatedList(): PaginatedList
    {
        return PaginatedList::create(ArrayList::create());
    }
}
