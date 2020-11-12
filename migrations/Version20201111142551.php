<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201111142551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_viewed_receipe DROP FOREIGN KEY FK_581F658C4FE39290');
        $this->addSql('DROP INDEX IDX_581F658C4FE39290 ON user_viewed_receipe');
        $this->addSql('ALTER TABLE user_viewed_receipe CHANGE receipe_reference_id_id receipe_reference_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_viewed_receipe ADD CONSTRAINT FK_581F658C7CAB4D3C FOREIGN KEY (receipe_reference_id) REFERENCES receipe_reference (id)');
        $this->addSql('CREATE INDEX IDX_581F658C7CAB4D3C ON user_viewed_receipe (receipe_reference_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_viewed_receipe DROP FOREIGN KEY FK_581F658C7CAB4D3C');
        $this->addSql('DROP INDEX IDX_581F658C7CAB4D3C ON user_viewed_receipe');
        $this->addSql('ALTER TABLE user_viewed_receipe CHANGE receipe_reference_id receipe_reference_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_viewed_receipe ADD CONSTRAINT FK_581F658C4FE39290 FOREIGN KEY (receipe_reference_id_id) REFERENCES receipe_reference (id)');
        $this->addSql('CREATE INDEX IDX_581F658C4FE39290 ON user_viewed_receipe (receipe_reference_id_id)');
    }
}
