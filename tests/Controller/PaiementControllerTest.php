<?php

namespace App\Test\Controller;

use App\Entity\Paiement;
use App\Entity\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaiementControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PaiementRepository $repository;
    private string $path = '/paiement/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Paiement::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Paiement index');

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
            'paiement[username]' => 'Testing',
            'paiement[email]' => 'Testing',
            'paiement[country]' => 'Testing',
            'paiement[ncb]' => 'Testing',
            'paiement[cvc]' => 'Testing',
            'paiement[expdate]' => 'Testing',
            'paiement[postalcode]' => 'Testing',
            'paiement[promocode]' => 'Testing',
            'paiement[price]' => 'Testing',
            'paiement[pdate]' => 'Testing',
            'paiement[id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/paiement/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paiement();
        $fixture->setUsername('My Title');
        $fixture->setEmail('My Title');
        $fixture->setCountry('My Title');
        $fixture->setNcb('My Title');
        $fixture->setCvc('My Title');
        $fixture->setExpdate('My Title');
        $fixture->setPostalcode('My Title');
        $fixture->setPromocode('My Title');
        $fixture->setPrice('My Title');
        $fixture->setPdate('My Title');
        $fixture->setId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Paiement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Paiement();
        $fixture->setUsername('My Title');
        $fixture->setEmail('My Title');
        $fixture->setCountry('My Title');
        $fixture->setNcb('My Title');
        $fixture->setCvc('My Title');
        $fixture->setExpdate('My Title');
        $fixture->setPostalcode('My Title');
        $fixture->setPromocode('My Title');
        $fixture->setPrice('My Title');
        $fixture->setPdate('My Title');
        $fixture->setId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'paiement[username]' => 'Something New',
            'paiement[email]' => 'Something New',
            'paiement[country]' => 'Something New',
            'paiement[ncb]' => 'Something New',
            'paiement[cvc]' => 'Something New',
            'paiement[expdate]' => 'Something New',
            'paiement[postalcode]' => 'Something New',
            'paiement[promocode]' => 'Something New',
            'paiement[price]' => 'Something New',
            'paiement[pdate]' => 'Something New',
            'paiement[id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/paiement/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getUsername());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getCountry());
        self::assertSame('Something New', $fixture[0]->getNcb());
        self::assertSame('Something New', $fixture[0]->getCvc());
        self::assertSame('Something New', $fixture[0]->getExpdate());
        self::assertSame('Something New', $fixture[0]->getPostalcode());
        self::assertSame('Something New', $fixture[0]->getPromocode());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getPdate());
        self::assertSame('Something New', $fixture[0]->getId());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Paiement();
        $fixture->setUsername('My Title');
        $fixture->setEmail('My Title');
        $fixture->setCountry('My Title');
        $fixture->setNcb('My Title');
        $fixture->setCvc('My Title');
        $fixture->setExpdate('My Title');
        $fixture->setPostalcode('My Title');
        $fixture->setPromocode('My Title');
        $fixture->setPrice('My Title');
        $fixture->setPdate('My Title');
        $fixture->setId('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/paiement/');
    }
}
