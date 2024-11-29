<?php
/**
 * This file returns an array of package configurations.
 *
 * @package ProjectHuddle Child
 */

return [
	'packages' => [
		'wordpress' => [
			'source' => 'https://github.com/WordPress/WordPress.git',
			'tags'   => [ 'v6.6.2' ],
			'output' => __DIR__ . '/stubs/wordpress',
		],
	],
];
