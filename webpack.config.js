const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const WooCommerceDependencyExtractionWebpackPlugin = require( '@woocommerce/dependency-extraction-webpack-plugin' );
const path = require( 'path' );
const _ = require('lodash');

/** @type {import('webpack').Configuration} */
const extraConfig = {
	resolve: {
		alias: {
			'@prc': path.resolve( __dirname, 'src/@prc' ),
			'@previews': path.resolve( __dirname, 'src/previews' ),
		},
	},
	watchOptions: {
		ignored: /node_modules|build/,
	},
};

module.exports = defaultConfig.map((config, index) => {
	if (index !== 0) return config;

	const newConfig = _.merge( config, extraConfig );

	return {
		...newConfig,
		plugins: [
			...newConfig.plugins.filter(
				( plugin ) =>
					plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
			),
			new WooCommerceDependencyExtractionWebpackPlugin(),
		],
	};
	
});
