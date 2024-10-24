<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-status="idle">
	<form method="POST" name="newsletter" class="wp-block-loops-form__wrapper" data-loops-form action="<?php echo esc_url( rest_url( 'loops/v1/subscribe' ) ); ?>">
		<div class="wp-block-loops-form__field">
			<label for="first_name" class="screen-reader-text"><?php esc_html_e( 'Name', 'loops-for-wordpress' ); ?></label>
			<input class="wp-block-loops-form__input" type="text" name="first_name" placeholder="<?php esc_html_e( 'First name', 'loops-for-wordpress' ); ?>" required>
		</div>
		<div class="wp-block-loops-form__field">
			<label for="email" class="screen-reader-text"><?php esc_html_e( 'Email address', 'loops-for-wordpress' ); ?></label>
			<input class="wp-block-loops-form__input" type="email" name="email" placeholder="<?php esc_html_e( 'Email address', 'loops-for-wordpress' ); ?>" required>
		</div>
		<p class="wp-block-loops-form__honey"><label><?php esc_html_e( "Don't fill this out if you're human:", 'loops-for-wordpress' ); ?> <input name="honey"></label></p>
		<button type="submit" class="wp-element-button wp-block-loops-form__submit"><?php esc_html_e( 'Subscribe', 'loops-for-wordpress' ); ?></button>
	</form>
	<div class="wp-block-loops-form__success">
		<?php esc_html_e( 'Thank you for subscribing!', 'loops-for-wordpress' ); ?>
	</div>
	<div class="wp-block-loops-form__error">
		<?php esc_html_e( 'Something went wrong, please try again later.', 'loops-for-wordpress' ); ?>
	</div>
</div>

