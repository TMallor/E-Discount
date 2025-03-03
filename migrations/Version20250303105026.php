<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303105026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, article_id, quantity FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_4B3656607294869C FOREIGN KEY (article_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO stock (id, article_id, quantity) SELECT id, article_id, quantity FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B3656607294869C ON stock (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__stock AS SELECT id, article_id, quantity FROM stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('CREATE TABLE stock (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER DEFAULT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_4B3656607294869C FOREIGN KEY (article_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO stock (id, article_id, quantity) SELECT id, article_id, quantity FROM __temp__stock');
        $this->addSql('DROP TABLE __temp__stock');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B3656607294869C ON stock (article_id)');
    }
}
