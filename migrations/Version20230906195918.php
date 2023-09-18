<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906195918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE recommendation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE recommendation (id INT NOT NULL, user_id_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_433224D29D86650F ON recommendation (user_id_id)');
        $this->addSql('ALTER TABLE recommendation ADD CONSTRAINT FK_433224D29D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT fk_cbe5a3315a6d2235');
        $this->addSql('DROP INDEX idx_cbe5a3315a6d2235');
        $this->addSql('ALTER TABLE book ADD publisher VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE book DROP publishers');
        $this->addSql('ALTER TABLE book RENAME COLUMN posted_by_id TO user_id');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBE5A331A76ED395 ON book (user_id)');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT fk_7234a45f881a4e45');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT fk_7234a45fa66c3075');
        $this->addSql('DROP INDEX idx_friendship_accepter_id');
        $this->addSql('DROP INDEX idx_friendship_requester_id');
        $this->addSql('ALTER TABLE friendship ADD friend_requester_id INT NOT NULL');
        $this->addSql('ALTER TABLE friendship ADD friend_accepter_id INT NOT NULL');
        $this->addSql('ALTER TABLE friendship ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE friendship ADD request_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE friendship ADD acceptance_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE friendship ADD rejection_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE friendship DROP friendship_requester_id');
        $this->addSql('ALTER TABLE friendship DROP friendship_accepter_id');
        $this->addSql('ALTER TABLE friendship DROP is_accepted');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45F33F871B2 FOREIGN KEY (friend_requester_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT FK_7234A45F4F14C4C0 FOREIGN KEY (friend_accepter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_friend_requester_id ON friendship (friend_requester_id)');
        $this->addSql('CREATE INDEX idx_friend_accepter_id ON friendship (friend_accepter_id)');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT fk_794381c65a6d2235');
        $this->addSql('DROP INDEX idx_794381c65a6d2235');
        $this->addSql('ALTER TABLE review RENAME COLUMN posted_by_id TO user_id');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE recommendation_id_seq CASCADE');
        $this->addSql('ALTER TABLE recommendation DROP CONSTRAINT FK_433224D29D86650F');
        $this->addSql('DROP TABLE recommendation');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331A76ED395');
        $this->addSql('DROP INDEX IDX_CBE5A331A76ED395');
        $this->addSql('ALTER TABLE book ADD publishers TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE book DROP publisher');
        $this->addSql('ALTER TABLE book RENAME COLUMN user_id TO posted_by_id');
        $this->addSql('COMMENT ON COLUMN book.publishers IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT fk_cbe5a3315a6d2235 FOREIGN KEY (posted_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_cbe5a3315a6d2235 ON book (posted_by_id)');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6A76ED395');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395');
        $this->addSql('ALTER TABLE review RENAME COLUMN user_id TO posted_by_id');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT fk_794381c65a6d2235 FOREIGN KEY (posted_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_794381c65a6d2235 ON review (posted_by_id)');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT FK_7234A45F33F871B2');
        $this->addSql('ALTER TABLE friendship DROP CONSTRAINT FK_7234A45F4F14C4C0');
        $this->addSql('DROP INDEX idx_friend_requester_id');
        $this->addSql('DROP INDEX idx_friend_accepter_id');
        $this->addSql('ALTER TABLE friendship ADD friendship_requester_id INT NOT NULL');
        $this->addSql('ALTER TABLE friendship ADD friendship_accepter_id INT NOT NULL');
        $this->addSql('ALTER TABLE friendship ADD is_accepted BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE friendship DROP friend_requester_id');
        $this->addSql('ALTER TABLE friendship DROP friend_accepter_id');
        $this->addSql('ALTER TABLE friendship DROP status');
        $this->addSql('ALTER TABLE friendship DROP request_date');
        $this->addSql('ALTER TABLE friendship DROP acceptance_date');
        $this->addSql('ALTER TABLE friendship DROP rejection_date');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT fk_7234a45f881a4e45 FOREIGN KEY (friendship_requester_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE friendship ADD CONSTRAINT fk_7234a45fa66c3075 FOREIGN KEY (friendship_accepter_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_friendship_accepter_id ON friendship (friendship_accepter_id)');
        $this->addSql('CREATE INDEX idx_friendship_requester_id ON friendship (friendship_requester_id)');
    }
}
