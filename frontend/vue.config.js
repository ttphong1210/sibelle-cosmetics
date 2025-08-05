const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: [
    'bootstrap-vue',
    'vuetify'
  ],
  devServer: {
    host: '0.0.0.0',
    port: 8081,
    allowedHosts: 'all'
  },
})
