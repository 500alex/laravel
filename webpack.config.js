const path = require('path');
const webpack = require('webpack');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const isDev = process.env.NODE_ENV === 'development';

module.exports = {
    mode: 'development',
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@': path.resolve(__dirname,'resources')
        },
        extensions: ['.js','.json','scss']
    },
    entry: {
        main: path.resolve(__dirname, './resources/js/app.js'),
    },
    output: {
        clean: true,
        publicPath: '',
        path: path.resolve(__dirname, './public/dist'),
        filename: "[name].js",
    },
    module: {
        rules: [
            {
                test: /\.(scss|css)$/,
                use: [ MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader'],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: ['babel-loader'],
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    extractCSS: true,
                    loaders: {
                        js: 'babel-loader?presets[]=es2015&presets[]=es2016'
                    }
                }
            },
            {
                test:/\.ts$/,
                exclude: /node_modules/,
                use:{
                    loader: "babel-loader",
                    options:{
                        presets:['@babel/preset-env','@babel/preset-typescript'],
                        "plugins": [
                            ["@babel/plugin-proposal-class-properties", { "loose": true }]
                        ]
                    }
                }
            },
            {
                test: /\.(woff(2)?|eot|ttf|otf|svg|)$/,
                type: 'asset/inline',
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/i,
                dependency: { not: ['url'] },
                use:[
                    {
                        loader: 'file-loader',
                    }
                ],
                type: 'javascript/auto'


            },

        ]
    },
    plugins: [
        require('postcss-size'),
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin(),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            "window.jQuery": 'jquery'
        }),

    ],
    // devtool: isDev ? 'source-map' : '',
    optimization: {
        // splitChunks: {
        //     chunks: 'all',
        // },
        minimize: true,
        minimizer: [new TerserPlugin(),new CssMinimizerPlugin()],
    }


}
