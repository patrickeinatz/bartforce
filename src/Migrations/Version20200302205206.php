<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200302205206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, mode_id INT DEFAULT NULL, champion_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BD5FB8D977E5854A (mode_id), INDEX IDX_BD5FB8D9FA7FD7EB (champion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_match (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, opponent_a_id INT DEFAULT NULL, opponent_b_id INT DEFAULT NULL, winner_id INT DEFAULT NULL, block_id INT NOT NULL, INDEX IDX_BB0D551C33D1A3E7 (tournament_id), INDEX IDX_BB0D551C3B3A9DCB (opponent_a_id), INDEX IDX_BB0D551C298F3225 (opponent_b_id), INDEX IDX_BB0D551C5DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_mode (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, team_size INT NOT NULL, max_teams_count INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_participant (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, team_id INT DEFAULT NULL, wins INT NOT NULL, INDEX IDX_5C4BB35B33D1A3E7 (tournament_id), INDEX IDX_5C4BB35B296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_team (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_F36D142133D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D977E5854A FOREIGN KEY (mode_id) REFERENCES tournament_mode (id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9FA7FD7EB FOREIGN KEY (champion_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tournament_match ADD CONSTRAINT FK_BB0D551C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tournament_match ADD CONSTRAINT FK_BB0D551C3B3A9DCB FOREIGN KEY (opponent_a_id) REFERENCES tournament_team (id)');
        $this->addSql('ALTER TABLE tournament_match ADD CONSTRAINT FK_BB0D551C298F3225 FOREIGN KEY (opponent_b_id) REFERENCES tournament_team (id)');
        $this->addSql('ALTER TABLE tournament_match ADD CONSTRAINT FK_BB0D551C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES tournament_team (id)');
        $this->addSql('ALTER TABLE tournament_participant ADD CONSTRAINT FK_5C4BB35B33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tournament_participant ADD CONSTRAINT FK_5C4BB35B296CD8AE FOREIGN KEY (team_id) REFERENCES tournament_team (id)');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D142133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tournament_match DROP FOREIGN KEY FK_BB0D551C33D1A3E7');
        $this->addSql('ALTER TABLE tournament_participant DROP FOREIGN KEY FK_5C4BB35B33D1A3E7');
        $this->addSql('ALTER TABLE tournament_team DROP FOREIGN KEY FK_F36D142133D1A3E7');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D977E5854A');
        $this->addSql('ALTER TABLE tournament_match DROP FOREIGN KEY FK_BB0D551C3B3A9DCB');
        $this->addSql('ALTER TABLE tournament_match DROP FOREIGN KEY FK_BB0D551C298F3225');
        $this->addSql('ALTER TABLE tournament_match DROP FOREIGN KEY FK_BB0D551C5DFCD4B8');
        $this->addSql('ALTER TABLE tournament_participant DROP FOREIGN KEY FK_5C4BB35B296CD8AE');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_match');
        $this->addSql('DROP TABLE tournament_mode');
        $this->addSql('DROP TABLE tournament_participant');
        $this->addSql('DROP TABLE tournament_team');
    }
}
