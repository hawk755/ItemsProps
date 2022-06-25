<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625091020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE55E38587');
        $this->addSql('DROP INDEX IDX_8BF21CDE55E38587 ON property');
        $this->addSql('ALTER TABLE property CHANGE item_id_id item_id INT NOT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE126F525E ON property (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE126F525E');
        $this->addSql('DROP INDEX IDX_8BF21CDE126F525E ON property');
        $this->addSql('ALTER TABLE property CHANGE item_id item_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE55E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDE55E38587 ON property (item_id_id)');
    }
}
