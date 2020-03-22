<template>
  <el-dialog :close-on-click-modal="false" :close-on-press-escape="true" :title="title" :type="type" :visible.sync="isVisible" :width="width" top="50px">
    <el-form :model="dictionaryItem" :rules="rules" label-position="right" label-width="100px" ref="form">
      <el-form-item :hidden="true" :label="$t('table.dictionaryItem.dictionaryId')" prop="dictionaryId">
        <el-input :disabled="type==='edit'" v-model="dictionaryItem.dictionaryId" />
      </el-form-item>
      <el-form-item :label="$t('table.dictionaryItem.code')" prop="code">
        <el-input :disabled="type==='edit'" v-model="dictionaryItem.code" />
      </el-form-item>
      <el-form-item :label="$t('table.dictionaryItem.name')" prop="name">
        <el-input v-model="dictionaryItem.name" />
      </el-form-item>
      <el-form-item :label="$t('table.dictionaryItem.status')" prop="status">
        <el-radio-group v-model="dictionaryItem.status">
          <el-radio :label="true">{{ $t('common.status.valid') }}</el-radio>
          <el-radio :label="false">{{ $t('common.status.invalid') }}</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item :label="$t('table.dictionaryItem.sortValue')" prop="sortValue">
        <el-input v-model="dictionaryItem.sortValue" />
      </el-form-item>
      <el-form-item :label="$t('table.dictionaryItem.describe')" prop="describe">
        <el-input v-model="dictionaryItem.describe" />
      </el-form-item>
    </el-form>
    <div class="dialog-footer" slot="footer">
      <el-button @click="isVisible = false" plain type="warning">{{ $t('common.cancel') }}</el-button>
      <el-button @click="dataFormSubmit()" plain type="primary">{{ $t('common.confirm') }}</el-button>
    </div>
  </el-dialog>
</template>

<script>
export default {
  components: {},
  props: {
    dialogVisible: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'add'
    }
  },
  data () {
    return {
      visible: false,
      advertisingType: [],
      dataForm: {
        id: 0,
        title: '',
        type: '',
        valid_time: [],
        picture: '',
        description: ''
      },

      dictionaryItem: this.initDictionaryItem(),
      screenWidth: 0,
      width: this.initWidth(),
      rules: {
        dictionaryId: { required: true, message: this.$t('rules.require'), trigger: 'blur' },
        code: [
          { required: true, message: this.$t('rules.require'), trigger: 'blur' },
          { min: 1, max: 64, message: this.$t('rules.range4to10'), trigger: 'blur' }
        ],
        name: [
          { required: true, message: this.$t('rules.require'), trigger: 'blur' },
          { min: 1, max: 64, message: this.$t('rules.range4to10'), trigger: 'blur' }
        ],
        describe: [
          { min: 1, max: 200, message: this.$t('rules.range4to10'), trigger: 'blur' }
        ],
        status: { required: true, message: this.$t('rules.require'), trigger: 'blur' }
      }
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
            url: this.$http.adornUrl(`/system/dictionary/view/${this.dataForm.id}`),
            method: 'get',
            params: this.$http.adornParams()
          }).then(({data}) => {
            if (data && data.status === 200) {
              this.dataForm.title = data.data.title
              this.dataForm.type = data.data.type + ''
              this.dataForm.valid_time = [data.data.start_time, data.data.end_time]
              this.dataForm.picture = data.data.picture
              this.dataForm.description = data.data.description
            }
          })
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
              'title': this.dataForm.title,
              'type': this.dataForm.type,
              'valid_time': this.dataForm.valid_time,
              'picture': this.dataForm.picture,
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
    },
  }
}
</script>
