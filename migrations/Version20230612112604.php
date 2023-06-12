<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612112604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT NOT NULL, item_id INT NOT NULL, authors TEXT DEFAULT NULL, publisher VARCHAR(255) DEFAULT NULL, published_date DATE DEFAULT NULL, page_count INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBE5A331126F525E ON book (item_id)');
        $this->addSql('COMMENT ON COLUMN book.authors IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE friendship (id INT NOT NULL, friendship_requester_id INT NOT NULL, friendship_accepter_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7234A45F881A4E45 ON friendship (friendship_requester_id)');
        $this->addSql('CREATE INDEX IDX_7234A45FA66C3075 ON friendship (friendship_accepter_id)');
        $this->addSql('CREATE TABLE genre (id INT NOT NULL, name VARCHAR(100) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE item (id INT NOT NULL, posted_by_id INT NOT NULL, media_id INT DEFAULT NULL, media_type VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1F1B251E5A6D2235 ON item (posted_by_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F1B251EEA9FDD75 ON item (media_id)');
        $this->addSql('CREATE TABLE item_genre (id INT NOT NULL, item_id INT NOT NULL, genre_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A9F72828126F525E ON item_genre (item_id)');
        $this->addSql('CREATE INDEX IDX_A9F728284296D31F ON item_genre (genre_id)');
        $this->addSql('CREATE TABLE media (id INT NOT NULL, image_url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE movie (id INT NOT NULL, item_id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, casting TEXT DEFAULT NULL, directors TEXT DEFAULT NULL, year DATE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26F126F525E ON movie (item_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, item_id INT NOT NULL, posted_by_id INT NOT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C6126F525E ON review (item_id)');
        $this->addSql('CREATE INDEX IDX_794381C65A6D2235 ON review (posted_by_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, user_media_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, username VARCHAR(100) NOT NULL, phone VARCHAR(25) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A123975 ON "user" (user_media_id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45F881A4E45 FOREIGN KEY (friendship_requester_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45FA66C3075 FOREIGN KEY (friendship_accepter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_genre ADD CONSTRAINT FK_A9F72828126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_genre ADD CONSTRAINT FK_A9F728284296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6126F525E FOREIGN KEY (item_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C65A6D2235 FOREIGN KEY (posted_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649A123975 FOREIGN KEY (user_media_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331126F525E');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT FK_7234A45F881A4E45');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT FK_7234A45FA66C3075');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251E5A6D2235');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT FK_1F1B251EEA9FDD75');
        $this->addSql('ALTER TABLE item_genre DROP CONSTRAINT FK_A9F72828126F525E');
        $this->addSql('ALTER TABLE item_genre DROP CONSTRAINT FK_A9F728284296D31F');
        $this->addSql('ALTER TABLE movie DROP CONSTRAINT FK_1D5EF26F126F525E');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6126F525E');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C65A6D2235');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649A123975');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE friendship');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_genre');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE "user"');
    }
}
