<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191129094543 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, post_creator_id INT DEFAULT NULL, post_topic_id INT DEFAULT NULL, post_category_id INT DEFAULT NULL, post_content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_996BCC5A425A0767 (post_creator_id), INDEX IDX_996BCC5AA0B8A99C (post_topic_id), INDEX IDX_996BCC5AFE0617CD (post_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5A425A0767 FOREIGN KEY (post_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AA0B8A99C FOREIGN KEY (post_topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AFE0617CD FOREIGN KEY (post_category_id) REFERENCES forum_category (id)');
        $this->addSql('ALTER TABLE forum_category CHANGE related_discord_channel_id related_discord_channel_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE topic_creator_id topic_creator_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE forum_post');
        $this->addSql('ALTER TABLE forum_category CHANGE related_discord_channel_id related_discord_channel_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_topic CHANGE topic_creator_id topic_creator_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL');
    }
}
