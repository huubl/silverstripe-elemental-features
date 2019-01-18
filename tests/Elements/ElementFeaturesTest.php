<?php

namespace Dynamic\Elements\Features\Tests;

use Dynamic\Elements\Features\Elements\ElementFeatures;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataList;

class ElementFeaturesTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     *
     */
    public function testGetCMSFields()
    {
        $object = $this->objFromFixture(ElementFeatures::class, 'one');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }

    /**
     *
     */
    public function testGetFeaturesList()
    {
        $object = $this->objFromFixture(ElementFeatures::class, 'one');
        $this->assertInstanceOf(DataList::class, $object->getFeaturesList());
        $this->assertEquals($object->getFeaturesList(), $object->Features()->sort('Sort'));
    }

    /**
     *
     */
    public function testGetSummary()
    {
        $object = $this->objFromFixture(ElementFeatures::class, 'one');
        $result = $object->Features()->count() . ' feature';
        $this->assertEquals($object->getSummary(), $result);
    }

    /**
     *
     */
    public function testGetType()
    {
        $object = $this->objFromFixture(ElementFeatures::class, 'one');
        $this->assertEquals($object->getType(), 'Features');
    }
}
