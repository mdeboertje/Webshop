<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200319235825 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE factuur (id INT AUTO_INCREMENT NOT NULL, factuur_datum DATETIME NOT NULL, verval_datum DATETIME NOT NULL, in_zake_opdracht TINYINT(1) NOT NULL, korting INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factuurregel (id INT AUTO_INCREMENT NOT NULL, factuur_nummer_id INT NOT NULL, product_code_id INT NOT NULL, product_aantal INT NOT NULL, INDEX IDX_38897CCDF80A6986 (factuur_nummer_id), INDEX IDX_38897CCDF603E5A7 (product_code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE klant (id INT AUTO_INCREMENT NOT NULL, factuur_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, naam VARCHAR(255) NOT NULL, straat VARCHAR(255) NOT NULL, postcode VARCHAR(7) NOT NULL, plaats VARCHAR(255) NOT NULL, btw_nummer VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_BC33ABE1E7927C74 (email), INDEX IDX_BC33ABE1C35D3E (factuur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, omschrijving VARCHAR(255) NOT NULL, btw INT NOT NULL, prijs DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factuurregel ADD CONSTRAINT FK_38897CCDF80A6986 FOREIGN KEY (factuur_nummer_id) REFERENCES factuur (id)');
        $this->addSql('ALTER TABLE factuurregel ADD CONSTRAINT FK_38897CCDF603E5A7 FOREIGN KEY (product_code_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE klant ADD CONSTRAINT FK_BC33ABE1C35D3E FOREIGN KEY (factuur_id) REFERENCES factuur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE factuurregel DROP FOREIGN KEY FK_38897CCDF80A6986');
        $this->addSql('ALTER TABLE klant DROP FOREIGN KEY FK_BC33ABE1C35D3E');
        $this->addSql('ALTER TABLE factuurregel DROP FOREIGN KEY FK_38897CCDF603E5A7');
        $this->addSql('DROP TABLE factuur');
        $this->addSql('DROP TABLE factuurregel');
        $this->addSql('DROP TABLE klant');
        $this->addSql('DROP TABLE product');
    }
}
