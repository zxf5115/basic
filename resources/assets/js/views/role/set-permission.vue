<template>
  <el-dialog
    :title="$t('common.permission')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" ref="dataForm" @keyup.enter.native="dataFormSubmit()">
      <el-scrollbar>
        <el-card class="box-card">
          <el-form-item :label="dataForm.title">
            <div align="left" style="margin-left:24px;">
              <el-checkbox
                :indeterminate="isIndeterminate"
                @change="checkedAll"
                v-model="checkedMenu"
              />  <span>全选 / 反选</span>
            </div>
            <el-tree
              :data="menuList"
              :empty-text="$t('common.empty_data')"
              :props="menuListTreeProps"
              node-key="id"
              ref="menuListTree"
              :default-expand-all="true"
              show-checkbox>
            </el-tree>
          </el-form-item>
        </el-card>
      </el-scrollbar>

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
        menuList: [],
        menuListId: [],
        menuListTreeProps: {
          label: 'remark',
          children: 'children'
        },
        dataForm: {
          title: ''
        },
        isIndeterminate: false,
        checkedMenu: false
      }
    },
    methods: {
      init (id) {
        this.dataForm.id = id || 0
        this.$http({
          url: this.$http.adornUrl('/menu/select'),
          method: 'get',
          params: this.$http.adornParams()
        }).then(({data}) => {
          this.menuList = treeDataTranslate(data.data.menu),
          this.menuListId = data.data.all_menu_id
        }).then(() => {
          this.visible = true
          this.$nextTick(() => {
            this.$refs['dataForm'].resetFields()
            this.$refs.menuListTree.setCheckedKeys([])
          })
        }).then(() => {
          if (this.dataForm.id) {
            this.$http({
              url: this.$http.adornUrl(`/role/permission/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.dataForm.title = data.data.title
                this.checkedMenu = this.menuListId.length === data.data.permission.length
                this.$refs.menuListTree.setCheckedKeys(data.data.permission)
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
              url: this.$http.adornUrl(`/role/permission`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id,
                'menu_id': [].concat(this.$refs.menuListTree.getCheckedKeys(), this.$refs.menuListTree.getHalfCheckedKeys())
              })
            }).then(({data}) => {
              if (data && data.status === 200) {
                this.$message({
                  message: '操作成功',
                  type: 'success',
                  duration: 1500,
                  onClose: () => {
                    this.visible = false
                    this.$emit('refreshDataList')
                  }
                })
              } else {
                this.$message.error(data.msg)
              }
            })
          }
        })
      },

      checkedAll() {
        if (this.checkedMenu) {
          //全选
          this.$refs.menuListTree.setCheckedKeys(this.menuListId);
          this.isIndeterminate = false;
        } else {
          //取消选中
          this.$refs.menuListTree.setCheckedKeys([]);
          this.isIndeterminate = false;
        }
      }
    }
  }
</script>
