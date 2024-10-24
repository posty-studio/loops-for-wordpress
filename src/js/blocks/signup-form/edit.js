import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

const Edit = () => {
	return (
		<div { ...useBlockProps() }>
			<form method="POST" className="wp-block-loops-form__wrapper">
				<div className="wp-block-loops-form__field">
					<input
						className="wp-block-loops-form__input"
						type="email"
						name="email"
						placeholder={ __(
							'Your best email',
							'loops-for-wordpress'
						) }
						required
					/>
					<button
						type="submit"
						className="wp-block-loops-form__submit"
					>
						{ __( 'Subscribe', 'loops-for-wordpress' ) }
					</button>
				</div>
			</form>
		</div>
	);
};

export default Edit;
