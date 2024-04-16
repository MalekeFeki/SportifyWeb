<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415221405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Check if the 'adhesion' table exists before attempting to drop it
        if ($schema->hasTable('adhesion')) {
            // Drop foreign key constraint if it exists
            if ($schema->getTable('reclamation')->hasForeignKey('fk_reclamation_adhesion')) {
                $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fk_reclamation_adhesion');
            }
            
            // Drop column if it exists
            if ($schema->getTable('reclamation')->hasColumn('adhesion_id')) {
                $this->addSql('ALTER TABLE reclamation DROP COLUMN adhesion_id');
            }
            
            // Drop 'adhesion' table
            $this->addSql('DROP TABLE adhesion');
        }
        
        // Drop other tables if they exist
        $this->addSql('DROP TABLE IF EXISTS coach_admin, utilisateur');
        
        // Drop foreign key constraint 'reclamation_ibfk_1' if it exists
        if ($schema->getTable('reclamation')->hasForeignKey('reclamation_ibfk_1')) {
            $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        }
        
        // Drop primary key 'id' and column 'idCoach' from 'reclamation' table,
        // and change the 'commentaire' column
        $this->addSql('ALTER TABLE reclamation DROP PRIMARY KEY, DROP id, DROP idCoach, CHANGE commentaire commentaire VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // Recreate 'adhesion' table
        $this->addSql('CREATE TABLE adhesion (
            id_adhesion INT AUTO_INCREMENT NOT NULL,
            id INT DEFAULT NULL,
            date_debut DATE DEFAULT NULL,
            date_fin DATE DEFAULT NULL,
            etat VARCHAR(255) DEFAULT NULL,
            INDEX id (id),
            PRIMARY KEY(id_adhesion)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        
        // Recreate other tables
        $this->addSql('CREATE TABLE coach_admin (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            prenom VARCHAR(255) NOT NULL,
            description VARCHAR(1000) DEFAULT NULL,
            sexe VARCHAR(10) DEFAULT NULL,
            photo VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        
        $this->addSql('CREATE TABLE utilisateur (
            id INT AUTO_INCREMENT NOT NULL,
            cin VARCHAR(20) NOT NULL,
            num_tel VARCHAR(15) NOT NULL,
            nom VARCHAR(255) NOT NULL,
            prenom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            mdp VARCHAR(255) NOT NULL,
            role VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        
        // Recreate foreign key constraint 'adhesion_ibfk_1'
        $this->addSql('ALTER TABLE adhesion ADD CONSTRAINT adhesion_ibfk_1 FOREIGN KEY (id) REFERENCES utilisateur (id)');
        
        // Recreate foreign key constraint 'reclamation_ibfk_1' for 'reclamation' table
        $this->addSql('ALTER TABLE reclamation ADD id INT DEFAULT NULL, ADD idCoach INT DEFAULT NULL, CHANGE commentaire commentaire VARCHAR(1000) DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (id) REFERENCES utilisateur (id)');
        
        // Recreate index 'IDX_CE606404BF396750' on 'reclamation' table
        $this->addSql('CREATE INDEX IDX_CE606404BF396750 ON reclamation (id)');
    }
}
