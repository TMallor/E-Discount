<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220145224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, name, description, price, publication_date, author_id, image FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date VARCHAR(255) NOT NULL, author_id INTEGER NOT NULL, image_url VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO article (id, name, description, price, publication_date, author_id, image_url) SELECT id, name, description, price, publication_date, author_id, image FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, name, description, price, publication_date, author_id, image_url FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date VARCHAR(255) NOT NULL, author_id INTEGER NOT NULL, image VARCHAR(255) NOT NULL, class INTEGER NOT NULL, mainfeatures INTEGER NOT NULL)');
        $this->addSql('INSERT INTO article (id, name, description, price, publication_date, author_id, image) SELECT id, name, description, price, publication_date, author_id, image_url FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
    }
}
