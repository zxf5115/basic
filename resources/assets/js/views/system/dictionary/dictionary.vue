<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input :placeholder="$t('table.dictionary.code')" class="filter-item search-item" v-model="queryParams.code" />
      <el-input :placeholder="$t('table.dictionary.name')" class="filter-item search-item" v-model="queryParams.name" />

      <el-button @click="search" class="filter-item" plain type="primary">{{ $t('table.search') }}</el-button>
      <el-button @click="reset" class="filter-item" plain type="warning">{{ $t('table.reset') }}</el-button>
      <el-dropdown class="filter-item" trigger="click" v-if="['dict:add','dict:delete','dict:export']">
        <el-button>
          {{ $t('table.more') }}
          <i class="el-icon-arrow-down el-icon--right" />
        </el-button>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item @click.native="add" v-if="['dict:add']">{{ $t('table.add') }}</el-dropdown-item>
          <el-dropdown-item @click.native="batchDelete" v-if="['dict:delete']">{{ $t('table.delete') }}</el-dropdown-item>
          <el-dropdown-item @click.native="exportExcel" v-if="['dict:export']">{{ $t('table.export') }}</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>

    <el-table
      :data="dataList"
      :key="tableKey"
      @row-click="rowClick"
      @selection-change="onSelectChange"
      @sort-change="sortChange"
      border
      fit
      ref="table"
      size="mini"
      style="width: 100%;"
      v-loading="loading"
    >
      <el-table-column align="center" type="selection" width="40px" />
      <el-table-column :label="$t('table.dictionary.code')" :show-overflow-tooltip="true" align="center" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.code }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.dictionary.name')" :show-overflow-tooltip="true" align="center" prop="name">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.dictionary.describe')" :show-overflow-tooltip="true" align="center" prop="describe">
        <template slot-scope="scope">
          <span>{{ scope.row.describe }}</span>
        </template>
      </el-table-column>
      <el-table-column
        :filter-method="filterStatus"
        :filters="[{ text: $t('common.status.valid'), value: true }, { text: $t('common.status.invalid'), value: false }]"
        :label="$t('table.dictionary.status')"
        class-name="status-col"
        width="70px"
      >
        <template slot-scope="{row}">
          <el-tag :type="row.status | statusFilter">{{ row.status ? $t('common.status.valid') : $t('common.status.invalid') }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.createTime')" align="center" prop="createTime" sortable="custom" width="170px">
        <template slot-scope="scope">
          <span>{{ scope.row.createTime }}</span>
        </template>
      </el-table-column>
      <el-table-column :label="$t('table.operation')" align="center" class-name="small-padding fixed-width" width="100px">
        <template slot-scope="{row}">
          <i @click="edit(row)" class="el-icon-edit table-operation" style="color: #2db7f5;" v-if="isAuth('dictionary:update')" />
          <i @click="singleDelete(row)" class="el-icon-delete table-operation" style="color: #f50;" v-if="isAuth('dictionary:delete')" />
          <el-link class="no-perm" v-has-no-permission="['dict:update','dict:delete']">{{ $t('tips.noPermission') }}</el-link>
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
  </div>
</template>

<script>
import AddOrUpdate from './add-or-update'
import common from '@/utils/common'
export default {
  extends: common,
  components: { AddOrUpdate },
  data () {
    return {
      model: 'system/dictionary',
      dataForm: {
        userName: ''
      },

      tableKey: 0,
      queryParams: {},
      sort: {},
      selection: [],
      // 以下已修改
      loading: false,
      tableData: {
        total: 0
      },
    }
  },
  methods: {
    filterStatus (value, row) {
      return row.status === value
    },
    onSelectChange (selection) {
      this.selection = selection
    },
    search () {
      this.fetch({
        ...this.queryParams,
        ...this.sort
      })
      this.$emit('dictionaryClick', { id: -1 })
    },
    reset () {
      this.queryParams = {}
      this.sort = {}
      this.$refs.table.clearSort()
      this.$refs.table.clearFilter()
      this.search()
    },
    exportExcel () {
      this.$message({
        message: '待完善',
        type: 'warning'
      })
    },
    singleDelete (row) {
      this.$refs.table.toggleRowSelection(row, true)
      this.batchDelete()
    },
    batchDelete () {
      if (!this.selection.length) {
        this.$message({
          message: this.$t('tips.noDataSelected'),
          type: 'warning'
        })
        return
      }
      this.$confirm(this.$t('tips.confirmDelete'), this.$t('common.tips'), {
        confirmButtonText: this.$t('common.confirm'),
        cancelButtonText: this.$t('common.cancel'),
        type: 'warning'
      }).then(() => {
        const ids = []
        this.selection.forEach((u) => {
          ids.push(u.id)
        })
        this.delete(ids)
      }).catch(() => {
        this.clearSelections()
      })
    },
    clearSelections () {
      this.$refs.table.clearSelection()
    },
    delete (ids) {
      dictionaryApi.delete({ 'ids': ids })
        .then((response) => {
          const res = response.data
          if (res.isSuccess) {
            this.$message({
              message: this.$t('tips.deleteSuccess'),
              type: 'success'
            })
          }
          this.search()
        })
    },
    add () {
      this.dialog.type = 'add'
      this.dialog.isVisible = true
      this.$refs.edit.setDictionary(false)
    },
    edit (row) {
      this.$refs.edit.setDictionary(row)
      this.dialog.type = 'edit'
      this.dialog.isVisible = true
    },
    sortChange (val) {
      this.sort.field = val.prop
      this.sort.order = val.order
      this.search()
    },
    rowClick (row) {
      this.$emit('dictionaryClick', row)
    }
  }
}
</script>
