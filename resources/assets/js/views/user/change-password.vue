<template>
  <el-dialog
    :title="$t('user.change')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" ref="dataForm" label-width="80px">
      <el-form-item :label="$t('user.account')" prop="username">
        <el-input v-model="dataForm.username" :placeholder="$t('user.account')" :disabled="true"></el-input>
      </el-form-item>

      <el-form-item :label="$t('user.password')" prop="password">
        <el-input v-model="dataForm.password" type="password" :placeholder="$t('user.password')"></el-input>
      </el-form-item>

      <el-form-item :label="$t('user.comfirm_password')" prop="comfirmPassword">
        <el-input v-model="dataForm.comfirmPassword" type="password" :placeholder="$t('user.comfirm_password')"></el-input>
      </el-form-item>

    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">{{$t('common.cancel')}}</el-button>
      <el-button type="primary" @click="dataFormSubmit()">{{$t('common.confirm')}}</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import { isEmail, isMobile } from '@/utils/validate'
  export default {
    data () {
      var validateComfirmPassword = (rule, value, callback) => {
        if (!this.dataForm.id && !/\S/.test(value)) {
          callback(new Error(this.$t('user.rules.comfirmPassword.require')))
        } else if (this.dataForm.password !== value) {
          callback(new Error(this.$t('user.rules.comfirmPassword.equal')))
        } else {
          callback()
        }
      }
      return {
        visible: false,
        roleList: [],
        dataForm: {
          id: 0,
          username: '',
          password: '',
          comfirmPassword: ''
        },
        dataRule: {
          password: [
            { required: true, message: this.$t('user.rules.password.require'), trigger: 'blur' },
            { min: 1, max: 32, message: this.$t('user.rules.password.length'), trigger: 'blur' }
          ],
          comfirmPassword: [
            { required: true, message: this.$t('user.rules.password.require'), trigger: 'blur' },
            { min: 1, max: 32, message: this.$t('user.rules.password.length'), trigger: 'blur' }
          ],
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
              url: this.$http.adornUrl(`/user/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.dataForm.username = data.data.username
                this.dataForm.password = ''
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
              url: this.$http.adornUrl(`/user/password`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id,
                'password': this.dataForm.password,
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
                this.$message.error(data.message)
              }
            })
          }
        })
      },
      handleAvatarSuccess(res, file) {
        this.dataForm.avatar = res.data.message;
      },
      beforeAvatarUpload(file) {
        const isPicture = (file.type === 'image/jpeg' || file.type === 'image/png');
        const isLt2M = file.size / 1024 / 1024 < 2;

        if (!isPicture) {
          var message = this.$t('user.rules.avatar.picture_type');
          this.$message.error(message);
        }
        if (!isLt2M) {
          var message = this.$t('user.rules.avatar.picture_size');
          this.$message.error(message);
        }

        return isPicture && isLt2M;
      },
    }
  }
</script>

<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 100px;
    height: 100px;
    line-height: 100px;
    text-align: center;
  }
  .avatar {
    width: 100px;
    height: 100px;
    display: block;
  }
</style>
