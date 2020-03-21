<template>
    <div class="">
        <div class="container">
            <el-tabs v-model="message">
                <el-tab-pane :label="`未读消息(${unread.length ? unread.length : 0})`" name="first">
                    <el-table :data="unread" :show-header="false" style="width: 100%" :empty-text="$t('common.no_data')">
                        <el-table-column>
                            <template slot-scope="scope">
                                <span class="message-title">
                                  [{{scope.row.message.type}}] {{scope.row.message.title}}
                                </span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="date" width="180"></el-table-column>
                        <el-table-column width="120">
                            <template slot-scope="scope">
                                <el-button size="small" @click="handleRead(scope.row.id)">标为已读</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <div class="handle-row">
                        <el-button type="primary" @click="handleRead(0)">全部标为已读</el-button>
                    </div>
                </el-tab-pane>
                <el-tab-pane :label="`已读消息(${readed.length ? readed.length : 0})`" name="second">
                    <template v-if="message === 'second'">
                        <el-table :data="readed" :show-header="false" style="width: 100%" :empty-text="$t('common.no_data')">
                            <el-table-column>
                                <template slot-scope="scope">
                                    <span class="message-title">{{scope.row.message.title}}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="date" width="150"></el-table-column>
                            <el-table-column width="120">
                                <template slot-scope="scope">
                                    <el-button type="danger" @click="handleDel(scope.row.id)">删除</el-button>
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
                        <div class="handle-row">
                            <el-button type="danger" @click="handleDel(0)">删除全部</el-button>
                        </div>
                    </template>
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>

<script>
export default {
  name: 'tabs',
  inject:['refresh'],
  data () {
    return {
      message: 'first',
      showHeader: false,
      unread: [],
      readed: [],
      pageIndex: 1,
      pageSize: 10,
      totalPage: 0
    }
  },
  activated () {
    this.getDataList()
  },
  methods: {
    // 获取数据列表
    getDataList () {
      this.dataListLoading = true
      this.$http({
        url: this.$http.adornUrl('/user/message'),
        method: 'post',
        params: this.$http.adornParams({
          'page': this.pageIndex,
          'limit': this.pageSize
        })
      }).then(({data}) => {
        if (data && data.status === 200) {
          this.unread = data.data.unread
          this.unread_count = data.data.unread_count
          this.readed = data.data.readed.data
          this.totalPage = data.data.readed.total
        } else {
          this.unread = []
          this.readed = []
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
    currentChangeHandle (val) {
      this.pageIndex = val
      this.getDataList()
    },
    handleRead (id) {
      this.$http({
        url: this.$http.adornUrl('/message/readed'),
        method: 'post',
        data: this.$http.adornData({
          'id': id
        }),
      }).then(({data}) => {
        if (data && data.status === 200) {
          this.refresh()
        } else {
          this.$message.error(data.message)
        }
      })
    },
    handleDel (id) {
      this.$http({
        url: this.$http.adornUrl('/user/remove_message'),
        method: 'post',
        data: this.$http.adornData({
          'id': id
        }),
      }).then(({data}) => {
        if (data && data.status === 200) {
          this.refresh()
        } else {
          this.$message.error(data.message)
        }
      })
    }
  },
  computed: {
    unread_count: {
      get () { return this.$store.state.user.unread_count },
      set (val) { this.$store.commit('user/updateUnreadCount', val) }
    },
  }
}
</script>

<style>
.message-title{
    cursor: pointer;
}
.handle-row{
    margin-top: 30px;
}
</style>
