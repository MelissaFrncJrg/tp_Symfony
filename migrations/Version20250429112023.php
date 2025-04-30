<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429112023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13787998E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DA88B13787998E ON recipe
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe CHANGE origine_id origin_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13756A273CC FOREIGN KEY (origin_id) REFERENCES origin (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA88B13756A273CC ON recipe (origin_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13756A273CC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DA88B13756A273CC ON recipe
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe CHANGE origin_id origine_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13787998E FOREIGN KEY (origine_id) REFERENCES origin (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA88B13787998E ON recipe (origine_id)
        SQL);
    }
}
