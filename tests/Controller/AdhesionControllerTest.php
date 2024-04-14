<?php

namespace App\Test\Controller;

use App\Entity\Adhesion;
use App\Entity\AdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdhesionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AdhesionRepository $repository;
    private string $path = '/adhesion/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Adhesion::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adhesion index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'adhesion[username]' => 'Testing',
            'adhesion[gymname]' => 'Testing',
            'adhesion[typea]' => 'Testing',
            'adhesion[price]' => 'Testing',
            'adhesion[debuta]' => 'Testing',
            'adhesion[fina]' => 'Testing',
            'adhesion[gymid]' => 'Testing',
            'adhesion[userId]' => 'Testing',
        ]);

        self::assertResponseRedirects('/adhesion/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adhesion();
        $fixture->setUsername('My Title');
        $fixture->setGymname('My Title');
        $fixture->setTypea('My Title');
        $fixture->setPrice('My Title');
        $fixture->setDebuta('My Title');
        $fixture->setFina('My Title');
        $fixture->setGymid('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adhesion');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adhesion();
        $fixture->setUsername('My Title');
        $fixture->setGymname('My Title');
        $fixture->setTypea('My Title');
        $fixture->setPrice('My Title');
        $fixture->setDebuta('My Title');
        $fixture->setFina('My Title');
        $fixture->setGymid('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'adhesion[username]' => 'Something New',
            'adhesion[gymname]' => 'Something New',
            'adhesion[typea]' => 'Something New',
            'adhesion[price]' => 'Something New',
            'adhesion[debuta]' => 'Something New',
            'adhesion[fina]' => 'Something New',
            'adhesion[gymid]' => 'Something New',
            'adhesion[userId]' => 'Something New',
        ]);

        self::assertResponseRedirects('/adhesion/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getUsername());
        self::assertSame('Something New', $fixture[0]->getGymname());
        self::assertSame('Something New', $fixture[0]->getTypea());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getDebuta());
        self::assertSame('Something New', $fixture[0]->getFina());
        self::assertSame('Something New', $fixture[0]->getGymid());
        self::assertSame('Something New', $fixture[0]->getUserId());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Adhesion();
        $fixture->setUsername('My Title');
        $fixture->setGymname('My Title');
        $fixture->setTypea('My Title');
        $fixture->setPrice('My Title');
        $fixture->setDebuta('My Title');
        $fixture->setFina('My Title');
        $fixture->setGymid('My Title');
        $fixture->setUserId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/adhesion/');
    }
}
