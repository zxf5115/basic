<template>
  <div class="mod-home">

    <el-col :span="24">
      <el-card>
        <el-row :gutter="10">
          <el-col :sm="12" :xs="24">
            <el-col :sm="3" :xs="24">
              <img :src="avatar" class="user-avator" alt="">
            </el-col>
            <el-col :sm="21" :xs="24">
              <div class="user-info">
                <div class="random-message">
                  下午好, {{username}}, 今天又写了几个Bug
                </div>
                <div class="user-login-info">
                  {{ $t('home.lastLoginTime') }}：
                  <span id="last-login-time">{{last_login_time}}</span>
                </div>
                <div class="user-login-info">
                  {{ $t('home.lastLoginAddress') }}：
                  <span id="last-login-time">内蒙古呼和浩特市</span>
                </div>
              </div>
            </el-col>

          </el-col>
          <el-col :sm="12" :xs="24">
            <div class="user-visits">
              <el-row style="margin-bottom: .7rem">
                <el-col :offset="4" :span="4">{{ $t('home.todayIp') }}</el-col>
                <el-col :offset="4" :span="4">{{ $t('home.todayVisit') }}</el-col>
                <el-col :offset="4" :span="4">{{ $t('home.TotalVisit') }}</el-col>
              </el-row>
              <el-row>
                <el-col :offset="4" :span="4" class="num">
                  <el-link type="primary">
                    <countTo :duration="3000" :end-val="123" :start-val="0" />
                  </el-link>
                </el-col>
                <el-col :offset="4" :span="4" class="num">
                  <el-link type="primary">
                    <countTo :duration="3000" :end-val="123" :start-val="0" />
                  </el-link>
                </el-col>
                <el-col :offset="4" :span="4" class="num">
                  <el-link type="primary">
                    <countTo :duration="3000" :end-val="123" :start-val="0" />
                  </el-link>
                </el-col>
              </el-row>
            </div>
          </el-col>
        </el-row>
      </el-card>
    </el-col>

    <el-row :gutter="20">
      <el-col :span="24">
        <el-card shadow="hover" style="height:403px;">
          <div slot="header" class="clearfix">
            <span>待办事项</span>
            <el-button style="float: right; padding: 3px 0" type="text">添加</el-button>
          </div>
          <el-table :data="todoList" :show-header="false" height="304" style="width: 100%;font-size:14px;">
            <el-table-column width="40">
              <template slot-scope="scope">
                <el-checkbox v-model="scope.row.status"></el-checkbox>
              </template>
            </el-table-column>
            <el-table-column>
              <template slot-scope="scope">
                <div class="todo-item" :class="{'todo-item-del': scope.row.status}">{{scope.row.title}}</div>
              </template>
            </el-table-column>
            <el-table-column width="60">
              <template slot="title">
                <icon-svg name="edit"></icon-svg>
                <icon-svg name="delete"></icon-svg>
              </template>
            </el-table-column>
          </el-table>
        </el-card>
      </el-col>
      <el-col :span="24">
        <users/>
      </el-col>
      <el-col :span="12">
        <browser/>
      </el-col>
      <el-col :span="12">
        <system/>
      </el-col>
    </el-row>
  </div>
</template>

<script>
  import countTo from 'vue-count-to'
  import ECharts from 'vue-echarts'
  import Browser from './browser'
  import System from './system'
  import Users from './users'
  import 'echarts/lib/chart/map'
  import 'echarts/lib/chart/pie'
  import 'echarts/lib/chart/bar'
  import 'echarts/lib/chart/line'
  import 'echarts/lib/component/polar'
  import 'echarts/lib/component/tooltip'
  import 'echarts/lib/component/legend'
  import 'echarts/lib/component/title.js'

  export default {
    components: { countTo, Browser, System, Users, 'v-chart': ECharts},

    data () {
      return {
        todoList: [
          {
            title: '今天要修复100个bug',
            status: false
          },
          {
            title: '今天要修复100个bug',
            status: false
          },
          {
            title: '今天要写100行代码加几个bug吧',
            status: false
          },
          {
            title: '今天要修复100个bug',
            status: false
          },
          {
            title: '今天要修复100个bug',
            status: true
          },
          {
            title: '今天要写100行代码加几个bug吧',
            status: true
          }
        ],
        trendChart: {
          grid: {
            left: '10%',
            top: 52,
          },
          title : {
              text: '会员数据统计',
              subtext: '动态数据',
              x:'center'
          },
          legend: {
              show: true,
              orient: 'vertical',
              left: 'left',
              data: ['微信访问','公众号访问']
          },
          tooltip: {
              trigger: 'axis',
              showContent: true,
              position:function(p){
                return [p[0] + 10000000000, 0]; //不需要显示弹层信息
              },
              formatter: (parmes)=>{  //选择折线图上的坐标可以获得相关值
                let value1 = parmes[0];
                let value2 = parmes[1];
                //拿到值后,需要在哪显示可以在这操作

              }
          },
          textStyle: {
            color: "#999",
          },
          label:{
            fontSize: 44,
          },
          xAxis: {
              type: 'category', //类目的行式，原样展示
              boundaryGap: false, //两边留白策略
              offset: 1,
              axisLine:{
                show:false, //是否显示坐标轴轴线，默认显示
              },
              axisTick:{
                show: false, //是否显示坐标轴刻度
              },
              axisLabel: {
                interval: 1,   //默认1,表示【隔一个标签显示一个标签】
                showMinLabel: true, //x轴只需要展示最小的和最大的值
                showMaxLabel: true,
                fontSize: 20,
                //padding: [10, 0, 0, 0], 这里可以设置坐标轴的标签的padding值
              },
              data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
          },
          yAxis: {
              type: 'value',
              boundaryGap: false,
              axisLine:{
                show:false,
              },
              axisLabel:{
                fontSize: 22,
                formatter: '{value}%'  //格式化标签值
              },
              axisTick:{
                show: false,
              },
             lineStyle: {
              //网络线设置（只作用于非类目铀）
              show: true,
              color: "#f5f5f5",
              width: 1,
              type: "solid"
            },
            splitNumber: 5, //Y轴分格为几段
            min: value => {
              return value.min - 1; //最小显示的值
            },
            max: null, //最大显示的值
            interval: 20 //间隔
          },
          series: [{
              name: '本组合',
              type: 'line',
              silent: false,
              showSymbol: false, //不显示折线上的数据点，只在hover的时候显示
              smooth: false, //0-1之间,true时为曲线，flase为折线，线弯曲度
              color: '#e2b256',
              lineStyle:{
                width: 3, //线条宽度
              },
              data: [1,2,3,4,5,6,7,8,9,10,11,12]
          },{
            name: '上证指数',
            type: 'line',
            silent: false,
            showSymbol: false, //不显示折线上的数据点，只在hover的时候显示
            smooth: false, //0-1之间,true时为曲线，flase为折线，线弯曲度
            color: '#5eb8e2',
            data: [11,21,31,41,51,61,71,81,91,110,111,112]
          }]
        }
      }
    },
    methods: {
        //计算Y铀min,max,interval三个值，保证Y铀能够均等
        computeYaxis(data) {
          let allValues = [];

          for (let k of data) {
            for (let v of Object.values(k)) {
              if (Number(v)) {
                allValues.push(v);
              }
            }
          }
          //拿到数据当中的最小值
          const min = Number.parseFloat(Math.min(...new Set(allValues))).toFixed(2);
          //让显示在坐标上的最小值<数据当中的最小值
          const newMin = min - 1;
          //拿到数据当中的最大值
          const max = Number.parseFloat(Math.max(...new Set(allValues))).toFixed(2);

          let interval = Math.abs(
             //移动端interval必须取整数才生效
            Math.ceil(Number.parseFloat((max - min) / 5).toFixed(2))
          );
          let newMax = Number.parseFloat(newMin + interval * 5).toFixed(2);
          //存在算出来的newMax值可能小于数据当中的max值
          if (newMax < max) {
            interval++;
            newMax = Number.parseFloat(newMin + interval * 5).toFixed(2);
          }
          this.trendChart.yAxis.max = newMax;
          this.trendChart.yAxis.interval = interval;
        },
    },
    computed: {
      username: {
        get () { return this.$store.state.user.name }
      },
      avatar: {
        get () { return this.$store.state.user.avatar }
      },
      last_login_time: {
        get () { return this.$store.state.user.last_login_time }
      },
      last_login_address: {
        get () { return this.$store.state.user.last_login_address }
      }
    },
  }
</script>


<style lang="scss" scoped>


.mod-home {
  line-height: 1.5;
  .el-row {
    margin-top: -10px;
    margin-bottom: -10px;
    .el-col {
      padding-top: 10px;
      padding-bottom: 10px;
    }
  }
  .user-avator {
    width: 80px;
    height: 80px;
    border-radius: 50%;
  }
  .user-info {
    display: inline-block;
    vertical-align: middle;
    .random-message {
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }
    .user-dept,
    .user-login-info {
      color: rgba(0, 0, 0, 0.45);
      margin-bottom: 0.5rem;
      font-size: 0.8rem;
      line-height: 1.1rem;
    }
  }

  .user-visits {
    text-align: center;
    padding-right: 2rem;
    margin-top: 1rem;
    vertical-align: middle;
    .num {
      font-weight: 600;
    }
  }

  @media screen and (max-width: 1366px) {
    .user-info {
      max-width: 25rem;
    }
  }
  @media screen and (max-width: 1300px) {
    .user-info {
      max-width: 19rem;
    }
  }

  @media screen and (max-width: 1120px) {
    .user-info {
      max-width: 17rem;
    }
  }
}
</style>
