<?php
/**
 * Media from FTP
 * 
 * @package    Media from FTP
 * @subpackage mediafromftpcmd.php
 *
 * This program, run the Media from FTP on the command line.
 *
/*  Copyright (c) 2013- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 * command line argument list
 *
 * -s Search directory
 *    example -s wp-content/uploads
 * -d Date time settings (new, server, exif)
 *    example -d exif
 * -e Exclude file (Regular expression is possible.)
 *    example -e "(.ktai.)|(.backwpup_log.)|(.ps_auto_sitemap.)|.php|.js"
 * -t File type (all, image, audio, video, document, spreadsheet, interactive, text, archive, code)
 * 	  example -t image
 * -x File extension
 * 	  example -x jpg
 * -p Limit number of update files
 * 	  example -p 10
 * -f Limit number of search files
 * 	  example -f 100
 * -c Exif tags for registering in the caption
 *    example -c "%title% %credit% %camera% %caption% %created_timestamp% %copyright% %aperture% %shutter_speed% %iso% %focal_length%"
 *
 * If the argument is empty, use the set value of the management screen.
 *
 * command line switch
 *
 * -h Hides the display of the log.
 * 	  example -h
 * -g Create log to database.
 * 	  example -g
 * -m If you want to search for filename that contains such -0x0. It is low speed.
 * 	  example -m
 *
*/
// For your environment, please rewrite.
$_SERVER = array(
"HTTP_HOST" => "localhost.localdomain",
"SERVER_NAME" => "localhost.localdomain",
"REQUEST_URI" => "",
"REQUEST_METHOD" => "GET",
"HTTP_USER_AGENT" => "mediafromftp"
            );
require_once('D:/SkyDrive/www/top/wordpress/wp-load.php');
// For your environment, please rewrite.

	require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpCron.php' );
	$mediafromftpcron = new MediaFromFtpCron();
	$mediafromftpcron->CronDo('mediafromftp_settings'); // For your environment, please rewrite.
	unset($mediafromftpcron);

?>