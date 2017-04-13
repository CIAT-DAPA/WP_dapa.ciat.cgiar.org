-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-04-2017 a las 12:30:08
-- Versión del servidor: 10.1.19-MariaDB
-- Versión de PHP: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dapabd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_blc_filters`
--

CREATE TABLE `wp_blc_filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `params` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_blc_instances`
--

CREATE TABLE `wp_blc_instances` (
  `instance_id` int(10) UNSIGNED NOT NULL,
  `link_id` int(10) UNSIGNED NOT NULL,
  `container_id` int(10) UNSIGNED NOT NULL,
  `container_type` varchar(40) NOT NULL DEFAULT 'post',
  `link_text` text NOT NULL,
  `parser_type` varchar(40) NOT NULL DEFAULT 'link',
  `container_field` varchar(250) NOT NULL DEFAULT '',
  `link_context` varchar(250) NOT NULL DEFAULT '',
  `raw_url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_blc_links`
--

CREATE TABLE `wp_blc_links` (
  `link_id` int(20) UNSIGNED NOT NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `first_failure` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_check` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_success` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_check_attempt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `check_count` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `final_url` text CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `redirect_count` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  `http_code` smallint(6) NOT NULL DEFAULT '0',
  `status_code` varchar(100) DEFAULT '',
  `status_text` varchar(250) DEFAULT '',
  `request_duration` float NOT NULL DEFAULT '0',
  `timeout` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `broken` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `may_recheck` tinyint(1) NOT NULL DEFAULT '1',
  `being_checked` tinyint(1) NOT NULL DEFAULT '0',
  `result_hash` varchar(200) NOT NULL DEFAULT '',
  `false_positive` tinyint(1) NOT NULL DEFAULT '0',
  `dismissed` tinyint(1) NOT NULL DEFAULT '0',
  `warning` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_blc_synch`
--

CREATE TABLE `wp_blc_synch` (
  `container_id` int(20) UNSIGNED NOT NULL,
  `container_type` varchar(40) NOT NULL,
  `synched` tinyint(2) UNSIGNED NOT NULL,
  `last_synch` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_captcha_bank_block_range_ip`
--

CREATE TABLE `wp_captcha_bank_block_range_ip` (
  `id` int(10) UNSIGNED NOT NULL,
  `block_start_range` varchar(20) NOT NULL,
  `block_end_range` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_captcha_bank_block_single_ip`
--

CREATE TABLE `wp_captcha_bank_block_single_ip` (
  `id` int(10) UNSIGNED NOT NULL,
  `block_ip_address` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_captcha_bank_licensing`
--

CREATE TABLE `wp_captcha_bank_licensing` (
  `licensing_id` int(10) UNSIGNED NOT NULL,
  `version` varchar(10) NOT NULL,
  `type` varchar(100) NOT NULL,
  `url` text NOT NULL,
  `api_key` text,
  `order_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_captcha_bank_login_log`
--

CREATE TABLE `wp_captcha_bank_login_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `geo_location` varchar(200) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `date_time` datetime DEFAULT NULL,
  `captcha_status` int(1) NOT NULL,
  `block_ip` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_captcha_bank_plugin_settings`
--

CREATE TABLE `wp_captcha_bank_plugin_settings` (
  `plugin_settings_id` int(10) UNSIGNED NOT NULL,
  `plugin_settings_key` text NOT NULL,
  `plugin_settings_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_captcha_bank_settings`
--

CREATE TABLE `wp_captcha_bank_settings` (
  `settings_id` int(10) UNSIGNED NOT NULL,
  `settings_key` varchar(200) NOT NULL,
  `settings_value` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_block_range_ip`
--

CREATE TABLE `wp_cleanup_optimizer_block_range_ip` (
  `id` int(10) UNSIGNED NOT NULL,
  `block_start_range` varchar(20) NOT NULL,
  `block_end_range` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_block_single_ip`
--

CREATE TABLE `wp_cleanup_optimizer_block_single_ip` (
  `id` int(10) UNSIGNED NOT NULL,
  `block_ip_address` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_db_scheduler`
--

CREATE TABLE `wp_cleanup_optimizer_db_scheduler` (
  `scheduler_id` int(10) UNSIGNED NOT NULL,
  `db_optimizer` text,
  `start_date` date DEFAULT NULL,
  `schedule_type` varchar(100) DEFAULT NULL,
  `cron_name` varchar(100) DEFAULT NULL,
  `scheduler_action` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_licensing`
--

CREATE TABLE `wp_cleanup_optimizer_licensing` (
  `licensing_id` int(10) UNSIGNED NOT NULL,
  `version` varchar(10) NOT NULL,
  `type` varchar(100) NOT NULL,
  `url` text NOT NULL,
  `api_key` text NOT NULL,
  `order_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_login_log`
--

CREATE TABLE `wp_cleanup_optimizer_login_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `geo_location` varchar(200) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `date_time` datetime DEFAULT NULL,
  `login_status` int(1) NOT NULL,
  `block_ip` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_plugin_settings`
--

CREATE TABLE `wp_cleanup_optimizer_plugin_settings` (
  `plugin_settings_id` int(10) UNSIGNED NOT NULL,
  `plugin_settings_key` varchar(200) NOT NULL,
  `plugin_settings_value` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_cleanup_optimizer_wp_scheduler`
--

CREATE TABLE `wp_cleanup_optimizer_wp_scheduler` (
  `scheduler_id` int(10) UNSIGNED NOT NULL,
  `wp_schedule` varchar(1000) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `schedule_type` varchar(100) DEFAULT NULL,
  `cron_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_commentmeta`
--

CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_comments`
--

CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) UNSIGNED NOT NULL,
  `comment_post_ID` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_fileaway_downloads`
--

CREATE TABLE `wp_fileaway_downloads` (
  `id` int(11) NOT NULL,
  `timestamp` varchar(255) DEFAULT NULL,
  `file` varchar(1000) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `notified` bit(1) DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_fileaway_metadata`
--

CREATE TABLE `wp_fileaway_metadata` (
  `id` int(11) NOT NULL,
  `file` varchar(1000) DEFAULT NULL,
  `metadata` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_filemeta`
--

CREATE TABLE `wp_filemeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_links`
--

CREATE TABLE `wp_links` (
  `link_id` bigint(20) UNSIGNED NOT NULL,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  `link_order` int(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_options`
--

CREATE TABLE `wp_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) DEFAULT NULL,
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_postmeta`
--

CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_posts`
--

CREATE TABLE `wp_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(255) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_termmeta`
--

CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_terms`
--

CREATE TABLE `wp_terms` (
  `term_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  `term_order` int(4) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_term_relationships`
--

CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_term_taxonomy`
--

CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL,
  `term_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_usermeta`
--

CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_users`
--

CREATE TABLE `wp_users` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_wfu_log`
--

CREATE TABLE `wp_wfu_log` (
  `idlog` mediumint(9) NOT NULL,
  `userid` mediumint(9) NOT NULL,
  `uploaduserid` mediumint(9) NOT NULL,
  `filepath` text NOT NULL,
  `filehash` varchar(100) NOT NULL,
  `filesize` bigint(20) NOT NULL,
  `uploadid` varchar(20) NOT NULL,
  `pageid` mediumint(9) DEFAULT NULL,
  `sid` varchar(10) DEFAULT NULL,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL,
  `action` varchar(20) NOT NULL,
  `linkedto` mediumint(9) DEFAULT NULL,
  `uploadtime` bigint(20) DEFAULT NULL,
  `sessionid` varchar(40) DEFAULT NULL,
  `blogid` mediumint(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_wfu_userdata`
--

CREATE TABLE `wp_wfu_userdata` (
  `iduserdata` mediumint(9) NOT NULL,
  `uploadid` varchar(20) NOT NULL,
  `property` varchar(100) NOT NULL,
  `propkey` mediumint(9) NOT NULL,
  `propvalue` text,
  `date_from` datetime DEFAULT NULL,
  `date_to` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wp_wpmm_subscribers`
--

CREATE TABLE `wp_wpmm_subscribers` (
  `id_subscriber` bigint(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `insert_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `wp_blc_filters`
--
ALTER TABLE `wp_blc_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_blc_instances`
--
ALTER TABLE `wp_blc_instances`
  ADD PRIMARY KEY (`instance_id`),
  ADD KEY `link_id` (`link_id`),
  ADD KEY `source_id` (`container_type`,`container_id`),
  ADD KEY `parser_type` (`parser_type`);

--
-- Indices de la tabla `wp_blc_links`
--
ALTER TABLE `wp_blc_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `url` (`url`(150)),
  ADD KEY `final_url` (`final_url`(150)),
  ADD KEY `http_code` (`http_code`),
  ADD KEY `broken` (`broken`);

--
-- Indices de la tabla `wp_blc_synch`
--
ALTER TABLE `wp_blc_synch`
  ADD PRIMARY KEY (`container_type`,`container_id`),
  ADD KEY `synched` (`synched`);

--
-- Indices de la tabla `wp_captcha_bank_block_range_ip`
--
ALTER TABLE `wp_captcha_bank_block_range_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_captcha_bank_block_single_ip`
--
ALTER TABLE `wp_captcha_bank_block_single_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_captcha_bank_licensing`
--
ALTER TABLE `wp_captcha_bank_licensing`
  ADD PRIMARY KEY (`licensing_id`);

--
-- Indices de la tabla `wp_captcha_bank_login_log`
--
ALTER TABLE `wp_captcha_bank_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_captcha_bank_plugin_settings`
--
ALTER TABLE `wp_captcha_bank_plugin_settings`
  ADD PRIMARY KEY (`plugin_settings_id`);

--
-- Indices de la tabla `wp_captcha_bank_settings`
--
ALTER TABLE `wp_captcha_bank_settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_block_range_ip`
--
ALTER TABLE `wp_cleanup_optimizer_block_range_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_block_single_ip`
--
ALTER TABLE `wp_cleanup_optimizer_block_single_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_db_scheduler`
--
ALTER TABLE `wp_cleanup_optimizer_db_scheduler`
  ADD PRIMARY KEY (`scheduler_id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_licensing`
--
ALTER TABLE `wp_cleanup_optimizer_licensing`
  ADD PRIMARY KEY (`licensing_id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_login_log`
--
ALTER TABLE `wp_cleanup_optimizer_login_log`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_plugin_settings`
--
ALTER TABLE `wp_cleanup_optimizer_plugin_settings`
  ADD PRIMARY KEY (`plugin_settings_id`);

--
-- Indices de la tabla `wp_cleanup_optimizer_wp_scheduler`
--
ALTER TABLE `wp_cleanup_optimizer_wp_scheduler`
  ADD PRIMARY KEY (`scheduler_id`);

--
-- Indices de la tabla `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wp_comments`
--
ALTER TABLE `wp_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10));

--
-- Indices de la tabla `wp_fileaway_downloads`
--
ALTER TABLE `wp_fileaway_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indices de la tabla `wp_fileaway_metadata`
--
ALTER TABLE `wp_fileaway_metadata`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wp_filemeta`
--
ALTER TABLE `wp_filemeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `meta_key` (`meta_key`);

--
-- Indices de la tabla `wp_links`
--
ALTER TABLE `wp_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Indices de la tabla `wp_options`
--
ALTER TABLE `wp_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`);

--
-- Indices de la tabla `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wp_posts`
--
ALTER TABLE `wp_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`),
  ADD KEY `post_name` (`post_name`(191));

--
-- Indices de la tabla `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wp_terms`
--
ALTER TABLE `wp_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Indices de la tabla `wp_term_relationships`
--
ALTER TABLE `wp_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Indices de la tabla `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Indices de la tabla `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indices de la tabla `wp_users`
--
ALTER TABLE `wp_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Indices de la tabla `wp_wfu_log`
--
ALTER TABLE `wp_wfu_log`
  ADD PRIMARY KEY (`idlog`);

--
-- Indices de la tabla `wp_wfu_userdata`
--
ALTER TABLE `wp_wfu_userdata`
  ADD PRIMARY KEY (`iduserdata`);

--
-- Indices de la tabla `wp_wpmm_subscribers`
--
ALTER TABLE `wp_wpmm_subscribers`
  ADD PRIMARY KEY (`id_subscriber`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `wp_blc_filters`
--
ALTER TABLE `wp_blc_filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_blc_instances`
--
ALTER TABLE `wp_blc_instances`
  MODIFY `instance_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_blc_links`
--
ALTER TABLE `wp_blc_links`
  MODIFY `link_id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_captcha_bank_block_range_ip`
--
ALTER TABLE `wp_captcha_bank_block_range_ip`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_captcha_bank_block_single_ip`
--
ALTER TABLE `wp_captcha_bank_block_single_ip`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_captcha_bank_licensing`
--
ALTER TABLE `wp_captcha_bank_licensing`
  MODIFY `licensing_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_captcha_bank_login_log`
--
ALTER TABLE `wp_captcha_bank_login_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_captcha_bank_plugin_settings`
--
ALTER TABLE `wp_captcha_bank_plugin_settings`
  MODIFY `plugin_settings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_captcha_bank_settings`
--
ALTER TABLE `wp_captcha_bank_settings`
  MODIFY `settings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_block_range_ip`
--
ALTER TABLE `wp_cleanup_optimizer_block_range_ip`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_block_single_ip`
--
ALTER TABLE `wp_cleanup_optimizer_block_single_ip`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_db_scheduler`
--
ALTER TABLE `wp_cleanup_optimizer_db_scheduler`
  MODIFY `scheduler_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_licensing`
--
ALTER TABLE `wp_cleanup_optimizer_licensing`
  MODIFY `licensing_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_login_log`
--
ALTER TABLE `wp_cleanup_optimizer_login_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_plugin_settings`
--
ALTER TABLE `wp_cleanup_optimizer_plugin_settings`
  MODIFY `plugin_settings_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_cleanup_optimizer_wp_scheduler`
--
ALTER TABLE `wp_cleanup_optimizer_wp_scheduler`
  MODIFY `scheduler_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_comments`
--
ALTER TABLE `wp_comments`
  MODIFY `comment_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_fileaway_downloads`
--
ALTER TABLE `wp_fileaway_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_fileaway_metadata`
--
ALTER TABLE `wp_fileaway_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_filemeta`
--
ALTER TABLE `wp_filemeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_links`
--
ALTER TABLE `wp_links`
  MODIFY `link_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_options`
--
ALTER TABLE `wp_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_posts`
--
ALTER TABLE `wp_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  MODIFY `meta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_terms`
--
ALTER TABLE `wp_terms`
  MODIFY `term_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  MODIFY `umeta_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_users`
--
ALTER TABLE `wp_users`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_wfu_log`
--
ALTER TABLE `wp_wfu_log`
  MODIFY `idlog` mediumint(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_wfu_userdata`
--
ALTER TABLE `wp_wfu_userdata`
  MODIFY `iduserdata` mediumint(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `wp_wpmm_subscribers`
--
ALTER TABLE `wp_wpmm_subscribers`
  MODIFY `id_subscriber` bigint(20) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
