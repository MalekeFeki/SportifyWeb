<?php

namespace App\Test\Controller;

use App\Entity\Salle;
use App\Entity\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SalleControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SalleRepository $repository;
    private string $path = '/salle/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Salle::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Salle index');

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
            'salle[noms]' => 'Testing',
            'salle[addressed]' => 'Testing',
            'salle[region]' => 'Testing',
            'salle[options]' => 'Testing',
        ]);

        self::assertResponseRedirects('/salle/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salle();
        $fixture->setNoms('My Title');
        $fixture->setAddressed('My Title');
        $fixture->setRegion('My Title');
        $fixture->setOptions('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Salle');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Salle();
        $fixture->setNoms('My Title');
        $fixture->setAddressed('My Title');
        $fixture->setRegion('My Title');
        $fixture->setOptions('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'salle[noms]' => 'Something New',
            'salle[addressed]' => 'Something New',
            'salle[region]' => 'Something New',
            'salle[options]' => 'Something New',
        ]);

        self::assertResponseRedirects('/salle/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNoms());
        self::assertSame('Something New', $fixture[0]->getAddressed());
        self::assertSame('Something New', $fixture[0]->getRegion());
        self::assertSame('Something New', $fixture[0]->getOptions());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Salle();
        $fixture->setNoms('My Title');
        $fixture->setAddressed('My Title');
        $fixture->setRegion('My Title');
        $fixture->setOptions('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/salle/');
    }
}
