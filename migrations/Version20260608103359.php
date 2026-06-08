<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608103359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, mensaje VARCHAR(255) NOT NULL, emisor_id INT DEFAULT NULL, juego_id INT DEFAULT NULL, INDEX IDX_4B91E7026BDF87DF (emisor_id), INDEX IDX_4B91E70213375255 (juego_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7026BDF87DF FOREIGN KEY (emisor_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70213375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7026BDF87DF');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70213375255');
        $this->addSql('DROP TABLE comentario');
    }
}
