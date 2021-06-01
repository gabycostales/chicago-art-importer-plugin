const path = require('path');
const webpack = require('webpack');
const {VueLoaderPlugin} = require('vue-loader');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: './admin/src/entry.js',
  output: {
    path: path.resolve(__dirname, 'admin/dist'),
    publicPath: '/dist/',
		filename: 'build.js'
  },
  module: {
    rules: [
      {
				test: /\.js$/,
				loader: 'babel-loader',
				include: [path.resolve(__dirname, 'admin/src')]
			},
      {
				test: /\.vue$/,
				loader: 'vue-loader',
        include: [path.resolve(__dirname, 'admin/src')],
				options: {
					loaders: {
						'scss': [
							'vue-style-loader',
							'css-loader',
							'sass-loader'
						],
            'sass': [
							'vue-style-loader',
							'css-loader',
							'sass-loader?indentedSyntax'
						]
					}
					// other vue-loader options go here
				}
			},
      {
				test: /\.(s*)[a|c]ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					"css-loader",
					"sass-loader"
				]
			}
    ]
  },
  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': path.join(__dirname, 'admin/src'),
    },
    extensions: ['*', '.js', '.vue', '.json']
  },
  plugins: [
    new CleanWebpackPlugin(),
		new VueLoaderPlugin(),
		new MiniCssExtractPlugin({
			filename: "[name].css"
		})
	]
};

if (process.env.NODE_ENV === 'production') {
  module.exports.mode = 'production';
	module.exports.devtool = 'source-map';
	module.exports.optimization = {
		minimize: true,
	};
	module.exports.plugins = (module.exports.plugins || []).concat([
		new webpack.DefinePlugin({
			'process.env': {
				NODE_ENV: '"production"'
			}
		}),
		new webpack.LoaderOptionsPlugin({
			minimize: true
		}),
	]);
}