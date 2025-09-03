const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const glob = require('glob');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';
    const blockFiles = glob.sync('./src/js/blocks/*.js'); // adjust folder path
console.log (blockFiles)
    return {
        entry: {
            main: './src/js/main.js',
            blocks: blockFiles,
            style: './src/css/style.css'
        },
        output: {
            path: path.resolve(__dirname, 'assets'),
            filename: 'js/[name].js',
            clean: true
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },
                {
                    test: /\.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        {
                            loader: 'postcss-loader',
                            options: {
                                postcssOptions: {
                                    plugins: [
                                        require('tailwindcss'),
                                        require('autoprefixer')
                                    ]
                                }
                            }
                        }
                    ]
                }
            ]
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/[name].css'
            })
        ],
        resolve: {
            alias: {
                src: path.resolve(__dirname, 'src')
            }
        },
        devtool: isProduction ? false : 'source-map'
    };
};