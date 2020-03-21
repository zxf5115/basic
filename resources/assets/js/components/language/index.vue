<template>


  <el-menu-item class="language" index="2">
    <el-dropdown :show-timeout="0" placement="bottom" @command="handleSetLanguage">
      <span class="el-dropdown-link earth">
        <icon-svg name="language" icon-class="el-icon-language" />
      </span>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item :disabled="language === 'en'" command="en">English</el-dropdown-item>
        <el-dropdown-item :disabled="language === 'zh'" command="zh">中文</el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </el-menu-item>


</template>

<script>
export default {
  name: 'language',
  computed: {
    language() {
      return this.$store.getters.language
    }
  },
  methods: {
    handleSetLanguage(language) {
      this.$i18n.locale = language
      this.$store.dispatch('language/setLanguage', language)
      this.$message({
        message: this.$i18n.t('lang.set_lang_success'),
        type: 'success'
      })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
