<template>
  <div class="mod-config">
    <el-form :inline="true" :model="dataForm" @keyup.enter.native="getDataList()">
      <el-form-item>
        <el-input v-model="dataForm.title" :placeholder="$t('common.title')" clearable></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="getDataList()">{{ $t('common.search') }}</el-button>

        <el-button v-if="isAuth('project:customer:create')" type="primary" @click="addOrUpdateHandle()">{{ $t('common.create') }}</el-button>

        <el-button v-if="isAuth('project:customer:delete')" type="danger" @click="deleteHandle()" :disabled="dataListSelections.length <= 0">{{ $t('common.batch_delete') }}</el-button>
      </el-form-item>
    </el-form>

    <!-- <div class="shop-brand-list clearfix" v-loading="dataListLoading">
      <el-card class="brand" v-for="item in dataList" :key="item.id" shadow="hover">

        <img :alt="item.title" :src="item.picture" class="logo"/>

        <div class="body">
          <span>{{ item.title }}</span>
          <div class="bottom clearfix">
            <el-button v-if="isAuth('project:customer:update')" type="text" class="button" size="small" @click="addOrUpdateHandle(item.id)">
              <span class="edit">{{$t('common.update')}}</span>
            </el-button>
            <el-button v-if="isAuth('project:customer:delete')" type="text" class="button" size="small" @click="deleteHandle(item.id)">
              <span class="delete">{{$t('common.delete')}}</span>
            </el-button>
          </div>
        </div>
      </el-card>
    </div> -->

    <el-table :data="dataList" border v-loading="dataListLoading" @selection-change="selectionChangeHandle" style="width: 100%;">
      <el-table-column type="selection" header-align="center" align="center" width="50">
      </el-table-column>

      <el-table-column prop="id" header-align="center" align="center" width="80" :label="$t('common.id')">
      </el-table-column>

      <el-table-column prop="title" header-align="center" align="center" :label="$t('customer.title')">
      </el-table-column>

      <el-table-column prop="picture" align="center" :label="$t('customer.picture')">
        <template slot-scope="scope">
          <el-image width="30" :src="scope.row.picture">
            <div slot="error" class="image-slot">
              <i class="el-icon-picture-outline"></i>
            </div>
          </el-image>
        </template>
      </el-table-column>

      <el-table-column prop="contact" header-align="center" align="center" :label="$t('customer.contact')">
      </el-table-column>

      <el-table-column prop="mobile" header-align="center" align="center" :label="$t('customer.mobile')">
      </el-table-column>

      <el-table-column fixed="right" header-align="center" align="center" width="150" :label="$t('common.handle')">
        <template slot-scope="scope">
          <el-button v-if="isAuth('project:customer:update')" type="text" size="small" @click="addOrUpdateHandle(scope.row.id)">
            <span class="edit">{{$t('common.update')}}</span>
          </el-button>
          <el-button v-if="isAuth('project:customer:delete')" type="text" size="small" @click="deleteHandle(scope.row.id)">
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
        model: 'project/customer',
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
<style lang="scss">
.shop-brand-list {
  .brand {
    float: left;
    margin-right: 12px;
    margin-bottom: 12px;
    .logo {
      border: 1px solid #dddddd;
      width: 88px;
      height: 88px;
    }
    .body {
      padding: 10px 0;
    }
    .icon {
      margin-right: 5px;
    }
    .bottom {
      padding-top: 10px;
    }
  }
  .el-card__body {
    text-align: center;
  }
}
</style>
