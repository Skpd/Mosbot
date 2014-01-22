<?php

namespace BlogTest\Entity;

use Blog\Entity\Entry;

class EntryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Entry */
    protected $entry;

    public function setUp()
    {
        $this->entry = new Entry;
    }

    public function testEntryConstructor()
    {
        $this->assertInstanceOf('Blog\Entity\Entry', $this->entry);
    }

    public function testId()
    {
        $this->entry->setId(123);
        $this->assertEquals(123, $this->entry->getId());
    }

    public function testHeader()
    {
        $this->entry->setHeader('This is an example blog entry.');
        $this->assertEquals('This is an example blog entry.', $this->entry->getHeader());
    }

    public function testBody()
    {
        $this->entry->setBody('<b>Lorem ipsum dolor <br />sit amet.</b>');
        $this->assertEquals('<b>Lorem ipsum dolor <br />sit amet.</b>', $this->entry->getBody());
    }

    public function testCreated()
    {
        $date = new \DateTime('15 Dec 1988 11:22:33');
        $this->entry->setCreated($date);
        $this->assertEquals($date, $this->entry->getCreated());
    }

    public function testUpdated()
    {
        $date = new \DateTime('15 Dec 1988 11:22:33');
        $this->entry->setUpdated($date);
        $this->assertEquals($date, $this->entry->getUpdated());
    }

    public function testPublished()
    {
        $this->entry->setPublished(true);
        $this->assertEquals(true, $this->entry->isPublished());
    }

    public function testDefaultPublishedShouldBeFalse()
    {
        $entry = new Entry;

        $this->assertFalse($entry->getPublished());
    }
}