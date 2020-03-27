<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" label-width="100px" ref="dataForm">

      <el-form-item :label="$t('customer.title')" prop="title">
        <el-input :maxlength="50" v-model="dataForm.title" />
      </el-form-item>

      <el-form-item :label="$t('customer.contact')" prop="contact">
        <el-input :maxlength="20" v-model="dataForm.contact" />
      </el-form-item>

      <el-form-item :label="$t('customer.picture')" prop="picture">
        <el-upload class="avatar-uploader" :action="this.$http.adornUrl('/file/picture')" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
          <img v-if="dataForm.picture" :src="dataForm.picture" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>

      <el-form-item :label="$t('customer.mobile')" prop="mobile">
        <el-input :maxlength="100" v-model="dataForm.mobile" />
      </el-form-item>

      <el-form-item :label="$t('common.weixin')" prop="weixin">
        <el-input :maxlength="100" v-model="dataForm.weixin" />
      </el-form-item>

      <el-form-item :label="$t('common.email')" prop="email">
        <el-input :maxlength="100" v-model="dataForm.email" />
      </el-form-item>

      <el-form-item :label="$t('customer.description')" prop="description">
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
        customerType: [],
        dataForm: {
          id: 0,
          title: '',
          contact: '',
          mobile: '',
          weixin: '',
          email: '',
          picture: '',
          description: ''
        },
        dataRule: {
          title: [
            { required: true, message: this.$t('customer.rules.title.require'), trigger: 'blur' },
            { min: 1, max: 50, message: this.$t('customer.rules.title.length'), trigger: 'blur' }
          ],
          contact: [
            { required: true, message: this.$t('customer.rules.contact.require'), trigger: 'blur' },
            { min: 1, max: 20, message: this.$t('customer.rules.contact.length'), trigger: 'blur' }
          ],
          picture: [
            { required: true, message: this.$t('message.rules.content.require'), trigger: 'blur' }
          ]
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
              url: this.$http.adornUrl(`/project/customer/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.dataForm.title = data.data.title
                this.dataForm.contact = data.data.contact
                this.dataForm.mobile = data.data.mobile
                this.dataForm.weixin = data.data.weixin
                this.dataForm.email = data.data.email
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
              url: this.$http.adornUrl(`/project/customer/handle`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id || undefined,
                'title': this.dataForm.title,
                'contact': this.dataForm.contact,
                'mobile': this.dataForm.mobile,
                'weixin': this.dataForm.weixin,
                'email': this.dataForm.email,
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
