<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111142818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_viewed_receipe DROP FOREIGN KEY FK_581F658C9D86650F');
        $this->addSql('DROP INDEX IDX_581F658C9D86650F ON user_viewed_receipe');
        $this->addSql('ALTER TABLE user_viewed_receipe CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_viewed_receipe ADD CONSTRAINT FK_581F658CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_581F658CA76ED395 ON user_viewed_receipe (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_viewed_receipe DROP FOREIGN KEY FK_581F658CA76ED395');
        $this->addSql('DROP INDEX IDX_581F658CA76ED395 ON user_viewed_receipe');
        $this->addSql('ALTER TABLE user_viewed_receipe CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_viewed_receipe ADD CONSTRAINT FK_581F658C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_581F658C9D86650F ON user_viewed_receipe (user_id_id)');
    }
}
