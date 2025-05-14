<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422052710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE items (id INT AUTO_INCREMENT NOT NULL, list_id INT NOT NULL, name VARCHAR(155) NOT NULL, INDEX IDX_E11EE94D3DAE168B (list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE items ADD CONSTRAINT FK_E11EE94D3DAE168B FOREIGN KEY (list_id) REFERENCES user_list (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_list ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_list ADD CONSTRAINT FK_3E49B4D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3E49B4D1A76ED395 ON user_list (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D3DAE168B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE items
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_list DROP FOREIGN KEY FK_3E49B4D1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3E49B4D1A76ED395 ON user_list
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_list DROP user_id
        SQL);
    }
}
