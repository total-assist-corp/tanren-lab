const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');  // CSS抽出用プラグイン
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries"); // styleのエントリポイントから無駄なjsの生成を回避

module.exports = (env, argv) => {
  const isProduction = argv.mode === 'production';

  return {
    entry: {
      main: './src/js/main.js',       // main.jsエントリーポイント
      style: './src/scss/style.scss'  // style.scssエントリーポイント
    },
    output: {
      path: path.resolve(__dirname, 'dist', isProduction ? 'prod' : 'dev'),  // 環境ごとに出力先フォルダを切り替え
      filename: isProduction ? '[name].min.js' : '[name].js',  // 本番用はminified、開発用は通常のjs
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env'],
            },
          },
        },
        {
          test: /\.scss$/,
          use: [
            MiniCssExtractPlugin.loader,  // CSSを別ファイルとして抽出
            'css-loader',
            {
              loader: 'sass-loader',
              options: {
                implementation: require('sass'),
              },
            },
          ],
        },
      ],
    },
    devtool: isProduction ? false : 'source-map',
    watch: !isProduction,
    plugins: [
      new FixStyleOnlyEntriesPlugin(),
      new MiniCssExtractPlugin({
        filename: isProduction ? '[name].min.css' : '[name].css',  // 開発用は通常のCSS、本番用はminified
      }),
    ],
  };
};
