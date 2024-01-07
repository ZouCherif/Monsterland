<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208072208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monstre (id INT AUTO_INCREMENT NOT NULL, royaume_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, puissance SMALLINT NOT NULL, taille INT NOT NULL, UNIQUE INDEX UNIQ_A20EC7A56C6E55B5 (nom), INDEX IDX_A20EC7A5A3878AD1 (royaume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE royaume (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D7D09BAD6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monstre ADD CONSTRAINT FK_A20EC7A5A3878AD1 FOREIGN KEY (royaume_id) REFERENCES royaume (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monstre DROP FOREIGN KEY FK_A20EC7A5A3878AD1');
        $this->addSql('DROP TABLE monstre');
        $this->addSql('DROP TABLE royaume');
    }
}
