<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801145746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, theme_id INT NOT NULL, titre VARCHAR(255) NOT NULL, annee VARCHAR(255) DEFAULT NULL, INDEX IDX_39986E4359027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, texte LONGTEXT NOT NULL, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_9474526C60BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, auteur_id_id INT DEFAULT NULL, photo_id_id INT DEFAULT NULL, texte VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_67F068BC75F8742E (auteur_id_id), INDEX IDX_67F068BCC51599E0 (photo_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lien_tag_photo (id INT AUTO_INCREMENT NOT NULL, tag_id INT DEFAULT NULL, photo_id INT DEFAULT NULL, INDEX IDX_D4EB4526BAD26311 (tag_id), INDEX IDX_D4EB45267E9E4C8C (photo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, album_id INT NOT NULL, auteur_id INT NOT NULL, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, file VARCHAR(255) NOT NULL, annee VARCHAR(255) DEFAULT \'0000\', lieu VARCHAR(255) DEFAULT NULL, INDEX IDX_14B784181137ABCF (album_id), INDEX IDX_14B7841860BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_photo (tag_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_365B9D1EBAD26311 (tag_id), INDEX IDX_365B9D1E7E9E4C8C (photo_id), PRIMARY KEY(tag_id, photo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, is_valid TINYINT(1) NOT NULL, mail VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D6495126AC48 (mail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E4359027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC75F8742E FOREIGN KEY (auteur_id_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC51599E0 FOREIGN KEY (photo_id_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lien_tag_photo ADD CONSTRAINT FK_D4EB4526BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE lien_tag_photo ADD CONSTRAINT FK_D4EB45267E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784181137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841860BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag_photo ADD CONSTRAINT FK_365B9D1EBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_photo ADD CONSTRAINT FK_365B9D1E7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784181137ABCF');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC51599E0');
        $this->addSql('ALTER TABLE lien_tag_photo DROP FOREIGN KEY FK_D4EB45267E9E4C8C');
        $this->addSql('ALTER TABLE tag_photo DROP FOREIGN KEY FK_365B9D1E7E9E4C8C');
        $this->addSql('ALTER TABLE lien_tag_photo DROP FOREIGN KEY FK_D4EB4526BAD26311');
        $this->addSql('ALTER TABLE tag_photo DROP FOREIGN KEY FK_365B9D1EBAD26311');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E4359027487');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C60BB6FE6');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC75F8742E');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841860BB6FE6');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE lien_tag_photo');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_photo');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE user');
    }
}
