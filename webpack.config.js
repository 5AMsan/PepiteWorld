const path = require('path');

module.exports = {
  entry: [__dirname + '/src/js/app.js', __dirname + '/src/sass/style.scss'],
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'js/pepite-world.js',
  },
  module: {
    rules: [
      {
        test: /\.scss$/i,
        use: [
          // Creates `style` nodes from JS strings
          //'style-loader',
          // Translates CSS into CommonJS
          //'css-loader',
          // Extract to css files instead of webpack
          {
            loader: 'file-loader',
            options: { outputPath: 'css/', name: '[name].min.css'}
          },
          // Compiles Sass to CSS
          'sass-loader',
        ],
      },
      {
        test: /\.css$/i,
        loader: 'css-loader?url=false',
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts/'
            }
          }
        ]
      }
    ],
  },
};