const path                 = require('path');
const glob                 = require('glob');
const TerserPlugin         = require('terser-webpack-plugin');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const BrowserSyncPlugin    = require('browser-sync-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin   = require('css-minimizer-webpack-plugin');

const processedChunks = new Set();

module.exports = (env) => {
    // Variables
    const isProduction = env.mode === 'production';
    const proxyTarget  = env.proxy || 'http://localhost:8080';
    // End Variables

    let entries = {}

    // JS
    const jsFiles      = glob.sync('./js/[^_]*.js').map((file) =>
        file.replace(/\\/g, '/')
    );
    const blockJsFiles = glob.sync('./blocks/**/*.js', {
        ignore: ['./blocks/__example/**'],
    }).map((file) =>
        file.replace(/\\/g, '/')
    );

    // CSS
    const scssFiles      = glob.sync('./scss/**/[^_]*.scss').map((file) =>
        file.replace(/\\/g, '/')
    );
    const blockScssFiles = glob.sync('./blocks/**/[^_]*.scss', {
        ignore: ['./blocks/__example/**'],
    }).map((file) =>
        file.replace(/\\/g, '/')
    );


    jsFiles.forEach(file => {
        const relativePath                                           = path.relative('./js', file).replace(/\\/g, '/');
        entries[`../assets/js/${relativePath.replace(/\.js$/, '')}`] = './' + file;
    })
    blockJsFiles.forEach(file => {
        const relativePath                                        = path.relative('./blocks', file).replace(/\\/g, '/');
        entries[`../blocks/${relativePath.replace(/\.js$/, '')}`] = './' + file;
    })
    scssFiles.forEach(file => {
        const relativePath                                              = path.relative('./scss', file).replace(/\\/g, '/');
        entries[`../assets/css/${relativePath.replace(/\.scss$/, '')}`] = './' + file;
    })
    blockScssFiles.forEach(file => {
        const relativePath                                          = path.relative('./blocks', file).replace(/\\/g, '/');
        entries[`../blocks/${relativePath.replace(/\.scss$/, '')}`] = './' + file;
    })

    /** @type {import('webpack').Configuration} */
    let config = {
        mode:    isProduction ? "production" : "development",
        entry:   entries,
        output:  {
            filename: (pathData) => {
                console.log(pathData.chunk.name)

                return '[name].js'
            },
            path: path.resolve(__dirname, '/')
        },
        module:  {
            rules: [
                {
                    test:    /\.js$/,
                    exclude: /node_modules/,
                    use:     {
                        loader:  'babel-loader',
                        options: {
                            presets: ['@babel/preset-env'],
                        },
                    },
                },
                {
                    test: /\.scss$/,
                    use:  [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        {
                            loader:  'postcss-loader',
                            options: {
                                postcssOptions: {
                                    plugins: [require('autoprefixer')],
                                },
                            },
                        },
                        'sass-loader',
                    ],
                },
            ],
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: (pathData) => {
                    if (!processedChunks.has(pathData.chunk.name)) {
                        processedChunks.add(pathData.chunk.name);
                    }

                    return '[name].css';
                },
            })
        ],
        devtool: isProduction ? false : 'source-map',

    }
    if (isProduction) {
        config.optimization = {
            minimize:  true,
            minimizer: [
                new TerserPlugin({
                    terserOptions:   {
                        ecma:   5,
                        format: {
                            comments: false
                        }
                    },
                    extractComments: false
                }),
                new CssMinimizerPlugin()
            ]
        }
        config.plugins.push(new CleanWebpackPlugin())
        config.performance = {
            hints: false
        }
    } else {
        config.plugins.push(
            new BrowserSyncPlugin({
                host:   'localhost',
                port:   8800,
                notify: false,
                proxy:  proxyTarget,
                files:  [
                    '../**/*.php'
                ]
            })
        )
        config.devServer = {
            static:        path.resolve(__dirname, '../'),
            hot:           false,
            devMiddleware: {
                writeToDisk: true,
            }
        }
    }

    return config;
}
