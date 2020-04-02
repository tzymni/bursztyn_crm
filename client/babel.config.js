module.exports = {
  presets: [
    '@vue/cli-plugin-babel/preset'
  ],
  module: {
    rules: [
      {
        test: /\.css$/i,
        loader: 'css-loader',
        options: {
          modules: true,
        },
      },
    ],
  },
}
