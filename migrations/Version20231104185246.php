<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104185246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_amenities (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_553925B5FE54D947 (group_id), UNIQUE INDEX UNIQ_553925B5FE54D9475E237E06 (group_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_amenities_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7C900F165E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_description (id INT AUTO_INCREMENT NOT NULL, description_group_id INT NOT NULL, hotel_id INT DEFAULT NULL, text LONGTEXT NOT NULL, INDEX IDX_B32A0A094B673B63 (description_group_id), INDEX IDX_B32A0A093243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_description_groups (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_images (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, image_sort INT NOT NULL, image VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, INDEX IDX_7CF56C0D3243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotels (id INT AUTO_INCREMENT NOT NULL, location_id INT DEFAULT NULL, uri VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, check_in VARCHAR(255) NOT NULL, check_out VARCHAR(255) NOT NULL, star_rating INT NOT NULL, address VARCHAR(450) NOT NULL, latitude VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, additional_information LONGTEXT NOT NULL, INDEX IDX_E402F62564D218E (location_id), UNIQUE INDEX UNIQ_E402F625841CB121 (uri), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotels_amenities (hotel_id INT NOT NULL, hotel_amenities_id INT NOT NULL, INDEX IDX_5FBE02973243BB18 (hotel_id), INDEX IDX_5FBE02971222A171 (hotel_amenities_id), PRIMARY KEY(hotel_id, hotel_amenities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, rate_hawk_id INT NOT NULL, title VARCHAR(255) NOT NULL, country_name VARCHAR(255) NOT NULL, longitude VARCHAR(255) NOT NULL, latitude VARCHAR(255) NOT NULL, country_code VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_17E64ABA28A1E1D (rate_hawk_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, stars INT NOT NULL, title VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, INDEX IDX_6970EB0F3243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_amenities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_images (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, image_sort INT NOT NULL, image VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, INDEX IDX_A15178AB54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, uri VARCHAR(255) NOT NULL, ratehawk_room_group INT NOT NULL, INDEX IDX_7CA11A963243BB18 (hotel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rooms_amenities (room_id INT NOT NULL, room_amenities_id INT NOT NULL, INDEX IDX_1E98986654177093 (room_id), INDEX IDX_1E989866F5F4AF1 (room_amenities_id), PRIMARY KEY(room_id, room_amenities_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_amenities ADD CONSTRAINT FK_553925B5FE54D947 FOREIGN KEY (group_id) REFERENCES hotel_amenities_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_description ADD CONSTRAINT FK_B32A0A094B673B63 FOREIGN KEY (description_group_id) REFERENCES hotel_description_groups (id)');
        $this->addSql('ALTER TABLE hotel_description ADD CONSTRAINT FK_B32A0A093243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_images ADD CONSTRAINT FK_7CF56C0D3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotels ADD CONSTRAINT FK_E402F62564D218E FOREIGN KEY (location_id) REFERENCES locations (id)');
        $this->addSql('ALTER TABLE hotels_amenities ADD CONSTRAINT FK_5FBE02973243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotels_amenities ADD CONSTRAINT FK_5FBE02971222A171 FOREIGN KEY (hotel_amenities_id) REFERENCES hotel_amenities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE room_images ADD CONSTRAINT FK_A15178AB54177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A963243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rooms_amenities ADD CONSTRAINT FK_1E98986654177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rooms_amenities ADD CONSTRAINT FK_1E989866F5F4AF1 FOREIGN KEY (room_amenities_id) REFERENCES room_amenities (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_amenities DROP FOREIGN KEY FK_553925B5FE54D947');
        $this->addSql('ALTER TABLE hotel_description DROP FOREIGN KEY FK_B32A0A094B673B63');
        $this->addSql('ALTER TABLE hotel_description DROP FOREIGN KEY FK_B32A0A093243BB18');
        $this->addSql('ALTER TABLE hotel_images DROP FOREIGN KEY FK_7CF56C0D3243BB18');
        $this->addSql('ALTER TABLE hotels DROP FOREIGN KEY FK_E402F62564D218E');
        $this->addSql('ALTER TABLE hotels_amenities DROP FOREIGN KEY FK_5FBE02973243BB18');
        $this->addSql('ALTER TABLE hotels_amenities DROP FOREIGN KEY FK_5FBE02971222A171');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F3243BB18');
        $this->addSql('ALTER TABLE room_images DROP FOREIGN KEY FK_A15178AB54177093');
        $this->addSql('ALTER TABLE rooms DROP FOREIGN KEY FK_7CA11A963243BB18');
        $this->addSql('ALTER TABLE rooms_amenities DROP FOREIGN KEY FK_1E98986654177093');
        $this->addSql('ALTER TABLE rooms_amenities DROP FOREIGN KEY FK_1E989866F5F4AF1');
        $this->addSql('DROP TABLE hotel_amenities');
        $this->addSql('DROP TABLE hotel_amenities_groups');
        $this->addSql('DROP TABLE hotel_description');
        $this->addSql('DROP TABLE hotel_description_groups');
        $this->addSql('DROP TABLE hotel_images');
        $this->addSql('DROP TABLE hotels');
        $this->addSql('DROP TABLE hotels_amenities');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('DROP TABLE room_amenities');
        $this->addSql('DROP TABLE room_images');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP TABLE rooms_amenities');
    }
}
