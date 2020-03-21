<template>
  <el-card>
    <v-chart theme="ovilia-green" :options="users" :style="{width:'100%',height:'500px'}"/>
  </el-card>
</template>

<script>
  import ECharts from 'vue-echarts'
  import 'echarts/lib/chart/bar'
  import 'echarts/lib/component/tooltip'
  import 'echarts/lib/component/legend'
  import 'echarts/lib/component/title.js'

  export default {
    components: { 'v-chart': ECharts},

    data () {
      return {
        users: {
          title : {
              text: '每日新增用户量',
              subtext: '动态数据',
              x:'left'
          },
          tooltip : {
              trigger: 'item',
              formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
              show: true,
              orient: 'vertical',
              left: 'left',
              data: ['新增用户量','总用户量']
          },
          xAxis : [
            {
              type : 'category',
              data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月',],
              axisTick: {
                alignWithLabel: true
              }
            }
          ],
          yAxis : [
            {
              type : 'value'
            }
          ],
          series : [
              {
                  name: '访问来源',
                  type: 'bar',
                  radius : '55%',
                  center: ['50%', '60%'],
                  color: ["#aa21as", "#ff2211"],
                  data:[
                      {value:335, name:'微信访问'},
                      {value:310, name:'公众号访问'},
                      {value:234, name:'扫码进入'},
                      {value:135, name:'分享进入'},
                      {value:1548, name:'搜索访问'}
                  ],
                  itemStyle: {
                      emphasis: {
                          shadowBlur: 10,
                          shadowOffsetX: 0,
                          shadowColor: 'rgba(0, 0, 0, 0.5)'
                      }
                  }
              }
          ]
        }
      }
    },
    methods: {
    },
  }
</script>


<style lang="scss" scoped>

</style>
