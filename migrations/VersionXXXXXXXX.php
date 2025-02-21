<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Ulid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class VersionXXXXXXXX extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Convert user ID to ULID';
    }

    public function up(Schema $schema): void
    {
        // Créer une nouvelle table avec la structure ULID
        $this->addSql('CREATE TABLE user_new (
            id VARCHAR(26) NOT NULL --(DC2Type:ulid),
            email VARCHAR(180) NOT NULL,
            roles CLOB NOT NULL --(DC2Type:json),
            password VARCHAR(255) NOT NULL,
            balance DOUBLE PRECISION DEFAULT NULL,
            profile_picture VARCHAR(255) DEFAULT NULL,
            first_name VARCHAR(255) DEFAULT NULL,
            last_name VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(255) DEFAULT NULL,
            street VARCHAR(255) DEFAULT NULL,
            city VARCHAR(255) DEFAULT NULL,
            postal_code VARCHAR(10) DEFAULT NULL,
            country VARCHAR(2) DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        // Générer un ULID pour chaque utilisateur existant
        $this->addSql('INSERT INTO user_new 
            SELECT (lower(hex(randomblob(6))) || substr(lower(hex(randomblob(10))), 1, 20)), 
            email, roles, password, balance, profile_picture, 
            first_name, last_name, phone, street, city, postal_code, country 
            FROM user');

        // Supprimer l'ancienne table
        $this->addSql('DROP TABLE IF EXISTS user');

        // Renommer la nouvelle table
        $this->addSql('ALTER TABLE user_new RENAME TO user');

        // Ajouter l'index unique sur l'email
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // Créer une table temporaire avec l'ancien format
        $this->addSql('CREATE TABLE user_old (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles CLOB NOT NULL --(DC2Type:json),
            password VARCHAR(255) NOT NULL,
            balance DOUBLE PRECISION DEFAULT NULL,
            profile_picture VARCHAR(255) DEFAULT NULL,
            first_name VARCHAR(255) DEFAULT NULL,
            last_name VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(255) DEFAULT NULL,
            street VARCHAR(255) DEFAULT NULL,
            city VARCHAR(255) DEFAULT NULL,
            postal_code VARCHAR(10) DEFAULT NULL,
            country VARCHAR(2) DEFAULT NULL
        )');

        // Copier les données en générant de nouveaux IDs auto-incrémentés
        $this->addSql('INSERT INTO user_old (
            email, roles, password, balance, profile_picture, 
            first_name, last_name, phone, street, city, postal_code, country
        ) SELECT email, roles, password, balance, profile_picture, 
            first_name, last_name, phone, street, city, postal_code, country 
            FROM user');

        // Supprimer la table ULID
        $this->addSql('DROP TABLE user');

        // Renommer la table temporaire
        $this->addSql('ALTER TABLE user_old RENAME TO user');

        // Recréer l'index
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
} 