<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260607163600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria_juego (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, fecha DATE NOT NULL, ruta_imagen VARCHAR(255) DEFAULT NULL, categoria_id INT DEFAULT NULL, autor_id INT NOT NULL, INDEX IDX_F0EC403D3397707A (categoria_id), INDEX IDX_F0EC403D14D45BBE (autor_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT NOT NULL, nombre VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403D3397707A FOREIGN KEY (categoria_id) REFERENCES categoria_juego (id)');
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403D14D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego DROP FOREIGN KEY FK_F0EC403D3397707A');
        $this->addSql('ALTER TABLE juego DROP FOREIGN KEY FK_F0EC403D14D45BBE');
        $this->addSql('DROP TABLE categoria_juego');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE usuario');
    }
}
