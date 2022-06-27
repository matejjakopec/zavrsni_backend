<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620152757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, post_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, price NUMERIC(10, 0) NOT NULL, published DATETIME NOT NULL, INDEX IDX_29D6873EF675F31B (author_id), INDEX IDX_29D6873E4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_garbage (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published DATETIME NOT NULL, INDEX IDX_6171835EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_garbage_image (post_garbage_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_15512DE837CF4CC9 (post_garbage_id), INDEX IDX_15512DE83DA5256D (image_id), PRIMARY KEY(post_garbage_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_removal (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, published DATETIME NOT NULL, INDEX IDX_E9330078F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_removal_image (post_removal_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_ECD9DB0F83739D27 (post_removal_id), INDEX IDX_ECD9DB0F3DA5256D (image_id), PRIMARY KEY(post_removal_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E4B89032C FOREIGN KEY (post_id) REFERENCES post_removal (id)');
        $this->addSql('ALTER TABLE post_garbage ADD CONSTRAINT FK_6171835EF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_garbage_image ADD CONSTRAINT FK_15512DE837CF4CC9 FOREIGN KEY (post_garbage_id) REFERENCES post_garbage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_garbage_image ADD CONSTRAINT FK_15512DE83DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_removal ADD CONSTRAINT FK_E9330078F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_removal_image ADD CONSTRAINT FK_ECD9DB0F83739D27 FOREIGN KEY (post_removal_id) REFERENCES post_removal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_removal_image ADD CONSTRAINT FK_ECD9DB0F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_garbage_image DROP FOREIGN KEY FK_15512DE83DA5256D');
        $this->addSql('ALTER TABLE post_removal_image DROP FOREIGN KEY FK_ECD9DB0F3DA5256D');
        $this->addSql('ALTER TABLE post_garbage_image DROP FOREIGN KEY FK_15512DE837CF4CC9');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E4B89032C');
        $this->addSql('ALTER TABLE post_removal_image DROP FOREIGN KEY FK_ECD9DB0F83739D27');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EF675F31B');
        $this->addSql('ALTER TABLE post_garbage DROP FOREIGN KEY FK_6171835EF675F31B');
        $this->addSql('ALTER TABLE post_removal DROP FOREIGN KEY FK_E9330078F675F31B');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE post_garbage');
        $this->addSql('DROP TABLE post_garbage_image');
        $this->addSql('DROP TABLE post_removal');
        $this->addSql('DROP TABLE post_removal_image');
        $this->addSql('DROP TABLE user');
    }
}
