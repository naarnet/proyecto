<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180725233025 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mapping_category (id INT AUTO_INCREMENT NOT NULL, store_category_id INT DEFAULT NULL, store_migration_id INT DEFAULT NULL, marketplace_migration_id INT DEFAULT NULL, published TINYINT(1) NOT NULL, INDEX IDX_3D0969E0CCD3EA7C (store_category_id), INDEX IDX_3D0969E06EB0AA5B (store_migration_id), INDEX IDX_3D0969E07F557761 (marketplace_migration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mapping_category ADD CONSTRAINT FK_3D0969E0CCD3EA7C FOREIGN KEY (store_category_id) REFERENCES store_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mapping_category ADD CONSTRAINT FK_3D0969E06EB0AA5B FOREIGN KEY (store_migration_id) REFERENCES store_migration (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mapping_category ADD CONSTRAINT FK_3D0969E07F557761 FOREIGN KEY (marketplace_migration_id) REFERENCES marketplace_category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mapping_category');
    }
}
