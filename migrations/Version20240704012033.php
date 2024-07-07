<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240704012033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delta_updates (id INT AUTO_INCREMENT NOT NULL, last_update VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, error_code INT DEFAULT NULL, transaction_id VARCHAR(255) DEFAULT NULL, amount INT DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_65D29B322FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_images (id INT AUTO_INCREMENT NOT NULL, review_id INT NOT NULL, image VARCHAR(255) NOT NULL, image_sort INT NOT NULL, INDEX IDX_6A69F9AA3E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review_images ADD CONSTRAINT FK_6A69F9AA3E2E969B FOREIGN KEY (review_id) REFERENCES reviews (id)');
        $this->addSql('ALTER TABLE hotels ADD client_rating NUMERIC(5, 1) DEFAULT NULL, ADD cleanness_rating NUMERIC(5, 1) DEFAULT NULL, ADD location_rating NUMERIC(5, 1) DEFAULT NULL, ADD price_rating NUMERIC(5, 1) DEFAULT NULL, ADD services_rating NUMERIC(5, 1) DEFAULT NULL, ADD room_rating NUMERIC(5, 1) DEFAULT NULL, ADD meal_rating NUMERIC(5, 1) DEFAULT NULL, ADD wifi_rating NUMERIC(5, 1) DEFAULT NULL, ADD hygiene_rating NUMERIC(5, 1) DEFAULT NULL, DROP type');
        $this->addSql('CREATE INDEX hotels_star_rating_search ON hotels (location_id, star_rating, id)');
        $this->addSql('ALTER TABLE hotels_amenities DROP FOREIGN KEY FK_5FBE02971222A171');
        $this->addSql('ALTER TABLE hotels_amenities DROP FOREIGN KEY FK_5FBE02973243BB18');
        $this->addSql('ALTER TABLE hotels_amenities ADD CONSTRAINT FK_5FBE02971222A171 FOREIGN KEY (hotel_amenities_id) REFERENCES hotel_amenities (id)');
        $this->addSql('ALTER TABLE hotels_amenities ADD CONSTRAINT FK_5FBE02973243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE locations ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reviews ADD review_plus LONGTEXT DEFAULT NULL, ADD review_minus LONGTEXT DEFAULT NULL, ADD created_at VARCHAR(255) DEFAULT NULL, ADD adults INT DEFAULT NULL, ADD children INT DEFAULT NULL, ADD room_name VARCHAR(255) DEFAULT NULL, ADD nights INT DEFAULT NULL, ADD traveller_type VARCHAR(255) DEFAULT NULL, ADD trip_type VARCHAR(255) DEFAULT NULL, ADD rating NUMERIC(5, 1) DEFAULT NULL, ADD cleanness_rating INT DEFAULT NULL, ADD location_rating INT DEFAULT NULL, ADD price_rating INT DEFAULT NULL, ADD services_rating INT DEFAULT NULL, ADD room_rating INT DEFAULT NULL, ADD meal_rating INT DEFAULT NULL, ADD wifi_rating VARCHAR(255) DEFAULT NULL, ADD hygiene_rating VARCHAR(255) DEFAULT NULL, DROP stars, DROP title, DROP text, CHANGE author author VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rooms_amenities DROP FOREIGN KEY FK_1E98986654177093');
        $this->addSql('ALTER TABLE rooms_amenities DROP FOREIGN KEY FK_1E989866F5F4AF1');
        $this->addSql('ALTER TABLE rooms_amenities ADD CONSTRAINT FK_1E98986654177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE rooms_amenities ADD CONSTRAINT FK_1E989866F5F4AF1 FOREIGN KEY (room_amenities_id) REFERENCES room_amenities (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review_images DROP FOREIGN KEY FK_6A69F9AA3E2E969B');
        $this->addSql('DROP TABLE delta_updates');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE review_images');
        $this->addSql('ALTER TABLE hotels_amenities DROP FOREIGN KEY FK_5FBE02973243BB18');
        $this->addSql('ALTER TABLE hotels_amenities DROP FOREIGN KEY FK_5FBE02971222A171');
        $this->addSql('ALTER TABLE hotels_amenities ADD CONSTRAINT FK_5FBE02973243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotels_amenities ADD CONSTRAINT FK_5FBE02971222A171 FOREIGN KEY (hotel_amenities_id) REFERENCES hotel_amenities (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP INDEX hotels_star_rating_search ON hotels');
        $this->addSql('ALTER TABLE hotels ADD type VARCHAR(255) NOT NULL, DROP client_rating, DROP cleanness_rating, DROP location_rating, DROP price_rating, DROP services_rating, DROP room_rating, DROP meal_rating, DROP wifi_rating, DROP hygiene_rating');
        $this->addSql('ALTER TABLE locations DROP type');
        $this->addSql('ALTER TABLE reviews ADD stars INT NOT NULL, ADD title VARCHAR(255) NOT NULL, ADD text LONGTEXT NOT NULL, DROP review_plus, DROP review_minus, DROP created_at, DROP adults, DROP children, DROP room_name, DROP nights, DROP traveller_type, DROP trip_type, DROP rating, DROP cleanness_rating, DROP location_rating, DROP price_rating, DROP services_rating, DROP room_rating, DROP meal_rating, DROP wifi_rating, DROP hygiene_rating, CHANGE author author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE rooms_amenities DROP FOREIGN KEY FK_1E98986654177093');
        $this->addSql('ALTER TABLE rooms_amenities DROP FOREIGN KEY FK_1E989866F5F4AF1');
        $this->addSql('ALTER TABLE rooms_amenities ADD CONSTRAINT FK_1E98986654177093 FOREIGN KEY (room_id) REFERENCES rooms (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rooms_amenities ADD CONSTRAINT FK_1E989866F5F4AF1 FOREIGN KEY (room_amenities_id) REFERENCES room_amenities (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
