<template>
  <div class="app-container">
    <el-row :gutter="10">
      <el-col :sm="12" :xs="24">
        <el-card class="box-card">
          <div class="clearfix" slot="header">
            <span>字典列表</span>
          </div>
          <dictionary @dictionaryClick="dictionaryClick" @resetItem="resetItem" ref="dictionary" />
        </el-card>
      </el-col>
      <el-col :sm="12" :xs="24">
        <el-card class="box-card">
          <div class="clearfix" slot="header">
            <span>字典详情</span>
          </div>
          <dictionary-item ref="dictionaryItem" />
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import common from '@/utils/common'
import dictionary from './dictionary'
import dictionaryItem from './dictionary-item'

export default {
  extends: common,
  components: { dictionary, dictionaryItem },
  data () {
    return {
      model: 'system/dictionary',
    }
  },
  methods: {
    dictionaryClick (row) {
      this.$refs.dictionaryItem.dictionaryClick(row)
    },
    resetItem () {
      this.$refs.dictionaryItem.dictionaryClick({ id: -1 })
    }
  }
}
</script>

