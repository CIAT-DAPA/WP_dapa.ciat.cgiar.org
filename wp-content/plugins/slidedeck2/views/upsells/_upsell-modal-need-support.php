<div class="upsell-modal">
	<div class="slidedeck-header">
	    <h1><?php _e( "Upgrade to Get Support", $this->namespace ); ?></h1>
	</div>
	<div class="background">
		<div class="inner">
			<div class="copyblock">
			    <h3><?php _e( "Talk to real human beings!", $this->namespace ); ?></h3>
				<p>We're a proud team of design nerds whose passion is improving the Web. When you contact our support team, rest assured you're talking to the same folks who actually built SlideDeck.</p>
			</div>
			<div class="cta">
				<a class="slidedeck-noisy-button" href="<?php echo slidedeck2_action( "/upgrades&referrer=Need+Support+Handslap" ); ?>" class="button slidedeck-noisy-button"><span>Upgrade to Personal</span></a>
				<a class="features-link" href="http://www.slidedeck.com/features?utm_campaign=sd2_lite&utm_medium=handslap_link&utm_source=handslap_support&utm_content=support_team<?php echo self::get_cohort_query_string('&'); ?>" target="_blank">or learn more about other SlideDeck features</a>
			</div>
		</div>
	</div>
</div>