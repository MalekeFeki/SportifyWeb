<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417102536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, cin VARCHAR(20) NOT NULL, num_tel VARCHAR(15) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE seance DROP FOREIGN KEY fk_id_salle');
        // $this->addSql('DROP TABLE seance');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('CREATE TABLE seance (idSeance INT AUTO_INCREMENT NOT NULL, nomseance VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, debut TIME NOT NULL, fin TIME NOT NULL, dates DATE NOT NULL, idS INT NOT NULL, INDEX fk_id_salle (idS), PRIMARY KEY(idSeance)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        // $this->addSql('ALTER TABLE seance ADD CONSTRAINT fk_id_salle FOREIGN KEY (idS) REFERENCES salle (idS) ON UPDATE CASCADE ON DELETE CASCADE');
        // $this->addSql('DROP TABLE utilisateur');
    }
}
