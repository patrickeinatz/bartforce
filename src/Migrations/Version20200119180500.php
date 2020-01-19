<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200119180500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post_kudos (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_878D59124B89032C (post_id), INDEX IDX_878D5912A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_kudos (id INT AUTO_INCREMENT NOT NULL, topic_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_ABA5799F1F55203D (topic_id), INDEX IDX_ABA5799FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_kudos ADD CONSTRAINT FK_878D59124B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE post_kudos ADD CONSTRAINT FK_878D5912A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE topic_kudos ADD CONSTRAINT FK_ABA5799F1F55203D FOREIGN KEY (topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE topic_kudos ADD CONSTRAINT FK_ABA5799FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_category CHANGE related_discord_channel_id related_discord_channel_id VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_post CHANGE post_creator_id post_creator_id INT DEFAULT NULL, CHANGE post_topic_id post_topic_id INT DEFAULT NULL, CHANGE post_category_id post_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_reply CHANGE reply_creator_id reply_creator_id INT DEFAULT NULL, CHANGE topic_id topic_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE topic_creator_id topic_creator_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE topic_content_module_id topic_content_module_id INT DEFAULT NULL, CHANGE topic_text topic_text VARCHAR(255) DEFAULT NULL, CHANGE topic_content topic_content VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE joined_at joined_at INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE post_kudos');
        $this->addSql('DROP TABLE topic_kudos');
        $this->addSql('ALTER TABLE forum_category CHANGE related_discord_channel_id related_discord_channel_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE forum_post CHANGE post_creator_id post_creator_id INT DEFAULT NULL, CHANGE post_topic_id post_topic_id INT DEFAULT NULL, CHANGE post_category_id post_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_reply CHANGE reply_creator_id reply_creator_id INT DEFAULT NULL, CHANGE topic_id topic_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE forum_topic CHANGE topic_content_module_id topic_content_module_id INT DEFAULT NULL, CHANGE topic_creator_id topic_creator_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE topic_content topic_content VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE topic_text topic_text VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE joined_at joined_at INT DEFAULT NULL');
    }
}
