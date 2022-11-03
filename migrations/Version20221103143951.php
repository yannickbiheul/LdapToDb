<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103143951 extends AbstractMigration
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
        $this->addSql('CREATE SEQUENCE number_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE people_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE personne_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pole_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE batiment (id INT NOT NULL, hopital_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5FAB00C6C6E55B5 ON batiment (nom)');
        $this->addSql('CREATE INDEX IDX_F5FAB00CCC0FBF92 ON batiment (hopital_id)');
        $this->addSql('CREATE TABLE hopital (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8718F2C6C6E55B5 ON hopital (nom)');
        $this->addSql('CREATE TABLE metier (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE number_record (id INT NOT NULL, phone_number VARCHAR(255) NOT NULL, did_number VARCHAR(255) DEFAULT NULL, private VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE people_record (id INT NOT NULL, sn VARCHAR(255) NOT NULL, display_gn VARCHAR(255) DEFAULT NULL, main_line_number VARCHAR(255) DEFAULT NULL, did_numbers VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, hierarchy_sv VARCHAR(255) NOT NULL, attr1 VARCHAR(255) DEFAULT NULL, attr5 VARCHAR(255) DEFAULT NULL, attr6 VARCHAR(255) DEFAULT NULL, attr7 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE personne (id INT NOT NULL, metier_id INT DEFAULT NULL, hopital_id INT DEFAULT NULL, pole_id INT DEFAULT NULL, batiment_id INT DEFAULT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone_court VARCHAR(255) NOT NULL, telephone_long VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FCEC9EFED16FA20 ON personne (metier_id)');
        $this->addSql('CREATE INDEX IDX_FCEC9EFCC0FBF92 ON personne (hopital_id)');
        $this->addSql('CREATE INDEX IDX_FCEC9EF419C3385 ON personne (pole_id)');
        $this->addSql('CREATE INDEX IDX_FCEC9EFD6F6891B ON personne (batiment_id)');
        $this->addSql('CREATE TABLE pole (id INT NOT NULL, batiment_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FD6042E16C6E55B5 ON pole (nom)');
        $this->addSql('CREATE INDEX IDX_FD6042E1D6F6891B ON pole (batiment_id)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, pole_id INT DEFAULT NULL, batiment_id INT DEFAULT NULL, hopital_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone_court VARCHAR(255) DEFAULT NULL, telephone_long VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E19D9AD2419C3385 ON service (pole_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2D6F6891B ON service (batiment_id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD2CC0FBF92 ON service (hopital_id)');
        $this->addSql('ALTER TABLE batiment ADD CONSTRAINT FK_F5FAB00CCC0FBF92 FOREIGN KEY (hopital_id) REFERENCES hopital (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFED16FA20 FOREIGN KEY (metier_id) REFERENCES metier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFCC0FBF92 FOREIGN KEY (hopital_id) REFERENCES hopital (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EF419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFD6F6891B FOREIGN KEY (batiment_id) REFERENCES batiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pole ADD CONSTRAINT FK_FD6042E1D6F6891B FOREIGN KEY (batiment_id) REFERENCES batiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2419C3385 FOREIGN KEY (pole_id) REFERENCES pole (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2D6F6891B FOREIGN KEY (batiment_id) REFERENCES batiment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2CC0FBF92 FOREIGN KEY (hopital_id) REFERENCES hopital (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE batiment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hopital_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE metier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE number_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE people_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE personne_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pole_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('ALTER TABLE batiment DROP CONSTRAINT FK_F5FAB00CCC0FBF92');
        $this->addSql('ALTER TABLE personne DROP CONSTRAINT FK_FCEC9EFED16FA20');
        $this->addSql('ALTER TABLE personne DROP CONSTRAINT FK_FCEC9EFCC0FBF92');
        $this->addSql('ALTER TABLE personne DROP CONSTRAINT FK_FCEC9EF419C3385');
        $this->addSql('ALTER TABLE personne DROP CONSTRAINT FK_FCEC9EFD6F6891B');
        $this->addSql('ALTER TABLE pole DROP CONSTRAINT FK_FD6042E1D6F6891B');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2419C3385');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2D6F6891B');
        $this->addSql('ALTER TABLE service DROP CONSTRAINT FK_E19D9AD2CC0FBF92');
        $this->addSql('DROP TABLE batiment');
        $this->addSql('DROP TABLE hopital');
        $this->addSql('DROP TABLE metier');
        $this->addSql('DROP TABLE number_record');
        $this->addSql('DROP TABLE people_record');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE pole');
        $this->addSql('DROP TABLE service');
    }
}
