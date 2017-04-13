var slickr_flickr_slideshow_timer;
var slickr_flickr_slideshow_timer_on = false;

function  slickr_flickr_next_slide(obj) {
    var j = jQuery(obj);
    var $active = j.children('div.active');
    if ( $active.length == 0 ) $active = j.children('div:last');
    var $next =  $active.next().length ? $active.next() : j.children('div:first');

    $active.addClass('last-active');
    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 500, function() {
            $active.removeClass('active last-active');
        });
}

function slickr_flickr_next_slides() {
   jQuery('.slickr-flickr-slideshow').each(function(index){
        slickr_flickr_next_slide(jQuery(this));
   });
}

function  slickr_flickr_get_slideshow_delay() {
   var mindelay = 0;
   jQuery('.slickr-flickr-slideshow').each(function(index){

    delay = jQuery(this).data('delay');
    if ((!(delay == undefined)) && ((mindelay == 0) || (delay < mindelay))) mindelay = delay;
    });
   return mindelay;
}

function slickr_flickr_toggle_slideshows() {
   if (slickr_flickr_slideshow_timer_on)
       slickr_flickr_stop_slideshows();
   else
       slickr_flickr_start_slideshows();
}

function slickr_flickr_stop_slideshows() {
    clearTimeout(slickr_flickr_slideshow_timer);
    slickr_flickr_slideshow_timer_on = false;
}

function slickr_flickr_start_slideshows() {
var flickr_slideshow_delay =  slickr_flickr_get_slideshow_delay();
if (flickr_slideshow_delay > 0) {
    slickr_flickr_slideshow_timer = setInterval("slickr_flickr_next_slides()",flickr_slideshow_delay*1000);
    slickr_flickr_slideshow_timer_on = true;
    }
}

jQuery.noConflict(); jQuery(document).ready(function($) {
    $(".slickr-flickr-galleria").each(function($index){
        $delay = $(this).data("delay");
        $autoplay = $(this).data("autoplay")=="on"?true:false;
        $captions = $(this).data("captions")=="on"?true:false;
        $descriptions = $(this).data("descriptions")=="on"?true:false;
        if (($delay) && ($delay > 0))
            $(this).galleria( { slideDelay : $delay * 1000, autoPlay: $autoplay, captions: $captions, descriptions: $descriptions});
        else
            $(this).galleria();
    });
    if ($('a[rel="sf-lbox-auto"]').size() + $('a[rel="sf-lbox-manual"]').size() > 0) {
        $(".slickr-flickr-gallery").each( function (index) {
            $delay = $(this).data("delay");
            if (($delay) && ($delay > 0)) {
                $autoplay = $(this).data("autoplay")=="on"?true:false;
                $(this).find('a[rel="sf-lbox-auto"]').lightBox( { nextSlideDelay : 1000 * $delay, autoPlay: $autoplay });
            } else {
                $(this).find('a[rel="sf-lbox-manual"]').lightBox();
            }
        });
    }
  slickr_flickr_start_slideshows();
});