<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202075443 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE forum_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, related_discord_channel_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_post (id INT AUTO_INCREMENT NOT NULL, post_creator_id INT DEFAULT NULL, post_topic_id INT DEFAULT NULL, post_category_id INT DEFAULT NULL, post_content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_996BCC5A425A0767 (post_creator_id), INDEX IDX_996BCC5AA0B8A99C (post_topic_id), INDEX IDX_996BCC5AFE0617CD (post_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_reply (id INT AUTO_INCREMENT NOT NULL, reply_creator_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, post_id INT DEFAULT NULL, reply_content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E5DC60376BEF02F0 (reply_creator_id), INDEX IDX_E5DC60371F55203D (topic_id), INDEX IDX_E5DC60374B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_topic (id INT AUTO_INCREMENT NOT NULL, topic_creator_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, topic_content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_853478CCA471A08C (topic_creator_id), INDEX IDX_853478CC12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5A425A0767 FOREIGN KEY (post_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AA0B8A99C FOREIGN KEY (post_topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE forum_post ADD CONSTRAINT FK_996BCC5AFE0617CD FOREIGN KEY (post_category_id) REFERENCES forum_category (id)');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC60376BEF02F0 FOREIGN KEY (reply_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC60371F55203D FOREIGN KEY (topic_id) REFERENCES forum_topic (id)');
        $this->addSql('ALTER TABLE forum_reply ADD CONSTRAINT FK_E5DC60374B89032C FOREIGN KEY (post_id) REFERENCES forum_post (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CCA471A08C FOREIGN KEY (topic_creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE forum_topic ADD CONSTRAINT FK_853478CC12469DE2 FOREIGN KEY (category_id) REFERENCES forum_category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AFE0617CD');
        $this->addSql('ALTER TABLE forum_topic DROP FOREIGN KEY FK_853478CC12469DE2');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC60374B89032C');
        $this->addSql('ALTER TABLE forum_post DROP FOREIGN KEY FK_996BCC5AA0B8A99C');
        $this->addSql('ALTER TABLE forum_reply DROP FOREIGN KEY FK_E5DC60371F55203D');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_post');
        $this->addSql('DROP TABLE forum_reply');
        $this->addSql('DROP TABLE forum_topic');
    }
}
