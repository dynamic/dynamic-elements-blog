<?php

namespace Dynamic\Elements\Blog\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Blog\Model\Blog;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\ValidationResult;

/**
 * Class ElementBlogPosts
 * @package Dynamic\Elements\Elements
 *
 * @property int $Limit
 * @property string $Content
 *
 * @property int $BlogID
 * @property Blog Blog()
 */
class ElementBlogPosts extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'vendor/dnadesign/silverstripe-elemental/images/base.svg';

    /**
     * @var string
     */
    private static $singular_name = 'Blog Posts Element';

    /**
     * @var string
     */
    private static $plural_name = 'Blog Posts Elements';

    /**
     * @var string
     */
    private static $table_name = 'ElementBlogPosts';

    /**
     * @var array
     */
    private static $db = array(
        'Limit' => 'Int',
        'Content' => 'HTMLText',
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'Blog' => Blog::class,
    );

    /**
     * @var array
     */
    private static $defaults = array(
        'Limit' => 3,
    );

    /**
     * @return DBHTMLText
     */
    public function ElementSummary()
    {
        return DBField::create_field('HTMLText', $this->Content)->Summary(20);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Blog Posts');
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->dataFieldByName('Content')
                ->setRows(8);

            $fields->dataFieldByName('Limit')
                ->setTitle('Articles to show');

            if (class_exists(Blog::class)) {
                $fields->insertBefore(
                    $fields->dataFieldByName('BlogID')
                        ->setTitle('Featured Blog'),
                    'Limit'
                );
            }
        });

        return parent::getCMSFields();
    }

    /**
     * @return ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();
        if (!$this->BlogID) {
            $result->addError('Featured Blog is required before you can save');
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getPostsList()
    {
        if ($this->BlogID) {
            return Blog::get()->byID($this->BlogID)->getBlogPosts()->Limit($this->Limit);
        }
        return null;
    }
}
