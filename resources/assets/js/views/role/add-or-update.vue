<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" ref="dataForm" @keyup.enter.native="dataFormSubmit()" label-width="80px">
      <el-form-item :label="$t('role.title')" prop="title">
        <el-input v-model="dataForm.title" :placeholder="$t('role.title')"></el-input>
      </el-form-item>
      <el-form-item :label="$t('role.description')" prop="description">
        <el-input v-model="dataForm.description" :placeholder="$t('role.description')"></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">{{$t('common.cancel')}}</el-button>
      <el-button type="primary" @click="dataFormSubmit()">{{$t('common.confirm')}}</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import { treeDataTranslate } from '@/utils'
  export default {
    data () {
      return {
        visible: false,
        dataForm: {
          title: '',
          description: ''
        },
        dataRule: {
          title: [
            { required: true, message: this.$t('role.rules.title.require'), trigger: 'blur' },
            { min: 1, max: 50, message: this.$t('role.rules.title.length'), trigger: 'blur' }
          ]
        },
      }
    },
    methods: {
      init (id) {
        this.dataForm.id = id || 0
        this.visible = true
        this.$nextTick(() => {
          this.$refs['dataForm'].resetFields()
          if (this.dataForm.id) {
            this.$http({
              url: this.$http.adornUrl(`/role/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.dataForm.title = data.title
                this.dataForm.description = data.description
              }
            })
          }
        })
      },
      // 表单提交
      dataFormSubmit () {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            this.$http({
              url: this.$http.adornUrl(`/role/handle`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id || undefined,
                'title': this.dataForm.title,
                'description': this.dataForm.description,
              })
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.$message({
                  message: this.$t('common.handle_success'),
                  type: 'success',
                  duration: 1500,
                  onClose: () => {
                    this.visible = false
                    this.$emit('refreshDataList')
                  }
                })
              } else {
                this.$message.error(this.$t(data.message))
              }
            })
          }
        })
      }
    }
  }
</script>
