<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111131341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE receipe_reference (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, receipe_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_viewed_receipe (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, receipe_reference_id_id INT NOT NULL, last_view DATETIME NOT NULL, is_favourite TINYINT(1) DEFAULT NULL, INDEX IDX_581F658C9D86650F (user_id_id), INDEX IDX_581F658C4FE39290 (receipe_reference_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_viewed_receipe ADD CONSTRAINT FK_581F658C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_viewed_receipe ADD CONSTRAINT FK_581F658C4FE39290 FOREIGN KEY (receipe_reference_id_id) REFERENCES receipe_reference (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_viewed_receipe DROP FOREIGN KEY FK_581F658C4FE39290');
        $this->addSql('DROP TABLE receipe_reference');
        $this->addSql('DROP TABLE user_viewed_receipe');
    }
}
