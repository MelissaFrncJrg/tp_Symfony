<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408201807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE origin ADD user_id INT DEFAULT NULL, ADD is_public TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE origin ADD CONSTRAINT FK_DEF1561EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DEF1561EA76ED395 ON origin (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD user_id INT DEFAULT NULL, ADD is_public TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag ADD CONSTRAINT FK_389B783A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_389B783A76ED395 ON tag (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP FOREIGN KEY FK_389B783A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_389B783A76ED395 ON tag
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag DROP user_id, DROP is_public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE origin DROP FOREIGN KEY FK_DEF1561EA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DEF1561EA76ED395 ON origin
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE origin DROP user_id, DROP is_public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_DA88B137A76ED395 ON recipe
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE recipe DROP user_id
        SQL);
    }
}
