-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           10.4.11-MariaDB - mariadb.org binary distribution
-- SE du serveur:                Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour pepiteg368
CREATE DATABASE IF NOT EXISTS `pepiteg368` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci */;
USE `pepiteg368`;

-- Listage de la structure de la table pepiteg368. mod75_commentmeta
CREATE TABLE IF NOT EXISTS `mod75_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_comments
CREATE TABLE IF NOT EXISTS `mod75_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT 0,
  `comment_author` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_fontsampler_fonts
CREATE TABLE IF NOT EXISTS `mod75_fontsampler_fonts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `woff` int(11) unsigned DEFAULT NULL,
  `woff2` int(11) unsigned DEFAULT NULL,
  `eot` int(11) unsigned DEFAULT NULL,
  `ttf` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_fontsampler_sets
CREATE TABLE IF NOT EXISTS `mod75_fontsampler_sets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `initial_font` int(10) unsigned DEFAULT NULL,
  `use_defaults` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_fontsampler_sets_x_fonts
CREATE TABLE IF NOT EXISTS `mod75_fontsampler_sets_x_fonts` (
  `set_id` int(11) unsigned NOT NULL,
  `font_id` int(11) unsigned NOT NULL,
  `order` smallint(5) unsigned NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_fontsampler_settings
CREATE TABLE IF NOT EXISTS `mod75_fontsampler_settings` (
  `settings_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `is_default` tinyint(1) unsigned DEFAULT 0,
  `set_id` tinyint(1) unsigned DEFAULT NULL,
  `initial` text DEFAULT NULL,
  `is_ltr` tinyint(1) DEFAULT 1,
  `ui_order` varchar(255) DEFAULT NULL,
  `ui_columns` tinyint(1) DEFAULT NULL,
  `fontsize` tinyint(1) unsigned DEFAULT NULL,
  `letterspacing` tinyint(1) unsigned DEFAULT NULL,
  `lineheight` tinyint(1) unsigned DEFAULT NULL,
  `sampletexts` tinyint(1) unsigned DEFAULT NULL,
  `alignment` tinyint(1) unsigned DEFAULT NULL,
  `invert` tinyint(1) unsigned DEFAULT NULL,
  `multiline` tinyint(1) unsigned DEFAULT NULL,
  `opentype` tinyint(1) unsigned DEFAULT NULL,
  `fontpicker` tinyint(1) unsigned DEFAULT NULL,
  `buy` varchar(255) DEFAULT NULL,
  `specimen` varchar(255) DEFAULT NULL,
  `buy_label` varchar(255) DEFAULT NULL,
  `buy_image` int(11) unsigned DEFAULT NULL,
  `buy_url` varchar(255) DEFAULT NULL,
  `buy_type` varchar(5) DEFAULT 'label',
  `buy_target` varchar(10) DEFAULT NULL,
  `specimen_label` varchar(255) DEFAULT NULL,
  `specimen_image` int(11) unsigned DEFAULT NULL,
  `specimen_url` varchar(255) DEFAULT NULL,
  `specimen_type` varchar(5) DEFAULT 'label',
  `specimen_target` varchar(10) DEFAULT NULL,
  `fontsize_label` varchar(50) DEFAULT NULL,
  `fontsize_initial` smallint(5) unsigned DEFAULT NULL,
  `fontsize_min` smallint(5) unsigned DEFAULT NULL,
  `fontsize_max` smallint(5) unsigned DEFAULT NULL,
  `fontsize_unit` varchar(50) DEFAULT NULL,
  `letterspacing_label` varchar(50) DEFAULT NULL,
  `letterspacing_initial` tinyint(5) DEFAULT NULL,
  `letterspacing_min` tinyint(3) DEFAULT NULL,
  `letterspacing_max` tinyint(3) DEFAULT NULL,
  `letterspacing_unit` varchar(50) DEFAULT NULL,
  `lineheight_label` varchar(50) DEFAULT NULL,
  `lineheight_initial` smallint(5) DEFAULT NULL,
  `lineheight_min` smallint(5) DEFAULT NULL,
  `lineheight_max` smallint(5) DEFAULT NULL,
  `lineheight_unit` varchar(50) DEFAULT NULL,
  `alignment_initial` varchar(50) DEFAULT NULL,
  `sample_texts` text DEFAULT NULL,
  `sample_texts_default_option` varchar(255) DEFAULT NULL,
  `locl` tinyint(1) unsigned DEFAULT NULL,
  `locl_options` text DEFAULT NULL,
  `locl_default_option` varchar(255) DEFAULT NULL,
  `notdef` smallint(5) DEFAULT NULL,
  `css_color_text` tinytext DEFAULT NULL,
  `css_color_background` tinytext DEFAULT NULL,
  `css_color_label` tinytext DEFAULT NULL,
  `css_value_size_label` tinytext DEFAULT NULL,
  `css_value_fontfamily_label` tinytext DEFAULT NULL,
  `css_value_lineheight_label` tinytext DEFAULT NULL,
  `css_color_button_background` tinytext DEFAULT NULL,
  `css_color_button_background_inactive` tinytext DEFAULT NULL,
  `css_color_highlight` tinytext DEFAULT NULL,
  `css_color_highlight_hover` tinytext DEFAULT NULL,
  `css_color_line` tinytext DEFAULT NULL,
  `css_color_handle` tinytext DEFAULT NULL,
  `css_value_column_gutter` tinytext DEFAULT NULL,
  `css_value_row_height` tinytext DEFAULT NULL,
  `css_value_row_gutter` tinytext DEFAULT NULL,
  `css_color_notdef` tinytext DEFAULT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_links
CREATE TABLE IF NOT EXISTS `mod75_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_options
CREATE TABLE IF NOT EXISTS `mod75_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=5959 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_postmeta
CREATE TABLE IF NOT EXISTS `mod75_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=693 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_posts
CREATE TABLE IF NOT EXISTS `mod75_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `guid` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_termmeta
CREATE TABLE IF NOT EXISTS `mod75_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_terms
CREATE TABLE IF NOT EXISTS `mod75_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_term_relationships
CREATE TABLE IF NOT EXISTS `mod75_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_term_taxonomy
CREATE TABLE IF NOT EXISTS `mod75_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_usermeta
CREATE TABLE IF NOT EXISTS `mod75_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de la table pepiteg368. mod75_users
CREATE TABLE IF NOT EXISTS `mod75_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Les données exportées n'étaient pas sélectionnées.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
