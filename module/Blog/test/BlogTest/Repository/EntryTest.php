<?php

namespace BlogTest\Repository;

use Blog\Entity\Entry;
use BlogTest\AbstractTestCase;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Blog\Repository\Entry as EntryRepository;

class EntryTest extends AbstractTestCase
{
    /** @var EntryRepository */
    private $repository;
    /** @var DocumentManager */
    private $documentManager;

    public function setUp()
    {
        parent::setUp();
        $this->documentManager = $this->getApplication()->getServiceManager()->get('blog_odm');
        $this->repository = $this->documentManager->getRepository('Blog\Entity\Entry');
    }

    public function testFindAllReturnsCursor()
    {
        $this->assertInstanceOf('Doctrine\ODM\MongoDB\Cursor', $this->repository->findAll());
    }

    public function testPersistRemove()
    {
        $this->assertEquals(0, count($this->repository->findAll()), 'collections should be empty before ' . __FUNCTION__ . ' test');

        $entry = new Entry();

        $this->documentManager->persist($entry);
        $this->documentManager->flush($entry);
        $this->documentManager->refresh($entry);

        $this->assertEquals(1, count($this->repository->findAll()));

        $this->documentManager->remove($entry);
        $this->documentManager->flush();

        $this->assertEquals(0, count($this->repository->findAll()));
    }

    public function testFindById()
    {
        $entry = new Entry();

        $this->documentManager->persist($entry);
        $this->documentManager->flush($entry);
        $this->documentManager->refresh($entry);

        $this->assertSame($entry, $this->repository->find($entry->getId()));

        $this->documentManager->remove($entry);
        $this->documentManager->flush();
    }

    public function testGetRecent()
    {
        $oldEntry = new Entry();
        $oldEntry->setCreated(new DateTime('2014-01-21'));
        $oldEntry->setUpdated(new DateTime('2014-01-21'));

        $newEntry = new Entry();
        $newEntry->setCreated(new DateTime('2014-01-22'));
        $newEntry->setUpdated(new DateTime('2014-01-22'));

        $this->documentManager->persist($oldEntry);
        $this->documentManager->persist($newEntry);
        $this->documentManager->flush();
        $this->documentManager->refresh($oldEntry);
        $this->documentManager->refresh($newEntry);

        $this->assertSame(2, count($this->repository->getRecent(false)));
        $this->assertSame($newEntry, $this->repository->getRecent(false)->getNext());

        $this->documentManager->remove($oldEntry);
        $this->documentManager->remove($newEntry);
        $this->documentManager->flush();
    }

    public function testGetRecentWithPublishedEntriesOnly()
    {
        $publishedEntry = new Entry();
        $publishedEntry->setPublished(true);

        $unpublishedEntry = new Entry();
        $unpublishedEntry->setPublished(false);

        $this->documentManager->persist($publishedEntry);
        $this->documentManager->persist($unpublishedEntry);
        $this->documentManager->flush();
        $this->documentManager->refresh($publishedEntry);
        $this->documentManager->refresh($unpublishedEntry);

        $this->assertSame(1, $this->repository->getRecent()->count());
        $this->assertEquals($publishedEntry, $this->repository->getRecent()->getNext());

        $this->documentManager->remove($publishedEntry);
        $this->documentManager->remove($unpublishedEntry);
        $this->documentManager->flush();
    }
}