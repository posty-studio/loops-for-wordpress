import domReady from '@wordpress/dom-ready';

const initForm = ( form ) => {
	const parent = form.parentElement;

	form.addEventListener( 'submit', async ( event ) => {
		event.preventDefault();

		const status = parent.getAttribute( 'data-status' );

		if ( [ 'loading', 'success' ].includes( status ) ) {
			return;
		}

		parent.setAttribute( 'data-status', 'loading' );

		try {
			const response = await fetch( form.action, {
				method: 'POST',
				body: new FormData( form ),
			} );

			if ( ! response.ok ) {
				throw new Error( response.statusText );
			}

			parent.setAttribute( 'data-status', 'success' );
		} catch ( error ) {
			parent.setAttribute( 'data-status', 'error' );
		}
	} );
};

domReady( () => {
	const forms = document.querySelectorAll( '[data-loops-form]' );

	for ( const form of forms ) {
		initForm( form );
	}
} );
