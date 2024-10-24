const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		app: './src/js/app.js',
		editor: './src/js/editor.js',
	},
};
