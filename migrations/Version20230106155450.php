<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106155450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE astreinte_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE number_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE people_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE personne_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE search_data_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE astreinte (id INT NOT NULL, service VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, sur_place VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE contact_record (id INT NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, private VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE number_record (id INT NOT NULL, phone_number VARCHAR(255) NOT NULL, did_number VARCHAR(255) DEFAULT NULL, private VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE people_record (id INT NOT NULL, sn VARCHAR(255) NOT NULL, display_gn VARCHAR(255) DEFAULT NULL, main_line_number VARCHAR(255) DEFAULT NULL, did_numbers VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, hierarchy_sv VARCHAR(255) NOT NULL, attr1 VARCHAR(255) DEFAULT NULL, attr5 VARCHAR(255) DEFAULT NULL, attr6 VARCHAR(255) DEFAULT NULL, attr7 VARCHAR(255) DEFAULT NULL, cle_uid VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE personne (id INT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, tel_court VARCHAR(255) DEFAULT NULL, metier VARCHAR(255) DEFAULT NULL, hopital VARCHAR(255) DEFAULT NULL, pole VARCHAR(255) DEFAULT NULL, batiment VARCHAR(255) DEFAULT NULL, tel_long VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE search_data (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE astreinte_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE number_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE people_record_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE personne_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE search_data_id_seq CASCADE');
        $this->addSql('DROP TABLE astreinte');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_record');
        $this->addSql('DROP TABLE number_record');
        $this->addSql('DROP TABLE people_record');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE search_data');
    }
}
