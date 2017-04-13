/**
 * Media from FTP
 * 
 * @package    Media from FTP
 * @subpackage jquery.mediafromftp.js
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
jQuery(function(){

	/* Responsive Tabs */
	jQuery('#mediafromftp-settings-tabs').responsiveTabs({
		startCollapsed: 'accordion'
	});

	/* Spiner */
	window.addEventListener( "load", function(){
		jQuery("#mediafromftp-loading").delay(2000).fadeOut();
		jQuery("#mediafromftp-loading-container").delay(2000).fadeIn();
	}, false );

	/* Control of the Enter key */
	jQuery('input[type!="submit"][type!="button"]').keypress(function(e){
		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
			return false;
		}else{
			return true;
		}
	});

	/* Date Time Picker */
	jQuery(':input[id^=datetimepicker-mediafromftp]').datetimepicker({format:'Y-m-d H:i'});

	/* Ajax for register */
	var mediafromftp_defer = jQuery.Deferred().resolve();
	jQuery('#mediafromftp_ajax_update').submit(function(){

		var new_url = new Array();
		var new_datetime = new Array();
		var form_names = jQuery("#mediafromftp_ajax_update").serializeArray();
		jQuery.each(form_names, function(i) {
			if ( form_names[i].name.indexOf("[url]") != -1 ) {
				new_url[i] = form_names[i].value;
				new_datetime[i] = form_names[i+1].value;
			}
		});
		var new_url = jQuery.grep(new_url, function(e){return e;});
		var new_datetime = jQuery.grep(new_datetime, function(e){return e;});

		jQuery("#mediafromftp-loading-container").empty();
		jQuery("#screen-options-wrap").remove();
		jQuery(".updated").remove();
		jQuery(".error").remove();

		jQuery("#mediafromftp-loading-container").append("<div id=\"mediafromftp-update-progress\"><progress value=\"0\" max=\"100\"></progress> 0%</div><button type=\"button\" id=\"mediafromftp_ajax_stop\">Stop</button>");
		jQuery("#mediafromftp-loading-container").append("<div id=\"mediafromftp-update-result\"></div>");

		var update_continue = true;
		// Stop button
		jQuery("#mediafromftp_ajax_stop").click(function() {
			update_continue = false;
			jQuery("#mediafromftp_ajax_stop").text("Stopping now..");
		});

		var count = 0;
		var success_count = 0;
		var success_update = "";
		var error_count = 0;
		var error_update = "";
		jQuery.each(new_url, function(i){
			var j = i ;
			mediafromftp_defer = mediafromftp_defer.then(function(){
				if ( update_continue == true ) {
					return jQuery.ajax({
						type: 'POST',
						url: MEDIAFROMFTPUPDATE.ajax_url,
						data: {
							'action': MEDIAFROMFTPUPDATE.action,
							'nonce': MEDIAFROMFTPUPDATE.nonce,
							'maxcount': new_url.length,
							'new_url': new_url[j],
							'new_datetime': new_datetime[j]
						}
					}).then(
						function(result){
							count += 1; 
							success_count += 1;
							jQuery("#mediafromftp-update-result").append(result);
							jQuery("#mediafromftp-update-progress").empty();
							var progressper = Math.round((count/new_url.length)*100);
							jQuery("#mediafromftp-update-progress").append("<progress value=\"" + progressper + "\" max=\"100\"></progress> " + progressper + "%");
							if ( count == new_url.length || update_continue == false ) {
								jQuery.ajax({
									type: 'POST',
									url: MEDIAFROMFTPUPDATE.ajax_url,
									data: {
										'action': 'mediafromftp_message',
										'error_count': error_count,
										'error_update': error_update,
										'success_count': success_count
									}
								}).done(function(result){
									jQuery("#mediafromftp-update-progress").empty();
									jQuery("#mediafromftp-update-progress").append(result);
									jQuery("#mediafromftp_ajax_stop").hide();
								});
							}
						},
						function( jqXHR, textStatus, errorThrown){
							error_count += 1; 
							error_update += "<div>" + new_url[j] + ": error -> status " + jqXHR.status + ' ' + textStatus.status + "</div>";
						}
					);
				}
			});
		});
		return false;
	});

	/* Ajax for import */
	var medialibraryimport_defer = jQuery.Deferred().resolve();
	jQuery('#medialibraryimport_ajax_update').submit(function(){

		jQuery("#medialibraryimport-loading-container").empty();
		jQuery(".updated").remove();
		jQuery(".error").remove();

		jQuery("#medialibraryimport-loading-container").append("<div id=\"medialibraryimport-update-progress\"><progress value=\"0\" max=\"100\"></progress> 0%</div><button type=\"button\" id=\"medialibraryimport_ajax_stop\">Stop</button>");
		jQuery("#medialibraryimport-loading-container").append("<div id=\"medialibraryimport-update-result\"></div>");
		var update_continue = true;
		// Stop button
		jQuery("#medialibraryimport_ajax_stop").click(function() {
			update_continue = false;
			jQuery("#medialibraryimport_ajax_stop").text("Stopping now..");
		});

		var count = 0;
		var success_count = 0;
		var db_success_count = 0;
		var error_count = 0;
		var error_update = "";

		jQuery.each(medialibraryimport_file, function(i){
			var j = i;
			medialibraryimport_defer = medialibraryimport_defer.then(function(){
				if ( update_continue == true ) {
					return jQuery.ajax({
						type: 'POST',
						cache : false,
						url: MEDIAFROMFTPIMPORT.ajax_url,
						data: {
							'action': MEDIAFROMFTPIMPORT.action,
							'nonce': MEDIAFROMFTPIMPORT.nonce,
							'maxcount': medialibraryimport_maxcount,
							'file': medialibraryimport_file[j],
							'db_array': medialibraryimport_db_array[j],
							'db_wp_attachment_metadata': medialibraryimport_db_wp_attachment_metadata[j],
							'db_thumbnail_id': medialibraryimport_db_thumbnail_id[j],
							'db_cover_hash': medialibraryimport_db_cover_hash[j],
							'db_wp_attachment_image_alt': medialibraryimport_db_wp_attachment_image_alt[j]
						}
					}).then(
						function(result){
							count += 1;
							var update_view = result.split(",");
							if( update_view[0] == "success" ) {
								success_count += 1;
							} else if( update_view[0] == "success_db" ) {
								success_count += 1;
								db_success_count += 1;
							} else {
								error_count += 1;
								error_update += update_view[0];
							}
							jQuery("#medialibraryimport-update-progress").empty();
							var progressper = Math.round((count/medialibraryimport_maxcount)*100);
							jQuery("#medialibraryimport-update-progress").append("<progress value=\"" + progressper + "\" max=\"100\"></progress> " + progressper + "%");
							jQuery("#medialibraryimport-update-result").append(update_view[1]);
							if ( count == medialibraryimport_maxcount || update_continue == false ) {
								jQuery.ajax({
									type: 'POST',
									url: MEDIAFROMFTPIMPORT.ajax_url,
									data: {
										'action': 'mediafromftp_medialibraryimport_message',
										'error_count': error_count,
										'error_update': error_update,
										'success_count': success_count,
										'db_success_count': db_success_count
									}
								}).done(function(result){
									jQuery("#medialibraryimport-update-progress").empty();
									jQuery("#medialibraryimport-update-progress").append(result);
									jQuery("#medialibraryimport_ajax_stop").hide();
								});
							}
						}
					).fail(
						function( jqXHR, textStatus, errorThrown){
							error_count += 1;
							error_update += "<div>" + medialibraryimport_file[j] + ": error -> status " + jqXHR.status + ' ' + textStatus.status + "</div>";
						}
					);
				}
			});
		});
		return false;
	});

});

