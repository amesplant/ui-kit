const baseConfig = require('10up-toolkit/config/postcss.config');

module.exports = (props) => {
	const config = baseConfig(props);

	const newConfig = JSON.parse(JSON.stringify(config));

	const { 'postcss-mixins': postcssMixins, ...otherPlugins } = config.plugins;

	newConfig.plugins = {
		// This ensures that the the media queries are available in the global scope
		// This is needed for the media queries to for example work in the separate CSS files
		// imported by blocks
		'@csstools/postcss-global-data': {
			files: ['./assets/css/globals/media-queries.css'],
		},

		// This is needed to make the mixins available in the global scope
		// This is needed for the mixins to for example work in the separate CSS files
		// imported by blocks
		'postcss-mixins': {
			...postcssMixins,
			mixinsFiles: ['./assets/css/globals/mixins.css'],
		},
		...otherPlugins,
	};

	return newConfig;
};
