<template>
  <div
    class="site-wrapper"
    :class="{ 'site-sidebar--fold': sidebarFold }"
    v-loading.fullscreen.lock="loading"
    element-loading-text="拼命加载中">
    <template v-if="!loading">
      <layout-navbar />
      <layout-sidebar />
      <div class="site-content__wrapper" :style="{ 'min-height': documentClientHeight + 'px' }">
        <layout-content v-if="!$store.state.common.contentIsNeedRefresh" />
        <layout-footer />
      </div>
    </template>
    <right-panel v-if="1">
    </right-panel>

  </div>
</template>

<script>
  import LayoutNavbar from './layout-navbar'
  import LayoutSidebar from './layout-sidebar'
  import LayoutContent from './layout-content'
  import LayoutFooter from './layout-footer'
  import RightPanel from '@/components/right-panel'
  export default {
    provide () {
      return {
        // 刷新
        refresh () {
          this.$store.commit('common/updateContentIsNeedRefresh', true)
          this.$nextTick(() => {
            this.$store.commit('common/updateContentIsNeedRefresh', false)
          })
        }
      }
    },
    data () {
      return {
        loading: true
      }
    },
    components: {
      LayoutNavbar,
      LayoutSidebar,
      LayoutContent,
      LayoutFooter,
      RightPanel
    },
    computed: {
      documentClientHeight: {
        get () { return this.$store.state.common.documentClientHeight },
        set (val) { this.$store.commit('common/updateDocumentClientHeight', val) }
      },
      sidebarFold: {
        get () { return this.$store.state.common.sidebarFold }
      },
      userId: {
        get () { return this.$store.state.user.id },
        set (val) { this.$store.commit('user/updateId', val) }
      },
      userName: {
        get () { return this.$store.state.user.name },
        set (val) { this.$store.commit('user/updateName', val) }
      },
      avatar: {
        get () { return this.$store.state.user.avatar },
        set (val) { this.$store.commit('user/updateAvatar', val) }
      },
      last_login_time: {
        get () { return this.$store.state.user.last_login_time },
        set (val) { this.$store.commit('user/updateLastLoginTime', val) }
      },
      last_login_address: {
        get () { return this.$store.state.user.last_login_address },
        set (val) { this.$store.commit('user/updateLastLoginAddress', val) }
      },
      customer_name: {
        get () { return this.$store.state.customer.customer_name },
        set (val) { this.$store.commit('customer/updateCustomerTitle', val) }
      },
      customer_logo: {
        get () { return this.$store.state.customer.customer_logo },
        set (val) { this.$store.commit('customer/updateCustomerLogo', val) }
      }
    },
    created () {
      this.getUserInfo()
    },
    mounted () {
      this.resetDocumentClientHeight()
    },
    methods: {
      // 重置窗口可视高度
      resetDocumentClientHeight () {
        this.documentClientHeight = document.documentElement['clientHeight']
        window.onresize = () => {
          this.documentClientHeight = document.documentElement['clientHeight']
        }
      },
      // 获取当前管理员信息
      getUserInfo () {
        this.$http({
          url: this.$http.adornUrl('/user/info'),
          method: 'post',
          params: this.$http.adornParams()
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.loading = false
            this.userId = data.data.id
            this.userName = data.data.realname
            this.avatar = data.data.avatar
            this.last_login_time = data.data.last_login_time
            this.last_login_address = data.data.last_login_ip
            this.customer_name = data.data.customer_name
            this.customer_logo = data.data.customer_logo
          }
          else {
            this.$router.push({ name: 'login' })
          }
        })
      }
    }
  }
</script>
