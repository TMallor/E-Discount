<?php

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221101216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Suppression des anciennes colonnes qui causent des conflits
        $this->addSql('DROP TABLE IF EXISTS __temp__user');
        
        // Mise à jour de la structure de la table user
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, balance, profile_picture, first_name, last_name, street, city, postal_code, country, phone FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            email VARCHAR(180) NOT NULL, 
            roles CLOB NOT NULL --(DC2Type:json)
            , password VARCHAR(255) NOT NULL, 
            balance DOUBLE PRECISION DEFAULT NULL,
            profile_picture VARCHAR(255) DEFAULT NULL,
            first_name VARCHAR(255) DEFAULT NULL,
            last_name VARCHAR(255) DEFAULT NULL,
            street VARCHAR(255) DEFAULT NULL,
            city VARCHAR(255) DEFAULT NULL,
            postal_code VARCHAR(10) DEFAULT NULL,
            country VARCHAR(2) DEFAULT NULL,
            phone VARCHAR(255) DEFAULT NULL
        )');
        $this->addSql('INSERT INTO user (id, email, roles, password, balance, profile_picture, first_name, last_name, street, city, postal_code, country, phone) SELECT id, email, roles, password, balance, profile_picture, first_name, last_name, street, city, postal_code, country, phone FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
} 