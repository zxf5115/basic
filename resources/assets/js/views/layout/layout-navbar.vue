<template>
  <nav class="site-navbar" :class="'site-navbar--' + navbarLayoutType">
    <div class="site-navbar__header">
      <h1 class="site-navbar__brand" @click="$router.push({ name: 'home' })">
        <a class="site-navbar__brand-lg" href="javascript:;">{{ customer_name }}</a>
        <a class="site-navbar__brand-mini" href="javascript:;">
          <img class="logo" :src=" customer_logo ">
        </a>
      </h1>
    </div>
    <div class="site-navbar__body clearfix">
      <el-menu
        class="site-navbar__menu"
        mode="horizontal">
        <el-menu-item class="site-navbar__switch" index="0" @click="sidebarFold = !sidebarFold">
          <icon-svg name="zhedie"></icon-svg>
        </el-menu-item>

        <el-menu-item class="site-navbar__switch refresh" index="0" @click.native="refresh()">
          <icon-svg name="refresh"></icon-svg>
        </el-menu-item>
      </el-menu>
      <el-menu
        class="site-navbar__menu site-navbar__menu--right"
        mode="horizontal">

        <el-menu-item class="settings" index="1" @click="$router.push({ name: 'theme' })">
          <template slot="title">
            <icon-svg name="shezhi" class="el-icon-setting"></icon-svg>
          </template>
        </el-menu-item>

        <el-menu-item class="message" index="1" @click="$router.push({ name: 'message' })">
          <template slot="title">
            <el-badge :value="unread_count">
              <icon-svg name="message" class="el-icon-setting"></icon-svg>
            </el-badge>
          </template>
        </el-menu-item>

        <language class="right-menu-item hover-effect" />



        <el-menu-item class="site-navbar__avatar" index="3">
          <el-dropdown :show-timeout="0" placement="bottom">
            <span class="el-dropdown-link">
              <img :src=" avatar " :alt="userName">{{ userName }}
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item @click.native="updatePasswordHandle()">
                {{ $t('user.change') }}
              </el-dropdown-item>
              <el-dropdown-item @click.native="logoutHandle()">
                {{ $t('common.logout') }}
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </el-menu-item>
      </el-menu>
    </div>
    <!-- 弹窗, 修改密码 -->
    <update-password v-if="updatePassowrdVisible" ref="updatePassowrd"></update-password>
  </nav>
</template>

<script>
  import Language from '@/components/language'
  import UpdatePassword from './layout-navbar-update-password'
  import { clearLoginInfo } from '@/utils'

  export default {
    inject: ['refresh'],
    data () {
      return {
        updatePassowrdVisible: false
      }
    },
    components: {
      UpdatePassword,
      Language
    },
    computed: {
      navbarLayoutType: {
        get () { return this.$store.state.common.navbarLayoutType }
      },
      sidebarFold: {
        get () { return this.$store.state.common.sidebarFold },
        set (val) { this.$store.commit('common/updateSidebarFold', val) }
      },
      mainTabs: {
        get () { return this.$store.state.common.mainTabs },
        set (val) { this.$store.commit('common/updateMainTabs', val) }
      },
      userName: {
        get () { return this.$store.state.user.name }
      },
      avatar: {
        get () { return this.$store.state.user.avatar }
      },
      customer_name: {
        get () { return this.$store.state.customer.customer_name }
      },
      customer_logo: {
        get () { return this.$store.state.customer.customer_logo }
      },
      unread_count: {
        get () { return this.$store.state.user.unread_count }
      }
    },
    methods: {
      // 修改密码
      updatePasswordHandle () {
        this.updatePassowrdVisible = true
        this.$nextTick(() => {
          this.$refs.updatePassowrd.init()
        })
      },
      // 退出
      logoutHandle ()
      {
        this.$confirm(`确定进行[退出]操作?`, '提示',
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() =>
        {
          this.$http(
          {
            url: this.$http.adornUrl('/user/logout'),
            method: 'post',
            data: this.$http.adornData()
          }).then(({data}) =>
          {
            if (data && data.status === 200)
            {
              clearLoginInfo()
              this.$router.push({ name: 'login' })
            }
          })
        }).catch(() => {})
      },
      // tabs, 刷新当前
      tabsRefreshCurrentHandle () {
        var tab = this.$route
        this.removeTabHandle(tab.name)
        this.$nextTick(() => {
          this.$router.push({ name: tab.name, query: tab.query, params: tab.params })
        })
      }
    }
  }
</script>
