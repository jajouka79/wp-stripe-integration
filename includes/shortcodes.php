<?php

function pippin_stripe_payment_form($atts, $content = null) {
	
	extract( shortcode_atts( array(
		'amount' => ''
	), $atts ) );
	
	global $stripe_options;

	ob_start();

	if(isset($_GET['payment']) && $_GET['payment'] == 'paid') {
		echo '<p class="success">' . __('Thank you for your payment.', 'pippin_stripe') . '</p>';
	} else { ?>
		<form action="" method="POST" id="stripe-payment-form">
			<div class="form-row">
				<label><?php _e('Email', 'pippin_stripe'); ?></label>
				<input type="text" size="20" class="email" name="email"/>
			</div>
			<div class="form-row">
				<label><?php _e('Card Number', 'pippin_stripe'); ?></label>
				<input type="text" size="20" autocomplete="off" class="card-number"/>
			</div>
			<div class="form-row">
				<label><?php _e('CVC', 'pippin_stripe'); ?></label>
				<input type="text" size="4" autocomplete="off" class="card-cvc"/>
			</div>
			<div class="form-row">
				<label><?php _e('Expiration (MM/YYYY)', 'pippin_stripe'); ?></label>
				<input type="text" size="2" class="card-expiry-month"/>
				<span> / </span>
				<input type="text" size="4" class="card-expiry-year"/>
			</div>
			<?php if(isset($stripe_options['recurring'])) { ?>
			<div class="form-row">
				<label><?php _e('Payment Type:', 'pippin_stripe'); ?></label>
				<input type="radio" name="recurring" class="stripe-recurring" value="no" checked="checked"/><span><?php _e('One time payment', 'pippin_stripe'); ?></span>
				<input type="radio" name="recurring" class="stripe-recurring" value="yes"/><span><?php _e('Recurring monthly payment', 'pippin_stripe'); ?></span>
			</div>
			<div class="form-row" id="stripe-plans" style="display:none;">
				<label><?php _e('Choose Your Plan', 'pippin_stripe'); ?></label>
				<select name="plan_id" id="stripe_plan_id">
					<?php 
						$plans = pippin_get_stripe_plans();
						if($plans) {
							foreach($plans as $id => $plan) {
								echo '<option value="' . $id . '">' . $plan . '</option>';
							}
						}
					?>
				</select>
			</div>
			<div class="form-row">
				<label><?php _e('Discount Code', 'pippin_stripe'); ?></label>
				<input type="text" size="20" class="discount" name="discount"/>
			</div>
			<?php } ?>
			<input type="hidden" name="action" value="stripe"/>
			<input type="hidden" name="redirect" value="<?php echo get_permalink(); ?>"/>
			<input type="hidden" name="amount" value="<?php echo base64_encode($amount); ?>"/>
			<input type="hidden" name="stripe_nonce" value="<?php echo wp_create_nonce('stripe-nonce'); ?>"/>
			<button type="submit" id="stripe-submit"><?php _e('Submit Payment', 'pippin_stripe'); ?></button>
		</form>
		<div class="payment-errors"></div>
		<?php
	}
	return ob_get_clean();
}
add_shortcode('payment_form', 'pippin_stripe_payment_form');