<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191130093509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_category ADD description VARCHAR(255) DEFAULT NULL, CHANGE related_discord_channel_id related_discord_channel_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_post CHANGE post_creator_id post_creator_id INT DEFAULT NULL, CHANGE post_topic_id post_topic_id INT DEFAULT NULL, CHANGE post_category_id post_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE topic_creator_id topic_creator_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_category DROP description, CHANGE related_discord_channel_id related_discord_channel_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_post CHANGE post_creator_id post_creator_id INT DEFAULT NULL, CHANGE post_topic_id post_topic_id INT DEFAULT NULL, CHANGE post_category_id post_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE topic_creator_id topic_creator_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
    }
}
