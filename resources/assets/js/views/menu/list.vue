<template>
  <div class="mod-menu">
    <el-form :inline="true" :model="dataForm">
      <el-form-item>
        <el-button v-if="isAuth('menu:create')" type="primary" @click="addOrUpdateHandle()">
          {{ $t('common.create') }}
        </el-button>
      </el-form-item>
    </el-form>

    <el-table :data="dataList" row-key="id" border style="width: 100%; ">

      <el-table-column prop="title" header-align="center" min-width="150" :label="$t('menu.title')">
      </el-table-column>

      <el-table-column header-align="center" align="center" :label="$t('menu.icon')">
        <template slot-scope="scope">
          <icon-svg :name="scope.row.icon || ''"></icon-svg>
        </template>
      </el-table-column>

      <el-table-column prop="type" header-align="center" align="center" :label="$t('menu.type')">
      </el-table-column>

      <el-table-column prop="sort" header-align="center" align="center" :label="$t('common.sort')">
      </el-table-column>

      <el-table-column prop="url" header-align="center" align="center" width="150" :show-overflow-tooltip="true" :label="$t('menu.url')">
      </el-table-column>

      <el-table-column fixed="right" header-align="center" align="center" width="150" :label="$t('common.handle')">
        <template slot-scope="scope">
          <el-button v-if="isAuth('menu:update')" type="text" size="small" @click="addOrUpdateHandle(scope.row.id)">
            <span class="edit">{{$t('common.update')}}</span>
          </el-button>

          <el-button v-if="isAuth('menu:delete')" type="text" size="small" @click="deleteHandle(scope.row.id)">
            <span class="delete">{{$t('common.delete')}}</span>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <!-- 弹窗, 新增 / 修改 -->
    <add-or-update v-if="addOrUpdateVisible" ref="addOrUpdate" @refreshDataList="getDataList"></add-or-update>
  </div>
</template>

<script>
  import common from '@/utils/common'
  import AddOrUpdate from './add-or-update'
  import { treeDataTranslate } from '@/utils'
  export default
  {
    extends: common,
    data ()
    {
      return {
        model: 'menu',
        dataForm: {},
        dataList: [],
        dataListLoading: false,
        addOrUpdateVisible: false
      }
    },
    components:
    {
      AddOrUpdate
    },
    activated ()
    {
      this.getDataList()
    },
    methods:
    {
      // 获取数据列表
      getDataList ()
      {
        this.dataListLoading = true
        this.$http(
        {
          url: this.$http.adornUrl('/menu/list'),
          method: 'get',
          params: this.$http.adornParams()
        }).then(({data}) =>
        {
          this.dataList = treeDataTranslate(data.data, 'id')
          this.dataListLoading = false
        })
      },


    }
  }
</script>
