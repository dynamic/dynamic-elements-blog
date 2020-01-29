<?php

namespace Dynamic\Elements\Blog\Tests\Fake;

use Page as BasePage;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataList;
use SilverStripe\Widgets\Model\WidgetArea;

/**
 * Test only class used to test that the Overview block can be used outside of a Blog page
 *
 * Class Page
 *
 * @package Dynamic\Elements\Blog\Tests\Fake
 */
class Page extends BasePage implements TestOnly
{
    /**
     * @return WidgetArea
     */
    public function SideBarView(): WidgetArea
    {
        return WidgetArea::create();
    }

    /**
     * @return DataList
     */
    public function getBlogPosts(): DataList
    {
        // This is just a workaround way of returning an empty DataList of the correct type
        return BlogPost::get()->byIDs([0]);
    }
}
