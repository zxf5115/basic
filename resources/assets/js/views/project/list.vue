<template>
  <div class="mod-config">
    <el-form :inline="true" :model="dataForm" @keyup.enter.native="getDataList()">
      <el-form-item>
        <el-input v-model="dataForm.title" :placeholder="$t('common.title')" clearable></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="getDataList()">{{ $t('common.search') }}</el-button>

        <el-button v-if="isAuth('project:create')" type="primary" @click="addOrUpdateHandle()">{{ $t('common.create') }}</el-button>

        <el-button v-if="isAuth('project:delete')" type="danger" @click="deleteHandle()" :disabled="dataListSelections.length <= 0">{{ $t('common.batch_delete') }}</el-button>
      </el-form-item>
    </el-form>

    <el-table :data="dataList" border v-loading="dataListLoading" @selection-change="selectionChangeHandle" style="width: 100%;">
      <el-table-column type="selection" header-align="center" align="center" width="50">
      </el-table-column>

      <el-table-column prop="id" header-align="center" align="center" width="80" :label="$t('common.id')">
      </el-table-column>

      <el-table-column header-align="center" align="center" :label="$t('project.category_name')">
        <template slot-scope="scope">
          {{ scope.row.category.title }}
        </template>
      </el-table-column>

      <el-table-column prop="title" header-align="center" align="center" :label="$t('project.title')">
      </el-table-column>

      <el-table-column header-align="center" align="center" :label="$t('project.customer_name')">
        <template slot-scope="scope">
          {{ scope.row.customer.title }}
        </template>
      </el-table-column>

      <el-table-column header-align="center" align="center" :label="$t('project.leader')">
        <template slot-scope="scope">
          {{ scope.row.leader.title }}
        </template>
      </el-table-column>

      <el-table-column prop="create_time" header-align="center" align="center" :label="$t('project.create_time')">
      </el-table-column>

      <el-table-column fixed="right" header-align="center" align="center" width="150" :label="$t('common.handle')">
        <template slot-scope="scope">
          <el-button v-if="isAuth('project:update')" type="text" size="small" @click="addOrUpdateHandle(scope.row.id)">
            <span class="edit">{{$t('common.update')}}</span>
          </el-button>
          <el-button v-if="isAuth('project:delete')" type="text" size="small" @click="deleteHandle(scope.row.id)">
            <span class="delete">{{$t('common.delete')}}</span>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
      @size-change="sizeChangeHandle"
      @current-change="currentChangeHandle"
      :current-page="pageIndex"
      :page-sizes="[10, 20, 50, 100]"
      :page-size="pageSize"
      :total="totalPage"
      layout="total, sizes, prev, pager, next, jumper">
    </el-pagination>
    <!-- 弹窗, 新增 / 修改 -->
    <add-or-update v-if="addOrUpdateVisible" ref="addOrUpdate" @refreshDataList="getDataList"></add-or-update>
    <view v-if="viewVisible" ref="view" @refreshDataList="getDataList"></view>
  </div>
</template>

<script>
  import common from '@/utils/common'
  import AddOrUpdate from './add-or-update'
  export default {
    extends: common,
    data () {
      return {
        model: 'project',
        dataForm: {
          paramKey: ''
        },
        viewVisible: false,
      }
    },
    components: {
      AddOrUpdate
    },
    activated () {
      this.getDataList()
    },
    methods: {

    }
  }
</script>
