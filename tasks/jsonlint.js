module.exports = () =>
{
	'use strict';

	const config =
	{
		dependency:
		{
			src:
			[
				'composer.json',
				'package.json'
			]
		},
		ruleset:
		{
			src:
			[
				'.htmlhintrc',
				'.eslintrc',
				'.ncsslintrc',
				'.stylelintrc'
			]
		},
		languages:
		{
			src:
			[
				'languages/*.json'
			]
		},
		modules:
		{
			src:
			[
				'modules/**/*.json'
			]
		},
		provider:
		{
			src:
			[
				'tests/provider/*.json'
			]
		}
	};

	return config;
};