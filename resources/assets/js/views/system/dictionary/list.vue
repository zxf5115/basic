<template>
  <div class="app-container">
    <el-row :gutter="10">
      <el-col :sm="12" :xs="24">
        <el-card class="box-card">
          <div class="clearfix" slot="header">
            <span>{{ $t('dictionary.list') }}</span>
          </div>
          <dictionary @dictionaryClick="dictionaryClick" @resetItem="resetItem" ref="dictionary" />
        </el-card>
      </el-col>
      <el-col :sm="12" :xs="24">
        <el-card class="box-card">
          <div class="clearfix" slot="header">
            <span>{{ $t('dictionary.view') }}</span>
          </div>
          <dictionary-item ref="dictionaryItem" />
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import dictionary from './dictionary'
import dictionaryItem from './dictionary-item'

export default {
  components: { dictionary, dictionaryItem },
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

