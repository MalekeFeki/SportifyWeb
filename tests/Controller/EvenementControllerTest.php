<?php

namespace App\Test\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EvenementRepository $repository;
    private string $path = '/evenement/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Evenement::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement index');

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
            'evenement[nomev]' => 'Testing',
            'evenement[dateddebutev]' => 'Testing',
            'evenement[datedfinev]' => 'Testing',
            'evenement[heureev]' => 'Testing',
            'evenement[descrptionev]' => 'Testing',
            'evenement[photo]' => 'Testing',
            'evenement[lieu]' => 'Testing',
            'evenement[city]' => 'Testing',
            'evenement[tele]' => 'Testing',
            'evenement[email]' => 'Testing',
            'evenement[fbLink]' => 'Testing',
            'evenement[igLink]' => 'Testing',
            'evenement[genreevenement]' => 'Testing',
            'evenement[typeev]' => 'Testing',
            'evenement[nombrepersonneinteresse]' => 'Testing',
            'evenement[capacite]' => 'Testing',
            'evenement[lat]' => 'Testing',
            'evenement[lon]' => 'Testing',
        ]);

        self::assertResponseRedirects('/evenement/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNomev('My Title');
        $fixture->setDateddebutev('My Title');
        $fixture->setDatedfinev('My Title');
        $fixture->setHeureev('My Title');
        $fixture->setDescrptionev('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setLieu('My Title');
        $fixture->setCity('My Title');
        $fixture->setTele('My Title');
        $fixture->setEmail('My Title');
        $fixture->setFbLink('My Title');
        $fixture->setIgLink('My Title');
        $fixture->setGenreevenement('My Title');
        $fixture->setTypeev('My Title');
        $fixture->setNombrepersonneinteresse('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setLat('My Title');
        $fixture->setLon('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNomev('My Title');
        $fixture->setDateddebutev('My Title');
        $fixture->setDatedfinev('My Title');
        $fixture->setHeureev('My Title');
        $fixture->setDescrptionev('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setLieu('My Title');
        $fixture->setCity('My Title');
        $fixture->setTele('My Title');
        $fixture->setEmail('My Title');
        $fixture->setFbLink('My Title');
        $fixture->setIgLink('My Title');
        $fixture->setGenreevenement('My Title');
        $fixture->setTypeev('My Title');
        $fixture->setNombrepersonneinteresse('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setLat('My Title');
        $fixture->setLon('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'evenement[nomev]' => 'Something New',
            'evenement[dateddebutev]' => 'Something New',
            'evenement[datedfinev]' => 'Something New',
            'evenement[heureev]' => 'Something New',
            'evenement[descrptionev]' => 'Something New',
            'evenement[photo]' => 'Something New',
            'evenement[lieu]' => 'Something New',
            'evenement[city]' => 'Something New',
            'evenement[tele]' => 'Something New',
            'evenement[email]' => 'Something New',
            'evenement[fbLink]' => 'Something New',
            'evenement[igLink]' => 'Something New',
            'evenement[genreevenement]' => 'Something New',
            'evenement[typeev]' => 'Something New',
            'evenement[nombrepersonneinteresse]' => 'Something New',
            'evenement[capacite]' => 'Something New',
            'evenement[lat]' => 'Something New',
            'evenement[lon]' => 'Something New',
        ]);

        self::assertResponseRedirects('/evenement/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomev());
        self::assertSame('Something New', $fixture[0]->getDateddebutev());
        self::assertSame('Something New', $fixture[0]->getDatedfinev());
        self::assertSame('Something New', $fixture[0]->getHeureev());
        self::assertSame('Something New', $fixture[0]->getDescrptionev());
        self::assertSame('Something New', $fixture[0]->getPhoto());
        self::assertSame('Something New', $fixture[0]->getLieu());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getTele());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getFbLink());
        self::assertSame('Something New', $fixture[0]->getIgLink());
        self::assertSame('Something New', $fixture[0]->getGenreevenement());
        self::assertSame('Something New', $fixture[0]->getTypeev());
        self::assertSame('Something New', $fixture[0]->getNombrepersonneinteresse());
        self::assertSame('Something New', $fixture[0]->getCapacite());
        self::assertSame('Something New', $fixture[0]->getLat());
        self::assertSame('Something New', $fixture[0]->getLon());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Evenement();
        $fixture->setNomev('My Title');
        $fixture->setDateddebutev('My Title');
        $fixture->setDatedfinev('My Title');
        $fixture->setHeureev('My Title');
        $fixture->setDescrptionev('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setLieu('My Title');
        $fixture->setCity('My Title');
        $fixture->setTele('My Title');
        $fixture->setEmail('My Title');
        $fixture->setFbLink('My Title');
        $fixture->setIgLink('My Title');
        $fixture->setGenreevenement('My Title');
        $fixture->setTypeev('My Title');
        $fixture->setNombrepersonneinteresse('My Title');
        $fixture->setCapacite('My Title');
        $fixture->setLat('My Title');
        $fixture->setLon('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/evenement/');
    }
}
