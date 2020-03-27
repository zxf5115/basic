<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" label-width="100px" ref="dataForm">

      <el-form-item :label="$t('project.personnel.title')" prop="title">
        <el-input :maxlength="50" v-model="dataForm.title" />
      </el-form-item>

      <el-form-item :label="$t('project.personnel.picture')" prop="picture">
        <el-upload class="avatar-uploader" :action="this.$http.adornUrl('/file/picture')" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
          <img v-if="dataForm.picture" :src="dataForm.picture" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>

      <el-form-item :label="$t('project.personnel.company')" prop="company">
        <el-input :maxlength="20" v-model="dataForm.company" />
      </el-form-item>

      <el-form-item :label="$t('project.personnel.position')" prop="position">
        <el-input :maxlength="20" v-model="dataForm.position" />
      </el-form-item>

      <el-form-item :label="$t('project.personnel.address')" prop="address">
        <el-input :maxlength="100" v-model="dataForm.address" />
      </el-form-item>

      <el-form-item :label="$t('common.mobile')" prop="mobile">
        <el-input :maxlength="100" v-model="dataForm.mobile" />
      </el-form-item>

      <el-form-item :label="$t('common.weixin')" prop="weixin">
        <el-input :maxlength="100" v-model="dataForm.weixin" />
      </el-form-item>

      <el-form-item :label="$t('common.email')" prop="email">
        <el-input :maxlength="100" v-model="dataForm.email" />
      </el-form-item>

      <el-form-item :label="$t('project.personnel.description')" prop="description">
        <el-input type="textarea" v-model="dataForm.description"></el-input>
      </el-form-item>

      <el-form-item :label="$t('common.sort')" prop="sort">
        <el-input-number v-model="dataForm.sort" controls-position="right" :min="0"/>
      </el-form-item>

      <el-form-item prop="experience" :label="$t('project.personnel.experience')">
        <mavon-editor class="mavon" :boxShadow="false" v-model="dataForm.experience" ref="md" @imgAdd="$imgAdd" @change="change"/>
      </el-form-item>

    </el-form>

    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">{{$t('common.cancel')}}</el-button>
      <el-button type="primary" @click="dataFormSubmit()">{{$t('common.confirm')}}</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import { mavonEditor } from 'mavon-editor'
  import 'mavon-editor/dist/css/index.css'

  export default {
    components: {
      mavonEditor
    },
    data () {
      return {
        visible: false,
        dataForm: {
          id: 0,
          title: '',
          picture: '',
          company: '',
          position: '',
          address: '',
          mobile: '',
          weixin: '',
          email: '',
          description: '',
          experience: '',
          sort: 0
        },
        dataRule: {
          title: [
            { required: true, message: this.$t('project.personnel.rules.title.require'), trigger: 'blur' },
            { min: 1, max: 50, message: this.$t('project.personnel.rules.title.length'), trigger: 'blur' }
          ],
          picture: [
            { required: true, message: this.$t('project.personnel.rules.content.require'), trigger: 'blur' }
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
              url: this.$http.adornUrl(`/project/personnel/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.dataForm.title = data.data.title
                this.dataForm.picture = data.data.picture
                this.dataForm.company = data.data.company
                this.dataForm.position = data.data.position
                this.dataForm.address = data.data.address
                this.dataForm.mobile = data.data.mobile
                this.dataForm.weixin = data.data.weixin
                this.dataForm.email = data.data.email
                this.dataForm.description = data.data.description
                this.dataForm.experience = data.data.experience
                this.dataForm.sort = data.data.sort
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
              url: this.$http.adornUrl(`/project/personnel/handle`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id || undefined,
                'title': this.dataForm.title,
                'picture': this.dataForm.picture,
                'company': this.dataForm.company,
                'position': this.dataForm.position,
                'address': this.dataForm.address,
                'mobile': this.dataForm.mobile,
                'weixin': this.dataForm.weixin,
                'email': this.dataForm.email,
                'description': this.dataForm.description,
                'experience': this.dataForm.experience,
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
      },
      // 将图片上传到服务器，返回地址替换到md中
      $imgAdd (pos, $file) {
        let formdata = new FormData()
        formdata.append('file', $file)
        // 这里没有服务器供大家尝试，可将下面上传接口替换为你自己的服务器接口
        this.$http({
          url: this.$http.adornUrl('/file/picture'),
          method: 'post',
          data: formdata,
          headers: { 'Content-Type': 'multipart/form-data' }
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.$refs.md.$img2Url(pos, data.data.message)
          } else {
            this.$message.error(data.data.message)
          }
        })
      },
      change (value, render) {
        // render 为 markdown 解析后的结果
        this.html = render
      },
    }
  }
</script>
<style lang="scss" scoped>
</style>
