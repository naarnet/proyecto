<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724194629 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store_migration ADD marketplace_id INT NOT NULL');
        $this->addSql('ALTER TABLE store_migration ADD CONSTRAINT FK_9E4AD9BF7078ABE4 FOREIGN KEY (marketplace_id) REFERENCES marketplace (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9E4AD9BF7078ABE4 ON store_migration (marketplace_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store_migration DROP FOREIGN KEY FK_9E4AD9BF7078ABE4');
        $this->addSql('DROP INDEX IDX_9E4AD9BF7078ABE4 ON store_migration');
        $this->addSql('ALTER TABLE store_migration DROP marketplace_id');
    }
}
