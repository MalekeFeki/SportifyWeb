<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417100511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY fk_ids');
        $this->addSql('DROP TABLE seance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seance (idS INT NOT NULL, idSeance INT AUTO_INCREMENT NOT NULL, nomseance VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, debut TIME NOT NULL, fin TIME NOT NULL, dates DATE NOT NULL, INDEX fk_ids (idS), PRIMARY KEY(idSeance)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT fk_ids FOREIGN KEY (ids) REFERENCES salle (idS) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
