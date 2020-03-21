<template>
  <div class="mod-user">
    <el-form :inline="true" :model="dataForm" @keyup.enter.native="getDataList()">
      <el-form-item>
        <el-input v-model="dataForm.realname" :placeholder="$t('user.username')" clearable></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="getDataList()">
          {{$t('common.search')}}
        </el-button>

        <el-button v-if="isAuth('user:create')" type="success" @click="addOrUpdateHandle()">
          {{$t('common.create')}}
        </el-button>

        <el-button v-if="isAuth('user:delete')" type="danger" @click="deleteHandle()" :disabled="dataListSelections.length <= 0">
          {{$t('common.batch_delete')}}
        </el-button>

        <el-button v-if="isAuth('user:export')" type="primary" @click="exportExcel()">
          {{$t('common.export')}}
        </el-button>
      </el-form-item>
    </el-form>

    <el-table :data="dataList" border v-loading="dataListLoading" @selection-change="selectionChangeHandle" style="width: 100%;">

      <el-table-column type="selection" header-align="center" align="center" width="50">
      </el-table-column>

      <el-table-column prop="id" header-align="center" align="center" width="80" :label="$t('common.id')">
      </el-table-column>

      <el-table-column prop="avatar" header-align="center" align="center" :label="$t('user.avatar')">
        <template slot-scope="scope">
          <span><img :src="scope.row.avatar" width="30"></span>
        </template>
      </el-table-column>

      <el-table-column prop="username" header-align="center" align="center" :label="$t('user.account')">
      </el-table-column>

      <el-table-column prop="realname" header-align="center" align="center" :label="$t('user.realname')">
      </el-table-column>

      <el-table-column prop="email" header-align="center" align="center" :label="$t('user.email')">
      </el-table-column>

      <el-table-column prop="mobile" header-align="center" align="center" :label="$t('user.mobile')">
      </el-table-column>

      <el-table-column prop="status" header-align="center" align="center" :label="$t('common.status')">
      </el-table-column>

      <el-table-column prop="last_login_time" header-align="center" align="center" width="180" :label="$t('user.last_login_time')">
      </el-table-column>

      <el-table-column fixed="right" header-align="center" align="center" width="150" :label="$t('common.handle')">
        <template slot-scope="scope">
          <el-button v-if="isAuth('user:update')" type="text" size="small" @click="addOrUpdateHandle(scope.row.id)">
            <span class="edit">{{$t('common.update')}}</span>
          </el-button>

          <el-button v-if="isAuth('user:password')" type="text" size="small" @click="changePasswordHandle(scope.row.id)">
            <span class="warning">{{$t('user.password')}}</span>
          </el-button>

          <el-button v-if="isAuth('user:delete')" type="text" size="small" @click="deleteHandle(scope.row.id)">
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
    <change-password v-if="changePasswordVisible" ref="changePassword" @refreshDataList="getDataList"></change-password>
  </div>
</template>

<script>
  import common from '@/utils/common'
  import { exportCvsTable } from "@/utils/cvs";
  import AddOrUpdate from './add-or-update'
  import ChangePassword from './change-password'
  export default {
    extends: common,
    data () {
      return {
        model: 'user',
        dataForm: {
          userName: ''
        },
        changePasswordVisible: false,
      }
    },
    components: {
      AddOrUpdate,
      ChangePassword
    },
    activated () {
      this.getDataList()
    },
    methods: {
      // 修改密码
      changePasswordHandle (id) {
        this.changePasswordVisible = true
        this.$nextTick(() => {
          this.$refs.changePassword.init(id)
        })
      },
      exportExcel() {
        exportCvsTable(
        [
            { title: "文章标题", field: "id" },
            { title: "浏览量", field: "username" }
          ],
          this.dataList,
          "文章列表"
        );
      }
    }
  }
</script>
