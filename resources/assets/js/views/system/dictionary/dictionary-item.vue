<template>
  <div class="app-container">
    <el-form :inline="true" :model="dataForm" @keyup.enter.native="getDataList()">
      <el-form-item>
        <el-input v-model="dataForm.realname" :placeholder="$t('user.username')" clearable></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="getDataList()">
          {{$t('common.search')}}
        </el-button>

        <el-button v-if="isAuth('system:dictionary:create') && dataForm.pid > -1" type="success" @click="addOrUpdateHandle(dataForm.pid)">
          {{$t('common.create')}}
        </el-button>

        <el-button v-if="isAuth('system:dictionary:delete')" type="danger" @click="deleteHandle()" :disabled="dataListSelections.length <= 0">
          {{$t('common.batch_delete')}}
        </el-button>

        <el-button v-if="isAuth('system:dictionary:export')" type="primary" @click="exportExcel()">
          {{$t('common.export')}}
        </el-button>
      </el-form-item>
    </el-form>

    <el-table :data="dataList" border v-loading="dataListLoading" @selection-change="selectionChangeHandle" style="width: 100%;" size="mini">

      <el-table-column type="selection" header-align="center" align="center" width="50">
      </el-table-column>

      <el-table-column :label="$t('dictionary.title')" :show-overflow-tooltip="true" align="center" prop="title">
      </el-table-column>

      <el-table-column :label="$t('dictionary.code')" :show-overflow-tooltip="true" align="center" prop="code">
      </el-table-column>

      <el-table-column :label="$t('dictionary.value')" :show-overflow-tooltip="true" align="center" prop="value">
      </el-table-column>

      <el-table-column :label="$t('dictionary.description')" :show-overflow-tooltip="true" align="center" prop="description">
      </el-table-column>

      <el-table-column :label="$t('common.status')" align="center" prop="status">
      </el-table-column>

      <el-table-column :label="$t('common.handle')" align="center" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button v-if="isAuth('system:dictionary:update')" type="text" size="small" @click="addOrUpdateHandle(scope.row.pid, scope.row.id)">
            <span class="edit">{{$t('common.update')}}</span>
          </el-button>

          <el-button v-if="isAuth('system:dictionary:delete')" type="text" size="small" @click="deleteHandle(scope.row.id)">
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
        pid: -1
      }
    }
  },
  activated () {
    this.getDataList()
  },
  methods: {
    search () {
      this.getDataList({
        ...this.dataForm
      })
      this.$emit('dictionaryClick', { id: -1 })
    },


    // 新增 / 修改
    addOrUpdateHandle (pid, id) {
      this.addOrUpdateVisible = true
      this.$nextTick(() => {
        this.$refs.addOrUpdate.init(pid, id)
      })
    },

    dictionaryClick (dictionary) {
      this.dataForm.pid = dictionary.id
      this.dictionary = dictionary
      this.search()
    }
  }
}
</script>
