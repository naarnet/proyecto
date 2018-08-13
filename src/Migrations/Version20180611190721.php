<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180611190721 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marketplace_category ADD marketplace_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marketplace_category ADD CONSTRAINT FK_12A9A35D7078ABE4 FOREIGN KEY (marketplace_id) REFERENCES marketplace (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_12A9A35D7078ABE4 ON marketplace_category (marketplace_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marketplace_category DROP FOREIGN KEY FK_12A9A35D7078ABE4');
        $this->addSql('DROP INDEX IDX_12A9A35D7078ABE4 ON marketplace_category');
        $this->addSql('ALTER TABLE marketplace_category DROP marketplace_id');
    }
}
