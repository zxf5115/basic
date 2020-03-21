<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" ref="dataForm" label-width="80px">
      <el-form-item :label="$t('user.account')" prop="username">
        <el-input v-model="dataForm.username" :placeholder="$t('user.account')"></el-input>
      </el-form-item>

      <el-form-item :label="$t('user.avatar')" prop="avatar">
        <el-upload class="avatar-uploader" :action="this.$http.adornUrl('/file/avatar')" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
          <img v-if="dataForm.avatar" :src="dataForm.avatar" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>

      <el-form-item :label="$t('user.realname')" prop="realname">
        <el-input v-model="dataForm.realname" :placeholder="$t('user.realname')"></el-input>
      </el-form-item>

      <el-form-item :label="$t('user.email')" prop="email">
        <el-input v-model="dataForm.email" :placeholder="$t('user.email')"></el-input>
      </el-form-item>

      <el-form-item :label="$t('user.mobile')" prop="mobile">
        <el-input v-model="dataForm.mobile" :placeholder="$t('user.mobile')"></el-input>
      </el-form-item>

      <el-form-item :label="$t('role.title')" prop="role_id">
        <el-select style="width:100%" v-model="dataForm.role_id" :placeholder="$t('role.title')">
          <el-option :key="item.id" :label="item.title" :value="item.id" v-for="item in roleList" />
        </el-select>
      </el-form-item>

      <el-form-item :label="$t('common.status')" size="mini" prop="status">
        <el-radio-group v-model="dataForm.status">
          <el-radio :label="1">{{$t('common.enable')}}</el-radio>
          <el-radio :label="2">{{$t('common.disable')}}</el-radio>
        </el-radio-group>
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
      var validateEmail = (rule, value, callback) => {
        if (!isEmail(value)) {
          callback(new Error(this.$t('user.rules.email.format')))
        } else {
          callback()
        }
      }
      var validateMobile = (rule, value, callback) => {
        if (!isMobile(value)) {
          callback(new Error(this.$t('user.rules.mobile.format')))
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
          avatar: '',
          realname: '',
          email: '',
          mobile: '',
          role_id: '',
          status: 1
        },
        dataRule: {
          username: [
            { required: true, message: this.$t('user.rules.username.require'), trigger: 'blur' },
            { min: 1, max: 32, message: this.$t('user.rules.username.length'), trigger: 'blur' }
          ],
          avatar: [
            { required: true, message: this.$t('user.rules.avatar.require'), trigger: 'blur' },
          ],
          realname: [
            { required: true, message: this.$t('user.rules.realname.require'), trigger: 'blur' },
            { min: 1, max: 100, message: this.$t('user.rules.realname.length'), trigger: 'blur' }
          ],
          email: [
            { validator: validateEmail, trigger: 'blur' }
          ],
          mobile: [
            { validator: validateMobile, trigger: 'blur' }
          ],
          role_id: [
            { required: true, message: this.$t('user.rules.role.require'), trigger: 'blur' },
          ],
        }
      }
    },
    methods: {
      init (id) {
        this.dataForm.id = id || 0
        this.$http({
          url: this.$http.adornUrl('/role/select'),
          method: 'get',
          params: this.$http.adornParams()
        }).then(({data}) =>
        {
          this.roleList = data && data.status === 200 ? data.data : []
        }).then(() =>
        {
          this.visible = true
          this.$nextTick(() =>
          {
            this.$refs['dataForm'].resetFields()
          })
        }).then(() =>
        {
          if (this.dataForm.id)
          {
            this.$http(
            {
              url: this.$http.adornUrl(`/user/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) =>
            {
              if (data && data.status === 200)
              {
                this.dataForm.username = data.data.username
                this.dataForm.realname = data.data.realname
                this.dataForm.avatar = data.data.avatar
                this.dataForm.email = data.data.email
                this.dataForm.mobile = data.data.mobile
                this.dataForm.role_id = data.data.relevance.role_id
                this.dataForm.status = data.data.status
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
              url: this.$http.adornUrl(`/user/handle`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id || undefined,
                'username': this.dataForm.username,
                'avatar': this.dataForm.avatar,
                'realname': this.dataForm.realname,
                'email': this.dataForm.email,
                'mobile': this.dataForm.mobile,
                'role_id': this.dataForm.role_id,
                'status': this.dataForm.status,
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
      handleAvatarSuccess(res, file) {
        this.dataForm.avatar = res.data.message;
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
