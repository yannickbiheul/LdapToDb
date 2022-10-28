<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028143907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE batiment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hopital_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE metier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE personne_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pole_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE batiment (id INT NOT NULL, hopital_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5FAB00C6C6E55B5 ON batiment (nom)');
        $this->addSql('CREATE INDEX IDX_F5FAB00CCC0FBF92 ON batiment (hopital_id)');
        $this->addSql('CREATE TABLE hopital (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8718F2C6C6E55B5 ON hopital (nom)');
        $this->addSql('CREATE TABLE metier (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE personne (id INT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, telephone_court VARCHAR(255) NOT NULL, telephone_long VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pole (id INT NOT NULL, batiment_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD6042E16C6E55B5 ON pole (nom)');
        $this->addSql('CREATE INDEX IDX_FD6042E1D6F6891B ON pole (batiment_id)');
        $this->addSql('ALTER TABLE batiment ADD CONSTRAINT FK_F5FAB00CCC0FBF92 FOREIGN KEY (hopital_id) REFERENCES hopital (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pole ADD CONSTRAINT FK_FD6042E1D6F6891B FOREIGN KEY (batiment_id) REFERENCES batiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE batiment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hopital_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE metier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE personne_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pole_id_seq CASCADE');
        $this->addSql('ALTER TABLE batiment DROP CONSTRAINT FK_F5FAB00CCC0FBF92');
        $this->addSql('ALTER TABLE pole DROP CONSTRAINT FK_FD6042E1D6F6891B');
        $this->addSql('DROP TABLE batiment');
        $this->addSql('DROP TABLE hopital');
        $this->addSql('DROP TABLE metier');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE pole');
    }
}
