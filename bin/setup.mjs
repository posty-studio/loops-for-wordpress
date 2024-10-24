import { input } from '@inquirer/prompts';
import replace from 'replace-in-file';
import fs from 'fs';
import { exec } from 'child_process';
import { promisify } from 'util';

const execAsync = promisify( exec );

const name = await input( {
	message: 'Name',
	default: 'Starter Plugin',
} );

const description = await input( {
	message: 'Description',
	default: 'A starter plugin for WordPress',
} );

const slug = await input( {
	message: 'Slug',
	default: name.toLowerCase().replace( / /g, '-' ),
} );

const textDomain = await input( {
	message: 'Text Domain',
	default: slug,
} );

const prefix = await input( {
	message: 'Prefix',
	default: 'posty',
} );

const pluginFileName = await input( {
	message: 'Plugin File Name',
	default: `${ slug }.php`,
} );

const phpConstantPrefix = await input( {
	message: 'PHP Constant Prefix',
	default: slug.toUpperCase().replace( /-/g, '_' ),
} );

const phpNamespace = await input( {
	message: 'PHP Namespace',
	default: name.replace( / /g, '' ),
} );

const composerPackageName = await input( {
	message: 'Composer Package Name',
	default: `posty/${ slug }`,
} );

const authorName = await input( {
	message: 'Author Name',
	default: 'Daniel Post',
} );

const authorUrl = await input( {
	message: 'Author URL',
	default: 'https://posty.studio',
} );

const options = {
	files: [
		'includes/**/*.php',
		'src/**/*',
		'package.json',
		'composer.json',
		'readme.txt',
		'posty-starter-plugin.php',
		'.phpcs.xml',
	],
	from: [
		/Posty Starter Plugin/g,
		/PostyStarterPluginDescription/g,
		/posty-starter-plugin/g,
		/posty\/starter-plugin/g,
		/POSTY_STARTER_PLUGIN/g,
		/PostyStarterPlugin/g,
		/postyStarterPlugin/g,
		/starter-plugin/g,
		/posty\/sample-block/g,
		/posty-sample-block/g,
		/value="posty"/g,
		/Author: Daniel Post/g,
		/Author URI: https:\/\/posty.studio/g,
		/Text Domain: posty-starter-plugin/g,
	],
	to: [
		name,
		description,
		slug,
		composerPackageName,
		phpConstantPrefix,
		phpNamespace,
		phpNamespace.charAt( 0 ).toLowerCase() + phpNamespace.slice( 1 ),
		textDomain,
		`${ prefix }/sample-block`,
		`${ prefix }-sample-block`,
		`value="${ prefix }"`,
		`Author: ${ authorName }`,
		`Author URI: ${ authorUrl }`,
		`Text Domain: ${ textDomain }`,
	],
};

try {
	await replace( options );
	await fs.promises.rename( 'posty-starter-plugin.php', pluginFileName );
	await execAsync( 'composer install && composer update' );
	console.log( "You're all set!" ); // eslint-disable-line no-console
} catch ( error ) {
	console.error( 'Error occurred:', error ); // eslint-disable-line no-console
}
