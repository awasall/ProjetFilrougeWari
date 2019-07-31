<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190731082550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_32FFA37366918367 ON partenaire');
        $this->addSql('ALTER TABLE partenaire CHANGE telephone telephone VARCHAR(12) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA373C678AEBE ON partenaire (ninea)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA373450FF010 ON partenaire (telephone)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_32FFA373C678AEBE ON partenaire');
        $this->addSql('DROP INDEX UNIQ_32FFA373450FF010 ON partenaire');
        $this->addSql('ALTER TABLE partenaire CHANGE telephone telephone VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32FFA37366918367 ON partenaire (solde)');
    }
}
