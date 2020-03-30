<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" label-width="100px" ref="dataForm">

      <el-form-item :label="$t('project.title')" prop="title">
        <el-input :maxlength="50" v-model="dataForm.title" />
      </el-form-item>

      <el-form-item :label="$t('project.picture')" prop="picture">
        <el-upload class="avatar-uploader" :action="this.$http.adornUrl('/file/picture')" :show-file-list="false" :on-success="handleAvatarSuccess" :before-upload="beforeAvatarUpload">
          <img v-if="dataForm.picture" :src="dataForm.picture" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
      </el-form-item>

      <el-form-item :label="$t('project.category_name')" prop="category">
        <el-select class="fullwidth" placeholder v-model="dataForm.category">
          <el-option :key="item.id" :label="item.title" :value="item.id" v-for="item in allCategoryList" />
        </el-select>
      </el-form-item>


      <el-form-item :label="$t('project.customer_name')" prop="customer">
        <el-select class="fullwidth" placeholder v-model="dataForm.customer">
          <el-option :key="item.id" :label="item.title" :value="item.id" v-for="item in allCustomerList" />
        </el-select>
      </el-form-item>

      <el-form-item :label="$t('project.leader')" prop="leader">
        <el-select class="fullwidth" placeholder v-model="dataForm.leader">
          <el-option :key="item.id" :label="item.title" :value="item.id" v-for="item in allLeaderList" />
        </el-select>
      </el-form-item>

      <el-form-item :label="$t('project.personnel_name')" prop="personnel">
        <el-select class="fullwidth" collapse-tags multiple placeholder v-model="dataForm.personnel">
          <el-option :key="item.id" :label="item.title" :value="item.id" v-for="item in allPersonnelList" />
        </el-select>
      </el-form-item>

      <el-form-item :label="$t('project.project_cost')" prop="project_cost">
        <el-input :maxlength="100" v-model="dataForm.project_cost" />
      </el-form-item>

      <el-form-item :label="$t('project.url')" prop="url">
        <el-input :maxlength="100" v-model="dataForm.url" />
      </el-form-item>

      <el-form-item :label="$t('project.version')" prop="version">
        <el-input :maxlength="100" v-model="dataForm.version" />
      </el-form-item>

      <el-form-item :label="$t('project.level')" prop="level">
        <el-input :maxlength="100" v-model="dataForm.level" />
      </el-form-item>

      <el-form-item :label="$t('project.finish')" prop="finish">
        <el-radio-group v-model="dataForm.finish">
          <el-radio :label="1">{{$t('common.yes')}}</el-radio>
          <el-radio :label="2">{{$t('common.no')}}</el-radio>
        </el-radio-group>
      </el-form-item>

      <el-form-item :label="$t('project.hot')" prop="hot">
        <el-radio-group v-model="dataForm.hot">
          <el-radio :label="1">{{$t('common.yes')}}</el-radio>
          <el-radio :label="2">{{$t('common.no')}}</el-radio>
        </el-radio-group>
      </el-form-item>

      <el-form-item :label="$t('project.new')" prop="new">
        <el-radio-group v-model="dataForm.new">
          <el-radio :label="1">{{$t('common.yes')}}</el-radio>
          <el-radio :label="2">{{$t('common.no')}}</el-radio>
        </el-radio-group>
      </el-form-item>

      <el-form-item :label="$t('common.sort')" prop="sort">
        <el-input-number v-model="dataForm.sort" controls-position="right" :min="0"/>
      </el-form-item>

      <el-form-item prop="description" :label="$t('project.description')">
        <mavon-editor class="mavon" :boxShadow="false" v-model="dataForm.description" ref="md" @imgAdd="$imgAdd" @change="change"/>
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
        allCategoryList: [],
        allCustomerList: [],
        allLeaderList: [],
        allPersonnelList: [],
        dataForm: {
          id: 0,
          title: '',
          picture: '',
          category: '',
          customer: '',
          leader: '',
          personnel: '',
          project_cost: '',
          url: '',
          version: '',
          level: '',
          finish: 2,
          hot: 2,
          new: 2,
          description: '',
          sort: 0
        },
        dataRule: {
          title: [
            { required: true, message: this.$t('project.rules.title.require'), trigger: 'blur' },
            { min: 1, max: 50, message: this.$t('project.rules.title.length'), trigger: 'blur' }
          ],
          picture: [
            { required: true, message: this.$t('project.rules.content.require'), trigger: 'blur' }
          ]
        }
      }
    },
    mounted () {
      this.loadCategoryList()
      this.loadCustomerList()
      this.loadLeaderList()
      this.loadPersonnelList()
    },
    methods: {
      init (id) {
        this.dataForm.id = id || 0
        this.visible = true
        this.$nextTick(() => {
          this.$refs['dataForm'].resetFields()
          if (this.dataForm.id) {
            this.$http({
              url: this.$http.adornUrl(`/project/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.dataForm.title = data.data.title
                this.dataForm.picture = data.data.picture
                this.dataForm.category = data.data.category
                this.dataForm.customer = data.data.customer
                this.dataForm.leader = data.data.leader

                var personnel = [];

                if(data.data.personnel)
                {
                  for(var i in data.data.personnel)
                  {
                    personnel[i] = data.data.personnel[i]['personnel_id']
                  }
                }
                this.dataForm.personnel = personnel

                this.dataForm.project_cost = data.data.project_cost
                this.dataForm.url = data.data.url
                this.dataForm.version = data.data.version
                this.dataForm.level = data.data.level
                this.dataForm.finish = data.data.finish
                this.dataForm.hot = data.data.hot
                this.dataForm.new = data.data.new
                this.dataForm.description = data.data.description
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
              url: this.$http.adornUrl(`/project/handle`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id || undefined,
                'title': this.dataForm.title,
                'picture': this.dataForm.picture,
                'category': this.dataForm.category,
                'customer': this.dataForm.customer,
                'leader': this.dataForm.leader,
                'personnel': this.dataForm.personnel,
                'project_cost': this.dataForm.project_cost,
                'url': this.dataForm.url,
                'version': this.dataForm.version,
                'level': this.dataForm.level,
                'finish': this.dataForm.finish,
                'hot': this.dataForm.hot,
                'new': this.dataForm.new,
                'description': this.dataForm.description,
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
      typeChange (select) {
        if (select === '3') {
          this.disabledAcceptType = true
        } else {
          this.disabledAcceptType = false
        }
      },
      loadCategoryList () {
        this.$http({
          url: this.$http.adornUrl('/project/category/select'),
          method: 'get'
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.allCategoryList = [...data.data]
          } else {
            this.$message.error(this.$t(data.message))
          }
        })
      },
      loadCustomerList () {
        this.$http({
          url: this.$http.adornUrl('/project/customer/select'),
          method: 'get'
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.allCustomerList = [...data.data]
          } else {
            this.$message.error(this.$t(data.message))
          }
        })
      },
      loadLeaderList () {
        this.$http({
          url: this.$http.adornUrl('/project/personnel/select'),
          method: 'get'
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.allLeaderList = [...data.data]
          } else {
            this.$message.error(this.$t(data.message))
          }
        })
      },
      loadPersonnelList () {
        this.$http({
          url: this.$http.adornUrl('/project/personnel/select'),
          method: 'get'
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.allPersonnelList = [...data.data]
          } else {
            this.$message.error(this.$t(data.message))
          }
        })
      },
    }
  }
</script>
<style lang="scss" scoped>
</style>
