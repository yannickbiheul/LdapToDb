<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027115653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE batiment ADD hopital_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE batiment ADD CONSTRAINT FK_F5FAB00CCC0FBF92 FOREIGN KEY (hopital_id) REFERENCES hopital (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5FAB00CCC0FBF92 ON batiment (hopital_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE batiment DROP CONSTRAINT FK_F5FAB00CCC0FBF92');
        $this->addSql('DROP INDEX IDX_F5FAB00CCC0FBF92');
        $this->addSql('ALTER TABLE batiment DROP hopital_id');
    }
}
