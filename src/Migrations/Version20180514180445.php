<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180514180445 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store_credential ADD store_id INT DEFAULT NULL, DROP store');
        $this->addSql('ALTER TABLE store_credential ADD CONSTRAINT FK_2B9646A6B092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2B9646A6B092A811 ON store_credential (store_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store_credential DROP FOREIGN KEY FK_2B9646A6B092A811');
        $this->addSql('DROP INDEX IDX_2B9646A6B092A811 ON store_credential');
        $this->addSql('ALTER TABLE store_credential ADD store VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP store_id');
    }
}
