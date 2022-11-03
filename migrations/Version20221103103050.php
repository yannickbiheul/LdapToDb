<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103103050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE entree_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE people_record_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE people_record (id INT NOT NULL, sn VARCHAR(255) NOT NULL, display_gn VARCHAR(255) DEFAULT NULL, main_line_number VARCHAR(255) DEFAULT NULL, did_numbers VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, hierarchy_sv VARCHAR(255) NOT NULL, attr1 VARCHAR(255) DEFAULT NULL, attr5 VARCHAR(255) DEFAULT NULL, attr6 VARCHAR(255) DEFAULT NULL, attr7 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE entree');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE people_record_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE entree_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE entree (id INT NOT NULL, sn VARCHAR(255) NOT NULL, display_gn VARCHAR(255) DEFAULT NULL, main_line_number VARCHAR(255) DEFAULT NULL, did_numbers VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, hierarchy_sv VARCHAR(255) NOT NULL, attr1 VARCHAR(255) DEFAULT NULL, attr5 VARCHAR(255) DEFAULT NULL, attr6 VARCHAR(255) DEFAULT NULL, attr7 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE people_record');
    }
}
