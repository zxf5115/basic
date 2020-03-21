<template>
  <el-dialog
    :title="!dataForm.id ? $t('common.create') : $t('common.update')"
    :close-on-click-modal="false"
    :fullscreen="true"
    :visible.sync="visible">

    <el-form :model="dataForm" :rules="dataRule" ref="dataForm" label-width="80px">
      <el-form-item :label="$t('menu.type')" prop="type">
        <el-radio-group v-model="dataForm.type">
          <el-radio v-for="(type, index) in dataForm.typeList" :label="index" :key="index">
            {{ type }}
          </el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item :label="dataForm.typeList[dataForm.type] + '名称'" prop="title">
        <el-input v-model="dataForm.title" :placeholder="dataForm.typeList[dataForm.type] + '名称'"></el-input>
      </el-form-item>
      <el-form-item label="上级菜单" prop="parentName">
        <el-popover
          ref="menuListPopover"
          placement="bottom-start"
          trigger="click">
          <el-tree
            :data="menuList"
            :props="menuListTreeProps"
            node-key="id"
            ref="menuListTree"
            @current-change="menuListTreeCurrentChangeHandle"
            :default-expand-all="false"
            :expand-on-click-node="false">
          </el-tree>
        </el-popover>
        <el-input v-model="dataForm.parentName" v-popover:menuListPopover :readonly="true" placeholder="点击选择上级菜单" class="menu-list__input"></el-input>
      </el-form-item>
      <el-form-item label="菜单路由" prop="url">
        <el-input v-model="dataForm.url" placeholder="菜单路由"></el-input>
      </el-form-item>
      <el-form-item label="排序号" prop="sort">
        <el-input-number v-model="dataForm.sort" controls-position="right" :min="0" label="排序号"></el-input-number>
      </el-form-item>
      <el-form-item label="菜单图标" prop="icon">
        <el-row>
          <el-col :span="22">
            <el-popover
              ref="iconListPopover"
              placement="bottom-start"
              trigger="click"
              popper-class="mod-menu__icon-popover">
              <div class="mod-menu__icon-inner">
                <div class="mod-menu__icon-list">
                  <el-button
                    v-for="(item, index) in iconList"
                    :key="index"
                    @click="iconActiveHandle(item)"
                    :class="{ 'is-active': item === dataForm.icon }">
                    <icon-svg :name="item"></icon-svg>
                  </el-button>
                </div>
              </div>
            </el-popover>
            <el-input v-model="dataForm.icon" v-popover:iconListPopover :readonly="true" placeholder="菜单图标名称" class="icon-list__input"></el-input>
          </el-col>
          <el-col :span="2" class="icon-list__tips">
            <el-tooltip placement="top" effect="light">
              <div slot="content">全站推荐使用SVG Sprite, 详细请参考:<a href="//github.com/daxiongYang/renren-fast-vue/blob/master/src/icons/index.js" target="_blank">icons/index.js</a>描述</div>
              <i class="el-icon-warning"></i>
            </el-tooltip>
          </el-col>
        </el-row>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="visible = false">取消</el-button>
      <el-button type="primary" @click="dataFormSubmit()">确定</el-button>
    </span>
  </el-dialog>
</template>

<script>
  import { treeDataTranslate } from '@/utils'
  import Icon from '@/icons'
  export default {
    data () {
      var validateUrl = (rule, value, callback) => {
        if (this.dataForm.type === 1 && !/\S/.test(value)) {
          callback(new Error('菜单URL不能为空'))
        } else {
          callback()
        }
      }
      return {
        visible: false,
        dataForm:
        {
          id: 0,
          type: 1,
          typeList: ['模块', '菜单', '按钮'],
          title: '',
          pid: 0,
          parentName: '',
          url: '',
          sort: 0,
          icon: '',
          iconList: []
        },
        dataRule:
        {
          name: [
            { required: true, message: '菜单名称不能为空', trigger: 'blur' }
          ],
          parentName: [
            { required: true, message: '上级菜单不能为空', trigger: 'change' }
          ],
          url: [
            { validator: validateUrl, trigger: 'blur' }
          ]
        },
        menuList: [],
        menuListTreeProps:
        {
          label: 'title',
          children: 'children'
        }
      }
    },
    created ()
    {
      this.iconList = Icon.getNameList()
    },
    methods:
    {
      init (id)
      {
        this.dataForm.id = id || 0
        this.$http(
        {
          url: this.$http.adornUrl('/menu/select'),
          method: 'get',
          params: this.$http.adornParams()
        }).then(({data}) =>
        {
          this.menuList = [...[{ id: '0', title: '无' }], ...treeDataTranslate(data.data.menu)]
        }).then(() =>
        {
          this.visible = true
          this.$nextTick(() =>
          {
            this.$refs['dataForm'].resetFields()
          })
        }).then(() =>
        {
          if (!this.dataForm.id)
          {
            // 新增
            this.menuListTreeSetCurrentNode()
          }
          else
          {
            // 修改
            this.$http(
            {
              url: this.$http.adornUrl(`/menu/view/${this.dataForm.id}`),
              method: 'get',
              params: this.$http.adornParams()
            }).then(({data}) =>
            {
              if(data.status === 200)
              {
                this.dataForm.id = data.data.id
                this.dataForm.type = data.data.type
                this.dataForm.title = data.data.title
                this.dataForm.pid = data.data.pid
                this.dataForm.url = data.data.url
                this.dataForm.sort = data.data.sort
                this.dataForm.icon = data.data.icon
                this.menuListTreeSetCurrentNode()
              }
              else
              {

              }
            })
          }
        })
      },
      // 菜单树选中
      menuListTreeCurrentChangeHandle (data, node)
      {
        this.dataForm.pid = data.id
        this.dataForm.parentName = data.title
        node.dialogVisible  = false
        console.log(node);
      },
      // 菜单树设置当前选中节点
      menuListTreeSetCurrentNode ()
      {
        this.$refs.menuListTree.setCurrentKey(this.dataForm.pid)
        this.dataForm.parentName = (this.$refs.menuListTree.getCurrentNode() || {})['title']
      },
      // 图标选中
      iconActiveHandle (iconName)
      {
        this.dataForm.icon = iconName
      },
      // 表单提交
      dataFormSubmit ()
      {
        this.$refs['dataForm'].validate((valid) =>
        {
          if (valid)
          {
            this.$http(
            {
              url: this.$http.adornUrl(`/menu/handle`),
              method: 'post',
              data: this.$http.adornData({
                'id': this.dataForm.id || undefined,
                'pid': this.dataForm.pid,
                'title': this.dataForm.title,
                'type': this.dataForm.type,
                'icon': this.dataForm.icon,
                'url': this.dataForm.url,
                'sort': this.dataForm.sort
              })
            }).then(({data}) =>
            {
              if (data && data.status === 200)
              {
                this.$message(
                {
                  message: '操作成功',
                  type: 'success',
                  duration: 1500,
                  onClose: () => {
                    this.visible = false
                    this.$emit('refreshDataList')
                  }
                })
              }
              else
              {
                this.$message.error(data.message)
              }
            })
          }
        })
      }
    }
  }
</script>

<style lang="scss">
  .mod-menu {
    .menu-list__input,
    .icon-list__input {
       > .el-input__inner {
        cursor: pointer;
      }
    }
    &__icon-popover {
      width: 458px;
      overflow: hidden;
    }
    &__icon-inner {
      width: 478px;
      max-height: 258px;
      overflow-x: hidden;
      overflow-y: auto;
    }
    &__icon-list {
      width: 458px;
      padding: 0;
      margin: -8px 0 0 -8px;
      > .el-button {
        padding: 8px;
        margin: 8px 0 0 8px;
        > span {
          display: inline-block;
          vertical-align: middle;
          width: 18px;
          height: 18px;
          font-size: 18px;
        }
      }
    }
    .icon-list__tips {
      font-size: 18px;
      text-align: center;
      color: #e6a23c;
      cursor: pointer;
    }
  }
</style>
