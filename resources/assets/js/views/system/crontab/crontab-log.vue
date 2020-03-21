<template>
  <el-dialog
    title="日志列表"
    :close-on-click-modal="false"
    :visible.sync="visible"
    width="75%">
    <el-form :inline="true" :model="dataForm" @keyup.enter.native="getDataList()">
      <el-form-item>
        <el-input v-model="dataForm.id" placeholder="任务ID" clearable></el-input>
      </el-form-item>
      <el-form-item>
        <el-button @click="getDataList()">查询</el-button>
      </el-form-item>
    </el-form>
    <el-table :data="dataList" border v-loading="dataListLoading" height="460" style="width: 100%;">

      <el-table-column prop="id" header-align="center" align="center" width="80" label="日志编号">
      </el-table-column>

      <el-table-column prop="crontab.title" header-align="center" align="center" label="任务标题">
      </el-table-column>

      <el-table-column prop="crontab.remark" header-align="center" align="center" label="备注">
      </el-table-column>

      <el-table-column prop="crontab.cron_title" header-align="center" align="center" label="cron脚本名称">
      </el-table-column>

      <el-table-column prop="crontab.cron_params" header-align="center" align="center" label="cron脚本参数">
      </el-table-column>

      <el-table-column prop="crontab.cron_expression" header-align="center" align="center" label="cron时间表达式">
      </el-table-column>

      <el-table-column prop="timestamp" header-align="center" align="center" label="耗时(毫秒)">
      </el-table-column>

      <el-table-column prop="status" header-align="center" align="center" label="状态">
      </el-table-column>

      <el-table-column prop="create_time" header-align="center" align="center" width="180" label="执行时间">
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
  </el-dialog>
</template>

<script>
  export default {
    data () {
      return {
        visible: false,
        dataForm: {
          id: ''
        },
        dataList: [],
        pageIndex: 1,
        pageSize: 10,
        totalPage: 0,
        dataListLoading: false
      }
    },
    methods: {
      init (id) {
        this.crontab_id = id;
        this.visible = true
        this.getDataList()
      },
      // 获取数据列表
      getDataList ()
      {
        this.dataListLoading = true
        this.$http({
          url: this.$http.adornUrl('/crontab/logs'),
          method: 'post',
          params: this.$http.adornParams({
            'page': this.pageIndex,
            'limit': this.pageSize,
            'id': this.crontab_id
          })
        }).then(({data}) =>
        {
          if (data && data.status === 200)
          {
            this.dataList = data.data.data
            this.totalPage = data.data.total
          }
          else
          {
            this.dataList = []
            this.totalPage = 0
          }
          this.dataListLoading = false
        })
      },
      // 每页数
      sizeChangeHandle (val) {
        this.pageSize = val
        this.pageIndex = 1
        this.getDataList()
      },
      // 当前页
      currentChangeHandle (val)
      {
        this.pageIndex = val
        this.getDataList()
      },
      // 失败信息
      showErrorInfo (id)
      {
        this.$http({
          url: this.$http.adornUrl(`/sys/scheduleLog/info/${id}`),
          method: 'get',
          params: this.$http.adornParams()
        }).then(({data}) =>
        {
          if (data && data.status === 200)
          {
            this.$alert(data.log.error)
          }
          else
          {
            this.$message.error(data.msg)
          }
        })
      }
    }
  }
</script>
