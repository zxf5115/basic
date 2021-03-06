<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" label-width="80px" ref="dataForm">

      <el-form-item :label="$t('advertising.title')" prop="title">
        <el-input :maxlength="100" v-model="dataForm.title" />
      </el-form-item>

      <el-form-item :label="$t('advertising.type')" prop="type">
        <el-select class="fullwidth" v-model="dataForm.type" value-key="key" placeholder>
          <el-option :key="key" :label="item" :value="key" v-for="(item, key) in advertisingType" />
        </el-select>
      </el-form-item>

      <el-form-item :label="$t('advertising.valid_time')" prop="valid_time">
        <el-date-picker class="fullwidth" v-model="dataForm.valid_time" type="daterange" :start-placeholder="$t('common.start_time')" :end-placeholder="$t('common.end_time')" format="yyyy-MM-dd" value-format="timestamp">
        </el-date-picker>
      </el-form-item>

      <el-form-item :label="$t('advertising.picture')" prop="picture">
        <el-upload class="avatar-uploader" :action="this.$http.adornUrl('/file/picture')" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
          <img v-if="dataForm.picture" :src="dataForm.picture" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>


      <el-form-item :label="$t('advertising.description')" prop="description">
        <el-input type="textarea" v-model="dataForm.description"></el-input>
      </el-form-item>


    </el-form>

    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">{{$t('common.cancel')}}</el-button>
      <el-button type="primary" @click="dataFormSubmit()">{{$t('common.confirm')}}</el-button>
    </span>
  </el-dialog>
</template>

<script>
  export default {
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
        dataRule: {
          title: [
            { required: true, message: this.$t('advertising.rules.title.require'), trigger: 'blur' },
            { min: 1, max: 30, message: this.$t('advertising.rules.title.length'), trigger: 'blur' }
          ],
          type: [
            { required: true, message: this.$t('message.rules.type.require'), trigger: 'blur' },
          ],
          validtime: [
            { required: true, message: this.$t('message.rules.accept.require'), trigger: 'blur' }
          ],
          picture: [
            { required: true, message: this.$t('message.rules.content.require'), trigger: 'blur' }
          ]
        }
      }
    },
    mounted () {
      this.loadTypeList()
    },
    methods: {
      init (id) {
        this.dataForm.id = id || 0
        this.visible = true
        this.$nextTick(() => {
          this.$refs['dataForm'].resetFields()
          if (this.dataForm.id) {
            this.$http({
              url: this.$http.adornUrl(`/advertising/view/${this.dataForm.id}`),
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
      // 表单提交
      dataFormSubmit () {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            this.$http({
              url: this.$http.adornUrl(`/advertising/handle`),
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
      loadTypeList () {
        this.$http({
          url: this.$http.adornUrl('/advertising/type'),
          method: 'get'
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.advertisingType = data.data
          } else {
            this.$message.error(this.$t(data.message))
          }
        })
      },
      handleAvatarSuccess(res, file) {
        this.dataForm.picture = res.data.message;
      },
      beforeAvatarUpload(file) {
        const isPicture = (file.type === 'image/jpeg' || file.type === 'image/png');
        const isLt2M = file.size / 1024 / 1024 < 2;

        if (!isPicture) {
          var message = this.$t('user.rules.avatar.picture_type');
          this.$message.error(this.$t(data.message))
        }
        if (!isLt2M) {
          var message = this.$t('user.rules.avatar.picture_size');
          this.$message.error(this.$t(data.message))
        }

        return isPicture && isLt2M;
      }
    }
  }
</script>
<style lang="scss" scoped>
</style>
