<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" label-position="right" label-width="100px" ref="dataForm">

      <el-form-item :label="$t('dictionary.title')" prop="title">
        <el-input v-model="dataForm.title" :placeholder="$t('dictionary.title')"/>
      </el-form-item>

      <el-form-item :label="$t('dictionary.code')" prop="code">
        <el-input v-model="dataForm.code" :placeholder="$t('dictionary.code')"/>
      </el-form-item>

      <el-form-item v-if="dataForm.pid > 0" :label="$t('dictionary.value')" prop="value">
        <el-input v-model="dataForm.value" :placeholder="$t('dictionary.value')"/>
      </el-form-item>

      <el-form-item :label="$t('common.status')" prop="status">
        <el-radio-group v-model="dataForm.status">
          <el-radio :label="1">{{$t('common.enable')}}</el-radio>
          <el-radio :label="2">{{$t('common.disable')}}</el-radio>
        </el-radio-group>
      </el-form-item>

      <el-form-item :label="$t('dictionary.description')" prop="description">
        <el-input v-model="dataForm.description" />
      </el-form-item>

      <el-form-item :label="$t('common.sort')" prop="sort">
        <el-input-number v-model="dataForm.sort" controls-position="right" :min="0" :label="$t('common.sort')"/>
      </el-form-item>

      <el-form-item prop="pid">
        <el-input type="hidden" v-model="dataForm.pid" />
      </el-form-item>

    </el-form>
    <div class="dialog-footer" slot="footer">
      <el-button @click="visible = false" type="warning">{{ $t('common.cancel') }}</el-button>
      <el-button @click="dataFormSubmit()" type="primary">{{ $t('common.confirm') }}</el-button>
    </div>
  </el-dialog>
</template>


<script>
export default {
  data () {
    return {
      visible: false,
      roleList: [],
      dataForm: {
        id: 0,
        pid: 0,
        title: '',
        code: '',
        value: '',
        description: '',
        sort: 0,
        status: 1
      },
      dataRule: {
        title: [
          { required: true, message: this.$t('user.rules.username.require'), trigger: 'blur' },
          { min: 1, max: 32, message: this.$t('user.rules.username.length'), trigger: 'blur' }
        ],
        code: [
          { required: true, message: this.$t('user.rules.avatar.require'), trigger: 'blur' },
        ],
        description: [
          { required: true, message: this.$t('user.rules.realname.require'), trigger: 'blur' },
          { min: 1, max: 100, message: this.$t('user.rules.realname.length'), trigger: 'blur' }
        ],
      }
    }
  },
  methods: {
    init (pid, id) {
      this.dataForm.id = id || 0
      this.visible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].resetFields()
        if (this.dataForm.id) {
          this.$http({
            url: this.$http.adornUrl(`/system/dictionary/view/${this.dataForm.id}`),
            method: 'get',
            params: this.$http.adornParams()
          }).then(({data}) => {
            if (data && data.status === 200) {
              this.dataForm.pid = data.data.pid
              this.dataForm.title = data.data.title
              this.dataForm.code = data.data.code
              this.dataForm.value = data.data.value
              this.dataForm.description = data.data.description
              this.dataForm.sort = data.data.sort
              this.dataForm.status = data.data.status
            }
          })
        }
        else {
          this.dataForm.pid = pid
        }
      })
    },
    dataFormSubmit () {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.$http({
            url: this.$http.adornUrl(`/system/dictionary/handle`),
            method: 'post',
            data: this.$http.adornData({
              'id': this.dataForm.id || undefined,
              'pid': this.dataForm.pid,
              'title': this.dataForm.title,
              'code': this.dataForm.code,
              'value': this.dataForm.value,
              'description': this.dataForm.description,
              'status': this.dataForm.status,
              'sort': this.dataForm.sort,
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
    },
  }
}
</script>
