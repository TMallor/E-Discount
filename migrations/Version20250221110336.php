<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221110336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, name, description, price, publication_date, author_id, image_url FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date VARCHAR(255) NOT NULL, author_id INTEGER NOT NULL, image VARCHAR(255) NOT NULL, class INTEGER NOT NULL, mainfeatures INTEGER NOT NULL)');
        $this->addSql('INSERT INTO article (id, name, description, price, publication_date, author_id, image) SELECT id, name, description, price, publication_date, author_id, image_url FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, article_id FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id BLOB NOT NULL --(DC2Type:ulid)
        , article_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO cart (id, user_id, article_id) SELECT id, user_id, article_id FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE TEMPORARY TABLE __temp__invoice AS SELECT id, user_id, transaction_date, amount, billing_adress, billing_city, billing_postal_code FROM invoice');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('CREATE TABLE invoice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id BLOB NOT NULL --(DC2Type:ulid)
        , transaction_date VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, billing_adress VARCHAR(255) NOT NULL, billing_city VARCHAR(255) NOT NULL, billing_postal_code VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO invoice (id, user_id, transaction_date, amount, billing_adress, billing_city, billing_postal_code) SELECT id, user_id, transaction_date, amount, billing_adress, billing_city, billing_postal_code FROM __temp__invoice');
        $this->addSql('DROP TABLE __temp__invoice');
        $this->addSql('CREATE TEMPORARY TABLE __temp__previous_cart AS SELECT id, user_id, article_id, purchased_at FROM previous_cart');
        $this->addSql('DROP TABLE previous_cart');
        $this->addSql('CREATE TABLE previous_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id BLOB NOT NULL --(DC2Type:ulid)
        , article_id INTEGER NOT NULL, purchased_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO previous_cart (id, user_id, article_id, purchased_at) SELECT id, user_id, article_id, purchased_at FROM __temp__previous_cart');
        $this->addSql('DROP TABLE __temp__previous_cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, name, description, price, publication_date, author_id, image FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date VARCHAR(255) NOT NULL, author_id INTEGER NOT NULL, image_url VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO article (id, name, description, price, publication_date, author_id, image_url) SELECT id, name, description, price, publication_date, author_id, image FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, user_id, article_id FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, article_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO cart (id, user_id, article_id) SELECT id, user_id, article_id FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE TEMPORARY TABLE __temp__invoice AS SELECT id, user_id, transaction_date, amount, billing_adress, billing_city, billing_postal_code FROM invoice');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('CREATE TABLE invoice (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, transaction_date VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, billing_adress VARCHAR(255) NOT NULL, billing_city VARCHAR(255) NOT NULL, billing_postal_code VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO invoice (id, user_id, transaction_date, amount, billing_adress, billing_city, billing_postal_code) SELECT id, user_id, transaction_date, amount, billing_adress, billing_city, billing_postal_code FROM __temp__invoice');
        $this->addSql('DROP TABLE __temp__invoice');
        $this->addSql('CREATE TEMPORARY TABLE __temp__previous_cart AS SELECT id, user_id, article_id, purchased_at FROM previous_cart');
        $this->addSql('DROP TABLE previous_cart');
        $this->addSql('CREATE TABLE previous_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, article_id INTEGER NOT NULL, purchased_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO previous_cart (id, user_id, article_id, purchased_at) SELECT id, user_id, article_id, purchased_at FROM __temp__previous_cart');
        $this->addSql('DROP TABLE __temp__previous_cart');
    }
}
