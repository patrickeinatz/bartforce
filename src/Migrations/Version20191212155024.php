<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191212155024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_topic ADD topic_content_module_id INT DEFAULT NULL, ADD topic_text VARCHAR(255) NOT NULL, DROP topic_content');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCE9DE40F1 FOREIGN KEY (topic_content_module_id) REFERENCES topic_content_module (id)');
        $this->addSql('CREATE INDEX IDX_853478CCE9DE40F1 ON forum_topic (topic_content_module_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CCE9DE40F1');
        $this->addSql('DROP INDEX IDX_853478CCE9DE40F1 ON forum_topic');
        $this->addSql('ALTER TABLE forum_topic ADD topic_content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP topic_content_module_id, DROP topic_text');
    }
}
