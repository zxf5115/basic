<template>
  <el-card>
    <v-chart theme="ovilia-green" :options="browser" :style="{margin: 'auto'}"/>
  </el-card>
</template>

<script>
  import ECharts from 'vue-echarts'
  import 'echarts/lib/chart/pie'
  import 'echarts/lib/component/tooltip'
  import 'echarts/lib/component/legend'
  import 'echarts/lib/component/title.js'

  export default {
    components: { 'v-chart': ECharts},

    data () {
      return {
        browser: {
          title : {
              text: '访问用户浏览器',
              x:'center'
          },
          tooltip : {
              trigger: 'item',
              formatter: "{a} <br/>{b} : {c} ({d}%)"
          },
          legend: {
              show: true,
              orient: 'vertical',
              left: 'left',
              data: ['微信访问','公众号访问','扫码进入','分享进入','搜索访问']
          },
          series : [
            {
              name: '访问来源',
              type: 'pie',
              radius : '55%',
              center: ['50%', '60%'],
              color: ["#edce91", "#deab6a", "#ffe2ac", "#fcefd6","#77623b"],
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
