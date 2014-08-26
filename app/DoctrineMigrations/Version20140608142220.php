<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140608142220 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE financial_transactions (id INT AUTO_INCREMENT NOT NULL, credit_id INT DEFAULT NULL, payment_id INT DEFAULT NULL, extended_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:extended_payment_data)', processed_amount NUMERIC(10, 5) NOT NULL, reason_code VARCHAR(100) DEFAULT NULL, reference_number VARCHAR(100) DEFAULT NULL, requested_amount NUMERIC(10, 5) NOT NULL, response_code VARCHAR(100) DEFAULT NULL, state SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, tracking_id VARCHAR(100) DEFAULT NULL, transaction_type SMALLINT NOT NULL, INDEX IDX_1353F2D9CE062FF9 (credit_id), INDEX IDX_1353F2D94C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE payments (id INT AUTO_INCREMENT NOT NULL, payment_instruction_id INT NOT NULL, approved_amount NUMERIC(10, 5) NOT NULL, approving_amount NUMERIC(10, 5) NOT NULL, credited_amount NUMERIC(10, 5) NOT NULL, crediting_amount NUMERIC(10, 5) NOT NULL, deposited_amount NUMERIC(10, 5) NOT NULL, depositing_amount NUMERIC(10, 5) NOT NULL, expiration_date DATETIME DEFAULT NULL, reversing_approved_amount NUMERIC(10, 5) NOT NULL, reversing_credited_amount NUMERIC(10, 5) NOT NULL, reversing_deposited_amount NUMERIC(10, 5) NOT NULL, state SMALLINT NOT NULL, target_amount NUMERIC(10, 5) NOT NULL, attention_required TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_65D29B328789B572 (payment_instruction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE payment_instructions (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 5) NOT NULL, approved_amount NUMERIC(10, 5) NOT NULL, approving_amount NUMERIC(10, 5) NOT NULL, created_at DATETIME NOT NULL, credited_amount NUMERIC(10, 5) NOT NULL, crediting_amount NUMERIC(10, 5) NOT NULL, currency VARCHAR(3) NOT NULL, deposited_amount NUMERIC(10, 5) NOT NULL, depositing_amount NUMERIC(10, 5) NOT NULL, extended_data LONGTEXT NOT NULL COMMENT '(DC2Type:extended_payment_data)', payment_system_name VARCHAR(100) NOT NULL, reversing_approved_amount NUMERIC(10, 5) NOT NULL, reversing_credited_amount NUMERIC(10, 5) NOT NULL, reversing_deposited_amount NUMERIC(10, 5) NOT NULL, state SMALLINT NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE credits (id INT AUTO_INCREMENT NOT NULL, payment_instruction_id INT NOT NULL, payment_id INT DEFAULT NULL, attention_required TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, credited_amount NUMERIC(10, 5) NOT NULL, crediting_amount NUMERIC(10, 5) NOT NULL, reversing_amount NUMERIC(10, 5) NOT NULL, state SMALLINT NOT NULL, target_amount NUMERIC(10, 5) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_4117D17E8789B572 (payment_instruction_id), INDEX IDX_4117D17E4C3A3BB (payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_scoreboards (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, graphics SMALLINT NOT NULL, audio SMALLINT NOT NULL, originality SMALLINT NOT NULL, fun SMALLINT NOT NULL, theme SMALLINT NOT NULL, INDEX IDX_F7C59EBCE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_teams (id INT AUTO_INCREMENT NOT NULL, leader_id INT DEFAULT NULL, compo_id INT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, name VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, INDEX IDX_FC13581D73154ED4 (leader_id), INDEX IDX_FC13581DF1454301 (compo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_teams_invitations (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, target_id INT DEFAULT NULL, compo_id INT DEFAULT NULL, type SMALLINT NOT NULL, hash VARCHAR(255) NOT NULL, INDEX IDX_910B7846296CD8AE (team_id), INDEX IDX_910B7846F624B39D (sender_id), INDEX IDX_910B7846158E0B66 (target_id), INDEX IDX_910B7846F1454301 (compo_id), UNIQUE INDEX unique_inv (team_id, sender_id, target_id, compo_id, type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos (id INT AUTO_INCREMENT NOT NULL, theme_id INT DEFAULT NULL, memberFee NUMERIC(2, 0) DEFAULT NULL, normalFee NUMERIC(2, 0) DEFAULT NULL, name VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, open TINYINT(1) NOT NULL, startAt DATETIME NOT NULL, period VARCHAR(255) NOT NULL, applicationStartAt DATETIME DEFAULT NULL, applicationEndAt DATETIME DEFAULT NULL, maxPeople SMALLINT NOT NULL, maxTeamMembers SMALLINT NOT NULL, UNIQUE INDEX UNIQ_4CC8A80B59027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_compos_juries (compo_id INT NOT NULL, contributor_id INT NOT NULL, INDEX IDX_13749B41F1454301 (compo_id), INDEX IDX_13749B417A19A357 (contributor_id), PRIMARY KEY(compo_id, contributor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_compos_sponsors (compo_id INT NOT NULL, contributor_id INT NOT NULL, INDEX IDX_47899454F1454301 (compo_id), INDEX IDX_478994547A19A357 (contributor_id), PRIMARY KEY(compo_id, contributor_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_contributors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, featured TINYINT(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_activity (id INT AUTO_INCREMENT NOT NULL, compo_id INT DEFAULT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, achievement_id INT DEFAULT NULL, media_id INT DEFAULT NULL, download_id INT DEFAULT NULL, team_id INT DEFAULT NULL, date DATETIME NOT NULL, type SMALLINT NOT NULL, content LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)', INDEX IDX_FE213525F1454301 (compo_id), INDEX IDX_FE213525A76ED395 (user_id), INDEX IDX_FE213525E48FD905 (game_id), INDEX IDX_FE213525B3EC99FE (achievement_id), INDEX IDX_FE213525EA9FDD75 (media_id), INDEX IDX_FE213525C667AEAB (download_id), INDEX IDX_FE213525296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_achievements (id INT AUTO_INCREMENT NOT NULL, type SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, granter VARCHAR(255) DEFAULT NULL, hidden TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_diversifiers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_themes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_notifications (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, announce TINYINT(1) NOT NULL, date DATETIME NOT NULL, type SMALLINT NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_notifications_users (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_62D2D0C1EF1A9D84 (notification_id), INDEX IDX_62D2D0C1A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_notifications_users_read (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EE95A1F5EF1A9D84 (notification_id), INDEX IDX_EE95A1F5A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_compos_applications (id INT AUTO_INCREMENT NOT NULL, compo_id INT DEFAULT NULL, user_id INT DEFAULT NULL, order_id INT DEFAULT NULL, date DATETIME NOT NULL, modality SMALLINT NOT NULL, nightStay TINYINT(1) NOT NULL, completed TINYINT(1) NOT NULL, INDEX IDX_AB4D5307F1454301 (compo_id), INDEX IDX_AB4D5307A76ED395 (user_id), UNIQUE INDEX UNIQ_AB4D53078D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_games (id INT AUTO_INCREMENT NOT NULL, compo_id INT DEFAULT NULL, team_id INT DEFAULT NULL, user_id INT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, name VARCHAR(255) NOT NULL, nameSlug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, likes INT NOT NULL, coins INT NOT NULL, winner SMALLINT DEFAULT NULL, mentions LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)', INDEX IDX_98394EDCF1454301 (compo_id), INDEX IDX_98394EDC296CD8AE (team_id), INDEX IDX_98394EDCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_games_diversifiers (game_id INT NOT NULL, diversifier_id INT NOT NULL, INDEX IDX_71F8125BE48FD905 (game_id), INDEX IDX_71F8125B1D170D4D (diversifier_id), PRIMARY KEY(game_id, diversifier_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_games_user_likes (game_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3382D1FFE48FD905 (game_id), INDEX IDX_3382D1FFA76ED395 (user_id), PRIMARY KEY(game_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_games_downloads (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, gamejam TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, version VARCHAR(255) DEFAULT NULL, fileUrl LONGTEXT NOT NULL, platforms LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', comment LONGTEXT DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, INDEX IDX_1337C2F7E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_games_media (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME NOT NULL, url VARCHAR(255) NOT NULL, data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', comment VARCHAR(255) DEFAULT NULL, type SMALLINT NOT NULL, INDEX IDX_1C08298FE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, registeredAt DATETIME NOT NULL, coins INT NOT NULL, nickname VARCHAR(255) DEFAULT NULL, avatarUrl LONGTEXT DEFAULT NULL, birthDate DATE DEFAULT NULL, sex TINYINT(1) DEFAULT NULL, siteUrl VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, presentation LONGTEXT DEFAULT NULL, publicProfile TINYINT(1) NOT NULL, publicEmail TINYINT(1) NOT NULL, allowCommunications TINYINT(1) NOT NULL, oauthTokens LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)', twitter VARCHAR(255) DEFAULT NULL, legacyPassword TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_7399C00492FC23A8 (username_canonical), UNIQUE INDEX UNIQ_7399C004A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_users_teams (user_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_F8811C2EA76ED395 (user_id), INDEX IDX_F8811C2E296CD8AE (team_id), PRIMARY KEY(user_id, team_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_users_orders (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, createdAt DATETIME NOT NULL, orderNumber VARCHAR(255) NOT NULL, amount NUMERIC(2, 0) DEFAULT NULL, paymentInstruction_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5C276AD9989A8203 (orderNumber), UNIQUE INDEX UNIQ_5C276AD9FD913E4D (paymentInstruction_id), INDEX IDX_5C276AD9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE gamejam_users_achievements_granted (id INT AUTO_INCREMENT NOT NULL, achievement_id INT DEFAULT NULL, user_id INT DEFAULT NULL, game_id INT DEFAULT NULL, grantedAt DATETIME NOT NULL, INDEX IDX_B251C683B3EC99FE (achievement_id), INDEX IDX_B251C683A76ED395 (user_id), INDEX IDX_B251C683E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE financial_transactions ADD CONSTRAINT FK_1353F2D9CE062FF9 FOREIGN KEY (credit_id) REFERENCES credits (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE financial_transactions ADD CONSTRAINT FK_1353F2D94C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE payments ADD CONSTRAINT FK_65D29B328789B572 FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE credits ADD CONSTRAINT FK_4117D17E8789B572 FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE credits ADD CONSTRAINT FK_4117D17E4C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_scoreboards ADD CONSTRAINT FK_F7C59EBCE48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id)");
        $this->addSql("ALTER TABLE gamejam_compos_teams ADD CONSTRAINT FK_FC13581D73154ED4 FOREIGN KEY (leader_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_compos_teams ADD CONSTRAINT FK_FC13581DF1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id)");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations ADD CONSTRAINT FK_910B7846296CD8AE FOREIGN KEY (team_id) REFERENCES gamejam_compos_teams (id)");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations ADD CONSTRAINT FK_910B7846F624B39D FOREIGN KEY (sender_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations ADD CONSTRAINT FK_910B7846158E0B66 FOREIGN KEY (target_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations ADD CONSTRAINT FK_910B7846F1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id)");
        $this->addSql("ALTER TABLE gamejam_compos ADD CONSTRAINT FK_4CC8A80B59027487 FOREIGN KEY (theme_id) REFERENCES gamejam_compos_themes (id)");
        $this->addSql("ALTER TABLE gamejam_compos_compos_juries ADD CONSTRAINT FK_13749B41F1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_compos_juries ADD CONSTRAINT FK_13749B417A19A357 FOREIGN KEY (contributor_id) REFERENCES gamejam_compos_contributors (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_compos_sponsors ADD CONSTRAINT FK_47899454F1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_compos_sponsors ADD CONSTRAINT FK_478994547A19A357 FOREIGN KEY (contributor_id) REFERENCES gamejam_compos_contributors (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525F1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id)");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525A76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525E48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id)");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525B3EC99FE FOREIGN KEY (achievement_id) REFERENCES gamejam_compos_achievements (id)");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525EA9FDD75 FOREIGN KEY (media_id) REFERENCES gamejam_games_media (id)");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525C667AEAB FOREIGN KEY (download_id) REFERENCES gamejam_games_downloads (id)");
        $this->addSql("ALTER TABLE gamejam_compos_activity ADD CONSTRAINT FK_FE213525296CD8AE FOREIGN KEY (team_id) REFERENCES gamejam_compos_teams (id)");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users ADD CONSTRAINT FK_62D2D0C1EF1A9D84 FOREIGN KEY (notification_id) REFERENCES gamejam_compos_notifications (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users ADD CONSTRAINT FK_62D2D0C1A76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users_read ADD CONSTRAINT FK_EE95A1F5EF1A9D84 FOREIGN KEY (notification_id) REFERENCES gamejam_compos_notifications (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users_read ADD CONSTRAINT FK_EE95A1F5A76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_compos_applications ADD CONSTRAINT FK_AB4D5307F1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id)");
        $this->addSql("ALTER TABLE gamejam_compos_applications ADD CONSTRAINT FK_AB4D5307A76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_compos_applications ADD CONSTRAINT FK_AB4D53078D9F6D38 FOREIGN KEY (order_id) REFERENCES gamejam_users_orders (id)");
        $this->addSql("ALTER TABLE gamejam_games ADD CONSTRAINT FK_98394EDCF1454301 FOREIGN KEY (compo_id) REFERENCES gamejam_compos (id)");
        $this->addSql("ALTER TABLE gamejam_games ADD CONSTRAINT FK_98394EDC296CD8AE FOREIGN KEY (team_id) REFERENCES gamejam_compos_teams (id)");
        $this->addSql("ALTER TABLE gamejam_games ADD CONSTRAINT FK_98394EDCA76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_games_diversifiers ADD CONSTRAINT FK_71F8125BE48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_games_diversifiers ADD CONSTRAINT FK_71F8125B1D170D4D FOREIGN KEY (diversifier_id) REFERENCES gamejam_compos_diversifiers (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_games_user_likes ADD CONSTRAINT FK_3382D1FFE48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_games_user_likes ADD CONSTRAINT FK_3382D1FFA76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_games_downloads ADD CONSTRAINT FK_1337C2F7E48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id)");
        $this->addSql("ALTER TABLE gamejam_games_media ADD CONSTRAINT FK_1C08298FE48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id)");
        $this->addSql("ALTER TABLE gamejam_users_teams ADD CONSTRAINT FK_F8811C2EA76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_users_teams ADD CONSTRAINT FK_F8811C2E296CD8AE FOREIGN KEY (team_id) REFERENCES gamejam_compos_teams (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE gamejam_users_orders ADD CONSTRAINT FK_5C276AD9FD913E4D FOREIGN KEY (paymentInstruction_id) REFERENCES payment_instructions (id)");
        $this->addSql("ALTER TABLE gamejam_users_orders ADD CONSTRAINT FK_5C276AD9A76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_users_achievements_granted ADD CONSTRAINT FK_B251C683B3EC99FE FOREIGN KEY (achievement_id) REFERENCES gamejam_compos_achievements (id)");
        $this->addSql("ALTER TABLE gamejam_users_achievements_granted ADD CONSTRAINT FK_B251C683A76ED395 FOREIGN KEY (user_id) REFERENCES gamejam_users (id)");
        $this->addSql("ALTER TABLE gamejam_users_achievements_granted ADD CONSTRAINT FK_B251C683E48FD905 FOREIGN KEY (game_id) REFERENCES gamejam_games (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE financial_transactions DROP FOREIGN KEY FK_1353F2D94C3A3BB");
        $this->addSql("ALTER TABLE credits DROP FOREIGN KEY FK_4117D17E4C3A3BB");
        $this->addSql("ALTER TABLE payments DROP FOREIGN KEY FK_65D29B328789B572");
        $this->addSql("ALTER TABLE credits DROP FOREIGN KEY FK_4117D17E8789B572");
        $this->addSql("ALTER TABLE gamejam_users_orders DROP FOREIGN KEY FK_5C276AD9FD913E4D");
        $this->addSql("ALTER TABLE financial_transactions DROP FOREIGN KEY FK_1353F2D9CE062FF9");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations DROP FOREIGN KEY FK_910B7846296CD8AE");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525296CD8AE");
        $this->addSql("ALTER TABLE gamejam_games DROP FOREIGN KEY FK_98394EDC296CD8AE");
        $this->addSql("ALTER TABLE gamejam_users_teams DROP FOREIGN KEY FK_F8811C2E296CD8AE");
        $this->addSql("ALTER TABLE gamejam_compos_teams DROP FOREIGN KEY FK_FC13581DF1454301");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations DROP FOREIGN KEY FK_910B7846F1454301");
        $this->addSql("ALTER TABLE gamejam_compos_compos_juries DROP FOREIGN KEY FK_13749B41F1454301");
        $this->addSql("ALTER TABLE gamejam_compos_compos_sponsors DROP FOREIGN KEY FK_47899454F1454301");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525F1454301");
        $this->addSql("ALTER TABLE gamejam_compos_applications DROP FOREIGN KEY FK_AB4D5307F1454301");
        $this->addSql("ALTER TABLE gamejam_games DROP FOREIGN KEY FK_98394EDCF1454301");
        $this->addSql("ALTER TABLE gamejam_compos_compos_juries DROP FOREIGN KEY FK_13749B417A19A357");
        $this->addSql("ALTER TABLE gamejam_compos_compos_sponsors DROP FOREIGN KEY FK_478994547A19A357");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525B3EC99FE");
        $this->addSql("ALTER TABLE gamejam_users_achievements_granted DROP FOREIGN KEY FK_B251C683B3EC99FE");
        $this->addSql("ALTER TABLE gamejam_games_diversifiers DROP FOREIGN KEY FK_71F8125B1D170D4D");
        $this->addSql("ALTER TABLE gamejam_compos DROP FOREIGN KEY FK_4CC8A80B59027487");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users DROP FOREIGN KEY FK_62D2D0C1EF1A9D84");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users_read DROP FOREIGN KEY FK_EE95A1F5EF1A9D84");
        $this->addSql("ALTER TABLE gamejam_compos_scoreboards DROP FOREIGN KEY FK_F7C59EBCE48FD905");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525E48FD905");
        $this->addSql("ALTER TABLE gamejam_games_diversifiers DROP FOREIGN KEY FK_71F8125BE48FD905");
        $this->addSql("ALTER TABLE gamejam_games_user_likes DROP FOREIGN KEY FK_3382D1FFE48FD905");
        $this->addSql("ALTER TABLE gamejam_games_downloads DROP FOREIGN KEY FK_1337C2F7E48FD905");
        $this->addSql("ALTER TABLE gamejam_games_media DROP FOREIGN KEY FK_1C08298FE48FD905");
        $this->addSql("ALTER TABLE gamejam_users_achievements_granted DROP FOREIGN KEY FK_B251C683E48FD905");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525C667AEAB");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525EA9FDD75");
        $this->addSql("ALTER TABLE gamejam_compos_teams DROP FOREIGN KEY FK_FC13581D73154ED4");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations DROP FOREIGN KEY FK_910B7846F624B39D");
        $this->addSql("ALTER TABLE gamejam_compos_teams_invitations DROP FOREIGN KEY FK_910B7846158E0B66");
        $this->addSql("ALTER TABLE gamejam_compos_activity DROP FOREIGN KEY FK_FE213525A76ED395");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users DROP FOREIGN KEY FK_62D2D0C1A76ED395");
        $this->addSql("ALTER TABLE gamejam_compos_notifications_users_read DROP FOREIGN KEY FK_EE95A1F5A76ED395");
        $this->addSql("ALTER TABLE gamejam_compos_applications DROP FOREIGN KEY FK_AB4D5307A76ED395");
        $this->addSql("ALTER TABLE gamejam_games DROP FOREIGN KEY FK_98394EDCA76ED395");
        $this->addSql("ALTER TABLE gamejam_games_user_likes DROP FOREIGN KEY FK_3382D1FFA76ED395");
        $this->addSql("ALTER TABLE gamejam_users_teams DROP FOREIGN KEY FK_F8811C2EA76ED395");
        $this->addSql("ALTER TABLE gamejam_users_orders DROP FOREIGN KEY FK_5C276AD9A76ED395");
        $this->addSql("ALTER TABLE gamejam_users_achievements_granted DROP FOREIGN KEY FK_B251C683A76ED395");
        $this->addSql("ALTER TABLE gamejam_compos_applications DROP FOREIGN KEY FK_AB4D53078D9F6D38");
        $this->addSql("DROP TABLE financial_transactions");
        $this->addSql("DROP TABLE payments");
        $this->addSql("DROP TABLE payment_instructions");
        $this->addSql("DROP TABLE credits");
        $this->addSql("DROP TABLE gamejam_compos_scoreboards");
        $this->addSql("DROP TABLE gamejam_compos_teams");
        $this->addSql("DROP TABLE gamejam_compos_teams_invitations");
        $this->addSql("DROP TABLE gamejam_compos");
        $this->addSql("DROP TABLE gamejam_compos_compos_juries");
        $this->addSql("DROP TABLE gamejam_compos_compos_sponsors");
        $this->addSql("DROP TABLE gamejam_compos_contributors");
        $this->addSql("DROP TABLE gamejam_compos_activity");
        $this->addSql("DROP TABLE gamejam_compos_achievements");
        $this->addSql("DROP TABLE gamejam_compos_diversifiers");
        $this->addSql("DROP TABLE gamejam_compos_themes");
        $this->addSql("DROP TABLE gamejam_compos_notifications");
        $this->addSql("DROP TABLE gamejam_compos_notifications_users");
        $this->addSql("DROP TABLE gamejam_compos_notifications_users_read");
        $this->addSql("DROP TABLE gamejam_compos_applications");
        $this->addSql("DROP TABLE gamejam_games");
        $this->addSql("DROP TABLE gamejam_games_diversifiers");
        $this->addSql("DROP TABLE gamejam_games_user_likes");
        $this->addSql("DROP TABLE gamejam_games_downloads");
        $this->addSql("DROP TABLE gamejam_games_media");
        $this->addSql("DROP TABLE gamejam_users");
        $this->addSql("DROP TABLE gamejam_users_teams");
        $this->addSql("DROP TABLE gamejam_users_orders");
        $this->addSql("DROP TABLE gamejam_users_achievements_granted");
    }
}
