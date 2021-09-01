<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831080109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD manager_id_id INT DEFAULT NULL, ADD subordinate_id_id INT DEFAULT NULL, DROP content, DROP author, DROP audience');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C569B5E6D FOREIGN KEY (manager_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C80767D89 FOREIGN KEY (subordinate_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526C569B5E6D ON comment (manager_id_id)');
        $this->addSql('CREATE INDEX IDX_9474526C80767D89 ON comment (subordinate_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE team');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C569B5E6D');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C80767D89');
        $this->addSql('DROP INDEX IDX_9474526C569B5E6D ON comment');
        $this->addSql('DROP INDEX IDX_9474526C80767D89 ON comment');
        $this->addSql('ALTER TABLE comment ADD content VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD author VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD audience VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP manager_id_id, DROP subordinate_id_id');
    }
}
