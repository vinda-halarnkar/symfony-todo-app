<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250506075051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D3DAE168B
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E11EE94D3DAE168B ON items
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE items CHANGE list_id user_list_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE items ADD CONSTRAINT FK_E11EE94D65A30881 FOREIGN KEY (user_list_id) REFERENCES user_list (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E11EE94D65A30881 ON items (user_list_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D65A30881
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_E11EE94D65A30881 ON items
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE items CHANGE user_list_id list_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE items ADD CONSTRAINT FK_E11EE94D3DAE168B FOREIGN KEY (list_id) REFERENCES user_list (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_E11EE94D3DAE168B ON items (list_id)
        SQL);
    }
}
