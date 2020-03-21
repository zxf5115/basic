import Vue from 'vue'
import App from './App.vue'
import router from './router'                 // api: https://github.com/vuejs/vue-router
import store from './store'                   // api: https://github.com/vuejs/vuex
import VueCookie from 'vue-cookie'            // api: https://github.com/alfhen/vue-cookie
import i18n from './lang'                     // Internationalization
import './element-ui'                         // api: https://github.com/ElemeFE/element
import './icons'                              // api: http://www.iconfont.cn/
import './element-ui-theme'
import './assets/scss/index.scss'
import httpRequest from './utils/httpRequest' // api: https://github.com/axios/axios
import { isAuth } from './utils'
import cloneDeep from 'lodash/cloneDeep'

Vue.use(VueCookie, {
  i18n: (key, value) => i18n.t(key, value)
})
Vue.config.productionTip = false



// 挂载全局
Vue.prototype.$http = httpRequest // ajax请求方法
Vue.prototype.isAuth = isAuth     // 权限方法

// 保存整站vuex本地储存初始状态
window.SITE_CONFIG['storeState'] = cloneDeep(store.state)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  i18n,
  template: '<App/>',
  components: { App }
})
