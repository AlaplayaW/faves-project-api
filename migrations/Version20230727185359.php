<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727185359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649a123975');
        $this->addSql('DROP INDEX uniq_8d93d649a123975');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN user_media_id TO media_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649EA9FDD75 ON "user" (media_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649EA9FDD75');
        $this->addSql('DROP INDEX UNIQ_8D93D649EA9FDD75');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN media_id TO user_media_id');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649a123975 FOREIGN KEY (user_media_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649a123975 ON "user" (user_media_id)');
    }
}
