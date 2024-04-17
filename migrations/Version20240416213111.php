<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416213111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE seance DROP FOREIGN KEY fk_ids');
        // $this->addSql('DROP INDEX fk_ids ON seance');
        // $this->addSql('ALTER TABLE seance DROP ids, DROP nomseance, DROP debut, DROP fin, DROP dates');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE seance ADD ids INT NOT NULL, ADD nomseance VARCHAR(50) NOT NULL, ADD debut TIME NOT NULL, ADD fin TIME NOT NULL, ADD dates DATE NOT NULL');
        // $this->addSql('ALTER TABLE seance ADD CONSTRAINT fk_ids FOREIGN KEY (ids) REFERENCES salle (idS) ON UPDATE CASCADE ON DELETE CASCADE');
        // $this->addSql('CREATE INDEX fk_ids ON seance (ids)');
    }
}
