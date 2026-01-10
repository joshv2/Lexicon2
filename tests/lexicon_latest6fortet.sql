-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 24, 2025 at 05:21 PM
-- Server version: 10.5.29-MariaDB-0+deb11u1-log
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lexicon_latest6`
--
CREATE DATABASE IF NOT EXISTS `lexicon_latest6` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lexicon_latest6`;

-- --------------------------------------------------------

--
-- Table structure for table `alphabets`
--

CREATE TABLE `alphabets` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `UTF8value` char(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alternates`
--

CREATE TABLE `alternates` (
  `id` int(10) UNSIGNED NOT NULL,
  `word_id` int(11) NOT NULL,
  `spelling` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cake_d_c_users_phinxlog`
--

CREATE TABLE `cake_d_c_users_phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `definitions`
--

CREATE TABLE `definitions` (
  `id` int(11) UNSIGNED NOT NULL,
  `word_id` int(11) NOT NULL,
  `definition` mediumtext NOT NULL,
  `definition_json` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dictionaries`
--

CREATE TABLE `dictionaries` (
  `id` int(11) UNSIGNED NOT NULL,
  `dictionary` varchar(255) NOT NULL,
  `language_id` int(11) NOT NULL,
  `top` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dictionaries_words`
--

CREATE TABLE `dictionaries_words` (
  `id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `dictionary_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subdomain` varchar(255) DEFAULT NULL,
  `i18nspec` varchar(6) DEFAULT NULL,
  `translationfile` varchar(255) DEFAULT NULL,
  `HeaderImage` varchar(255) DEFAULT NULL,
  `LogoImage` varchar(255) DEFAULT NULL,
  `AboutSec1Header` text DEFAULT NULL,
  `AboutSec1Text` text DEFAULT NULL,
  `AboutSec1Text_json` text DEFAULT NULL,
  `AboutSec2Header` text DEFAULT NULL,
  `AboutSec2Text` text DEFAULT NULL,
  `AboutSec2Text_json` text DEFAULT NULL,
  `AboutSec3Header` text DEFAULT NULL,
  `AboutSec3Text` text DEFAULT NULL,
  `AboutSec3Text_json` text DEFAULT NULL,
  `AboutSec4Header` text DEFAULT NULL,
  `AboutSec4Text` text DEFAULT NULL,
  `AboutSec4Text_json` text DEFAULT NULL,
  `NotesSec1Header` text DEFAULT NULL,
  `NotesSec1Text` text DEFAULT NULL,
  `NotesSec1Text_json` text DEFAULT NULL,
  `hasOrigins` tinyint(1) DEFAULT NULL,
  `hasRegions` tinyint(1) DEFAULT NULL,
  `hasTypes` tinyint(1) DEFAULT NULL,
  `hasDictionaries` tinyint(1) DEFAULT NULL,
  `UTFRangeStart` char(40) DEFAULT NULL,
  `UTFRangeEnd` char(40) DEFAULT NULL,
  `righttoleft` tinyint(1) DEFAULT NULL,
  `googleAnalytics` varchar(15) DEFAULT NULL,
  `googleAnalyticsOld` varchar(15) DEFAULT NULL,
  `hasTypeCategories` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `origins`
--

CREATE TABLE `origins` (
  `id` int(11) UNSIGNED NOT NULL,
  `origin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `origins_languages`
--

CREATE TABLE `origins_languages` (
  `id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `top` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `origins_words`
--

CREATE TABLE `origins_words` (
  `id` int(11) NOT NULL,
  `word_id` int(11) NOT NULL,
  `origin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pronunciations`
--

CREATE TABLE `pronunciations` (
  `id` int(10) UNSIGNED NOT NULL,
  `word_id` int(11) NOT NULL,
  `spelling` varchar(255) NOT NULL,
  `sound_file` varchar(4000) DEFAULT NULL,
  `pronunciation` varchar(4000) NOT NULL,
  `notes` varchar(4000) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `approving_user_id` char(36) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` int(9) NOT NULL,
  `region` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions_languages`
--

CREATE TABLE `regions_languages` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `top` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions_words`
--

CREATE TABLE `regions_words` (
  `id` int(11) NOT NULL,
  `word_id` int(9) NOT NULL,
  `region_id` int(9) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(4000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sentences`
--

CREATE TABLE `sentences` (
  `id` int(10) UNSIGNED NOT NULL,
  `word_id` int(11) NOT NULL,
  `sentence` mediumtext DEFAULT NULL,
  `sentence_json` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sentence_recordings`
--

CREATE TABLE `sentence_recordings` (
  `id` int(10) UNSIGNED NOT NULL,
  `sentence_id` int(11) NOT NULL,
  `sound_file` varchar(4000) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `approved` tinyint(4) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `approving_user_id` char(36) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `provider` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `token` varchar(500) NOT NULL,
  `token_secret` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `id` int(10) NOT NULL,
  `word_id` int(11) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `suggestion` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types_languages`
--

CREATE TABLE `types_languages` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `type_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types_users`
--

CREATE TABLE `types_users` (
  `id` int(11) NOT NULL,
  `user_id` char(36) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types_words`
--

CREATE TABLE `types_words` (
  `id` int(11) NOT NULL,
  `word_id` int(11) DEFAULT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_categories`
--

CREATE TABLE `type_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `activation_date` datetime DEFAULT NULL,
  `secret` varchar(32) DEFAULT NULL,
  `secret_verified` tinyint(1) DEFAULT NULL,
  `tos_date` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `is_superuser` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) DEFAULT 'user',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `additional_data` text DEFAULT NULL,
  `last_login` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE `words` (
  `id` int(10) UNSIGNED NOT NULL,
  `old_id` int(11) DEFAULT NULL,
  `spelling` varchar(255) NOT NULL,
  `etymology` mediumtext DEFAULT NULL,
  `etymology_json` longtext DEFAULT NULL,
  `notes` mediumtext DEFAULT NULL,
  `notes_json` longtext DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `words_dictionaries`
--

CREATE TABLE `words_dictionaries` (
  `word_id` int(11) NOT NULL,
  `dictionary_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alphabets`
--
ALTER TABLE `alphabets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alternates`
--
ALTER TABLE `alternates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`),
  ADD KEY `spelling` (`spelling`(250));

--
-- Indexes for table `definitions`
--
ALTER TABLE `definitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`),
  ADD KEY `definition` (`definition`(250));

--
-- Indexes for table `dictionaries`
--
ALTER TABLE `dictionaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dictionaries_words`
--
ALTER TABLE `dictionaries_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`),
  ADD KEY `dictionary_id` (`dictionary_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `origins`
--
ALTER TABLE `origins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `origins_languages`
--
ALTER TABLE `origins_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `origins_words`
--
ALTER TABLE `origins_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`),
  ADD KEY `origin_id` (`origin_id`);

--
-- Indexes for table `pronunciations`
--
ALTER TABLE `pronunciations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions_languages`
--
ALTER TABLE `regions_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions_words`
--
ALTER TABLE `regions_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`),
  ADD KEY `region_id` (`region_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sentences`
--
ALTER TABLE `sentences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `sentence_recordings`
--
ALTER TABLE `sentence_recordings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sentence_id` (`sentence_id`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types_languages`
--
ALTER TABLE `types_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types_users`
--
ALTER TABLE `types_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `types_words`
--
ALTER TABLE `types_words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `word_id` (`word_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `type_categories`
--
ALTER TABLE `type_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes` (`notes`(250)),
  ADD KEY `spelling` (`spelling`(250)),
  ADD KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alphabets`
--
ALTER TABLE `alphabets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alternates`
--
ALTER TABLE `alternates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `definitions`
--
ALTER TABLE `definitions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dictionaries`
--
ALTER TABLE `dictionaries`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dictionaries_words`
--
ALTER TABLE `dictionaries_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `origins`
--
ALTER TABLE `origins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `origins_languages`
--
ALTER TABLE `origins_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `origins_words`
--
ALTER TABLE `origins_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pronunciations`
--
ALTER TABLE `pronunciations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions_languages`
--
ALTER TABLE `regions_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions_words`
--
ALTER TABLE `regions_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sentences`
--
ALTER TABLE `sentences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sentence_recordings`
--
ALTER TABLE `sentence_recordings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types_languages`
--
ALTER TABLE `types_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types_users`
--
ALTER TABLE `types_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `types_words`
--
ALTER TABLE `types_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_categories`
--
ALTER TABLE `type_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
