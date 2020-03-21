<template>
  <div class="login-container">
    <div class="login">
      <div class="login-panel">
        <el-row class="login-form">
          <div class="header text-center">
            <h3 class="company-name-text">{{ $t('login.title') }}</h3>
            <div class="company-description-text">{{ $t('login.description') }}</div>
          </div>

          <el-form label-position="top" label-width="80px" :model="dataForm" ref="dataForm" :rules="dataRule">
            <el-form-item label="" prop="username">
              <el-input v-model="dataForm.username"
                autocomplete="on"
                name="username"
                :placeholder="$t('login.username')"
                tabindex="1">
              </el-input>
            </el-form-item>


            <el-form-item label="" prop="password">
              <el-input type="password"
                v-model="dataForm.password"
                autocomplete="on"
                name="password"
                :placeholder="$t('login.password')"
                show-password
                tabindex="2"
                @keyup.enter.native="dataFormSubmit">
              </el-input>
            </el-form-item>

            <el-form-item label="" prop="captcha">
              <el-row :gutter="20">
                <el-col :span="14">
                  <el-input v-model="dataForm.captcha" placeholder="验证码">
                  </el-input>
                </el-col>
                <el-col :span="10" class="login-captcha">
                  <img :src="dataForm.captchaPath" @click.native.prevent="getCaptcha" alt="">
                </el-col>
              </el-row>
            </el-form-item>

            <el-form-item label="" prop="keep">
              <el-checkbox v-model="dataForm.keep"
                autocomplete="on"
                name="keep"
                border>
                {{ $t('login.keep') }}
              </el-checkbox>
            </el-form-item>

            <el-form-item>
              <el-button :loading="loading" type="primary" @click.native.prevent="dataFormSubmit">
                {{ $t('login.logIn') }}
              </el-button>
            </el-form-item>

          </el-form>

          <div class="forget text-center">
            <el-link href="www.baidu.com" type="danger">{{ $t('login.forget') }}</el-link>
          </div>

          <div class="oauth">
            <div class="left">{{ $t('login.oauth') }}</div>
            <div class="right">
              <el-link class="qq" href="https://element.eleme.io" target="_blank">
                <icon-svg name="qq"></icon-svg>
              </el-link>

              <el-link class="weixin" href="https://element.eleme.io" target="_blank">
                <icon-svg name="weixin"></icon-svg>
              </el-link>
            </div>
          </div>
        </el-row>
      </div>
    </div>
  </div>
</template>



<script>
  import { getUUID } from '@/utils'
  import { isvalidUsername } from '@/utils/validate'

  export default {
    data () {
      const validateUsername = (rule, value, callback) => {
        if (!isvalidUsername(value)) {
          callback(new Error('请输入正确的邮箱'))
        } else {
          callback()
        }
      }
      const validatePassword = (rule, value, callback) => {
        if (value.length < 6) {
          callback(new Error('密码至少要6位'))
        } else {
          callback()
        }
      }
      return {
        dataForm: {
          username: '',
          password: '',
          uuid: '',
          // captcha: ''
        },
        dataRule: {
          username: [{ required: true, trigger: 'blur', validator: validateUsername }],
          password: [{ required: true, trigger: 'blur', validator: validatePassword }]
          // captcha: [
          //   { required: true, message: '验证码不能为空', trigger: 'blur' }
          // ]
        },
        loading: false,
        captchaPath: ''
      }
    },
    created () {
      this.getCaptcha()
      this.getToken()
    },
    methods: {
      // 提交表单
      dataFormSubmit () {
        this.$refs['dataForm'].validate((valid) => {
          if (valid)
          {
            this.loading = true

            this.$http(
            {
              url: this.$http.adornUrl('/user/login'),
              method: 'post',
              data: this.$http.adornData({
                'username': this.dataForm.username,
                'password': this.dataForm.password,
                // 'uuid': this.dataForm.uuid,
                // 'captcha': this.dataForm.captcha
              })
            }).then(({data}) =>
            {
              this.loading = false

              if (data && data.status === 200)
              {
                this.$router.replace({ name: 'home' })
              }
              else
              {
                this.getCaptcha()
                this.$message.error(data.message)
              }
            }).catch((error) =>
            {
              this.loading = false

              this.$message({
                message: error,
                type: 'error',
                duration: 2000
              })
            })
          }
        })
      },
      // 获取验证码
      getCaptcha () {
        this.dataForm.uuid = getUUID()
        this.captchaPath = this.$http.adornUrl(`/captcha.jpg?uuid=${this.dataForm.uuid}`)
      },
      getToken () {
        this.$http({
          url: this.$http.adornUrl('/token/certification'),
          method: 'post',
          data: this.$http.adornData({
            'username': process.env.MIX_USERNAME,
            'password': process.env.MIX_PASSWORD
          })
        }).then(({data}) => {
          if (data && data.status === 200) {
            this.$cookie.set('token', data.data)
          } else {
            this.$message.error(data.msg)
          }
        })
      }
    }
  }
</script>

<style lang="scss">
  $bg:#2d3a4b;
  $dark_gray:#889aa4;
  $light_gray:#eee;

  .login-container {
    position: fixed;
    height: 100%;
    width: 100%;
    background: url('/images/background/bg-img-1.jpg');
    background-repeat:no-repeat;
    background-size: cover;
    background-position: center center;

    .svg-container {
      padding: 6px 5px 6px 15px;
      color: $dark_gray;
      vertical-align: middle;
      width: 30px;
      display: inline-block;
    }

    .login-form {
      position: absolute;
      top: 0;
      right: 0;
      padding: 10px 60px 180px;
      width: 400px;
      min-height: 100%;
      background-color: #fff;
      box-shadow: #1b1919 0px 0px 50px
    }

    .login-form .header {
      padding: 50px 0 10px 0;
    }

    .login-form .company-name-text {
      font-size: 24px;
      font-weight: 400;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .login-form .company-description-text {
      margin-bottom: 20px;
    }

    .login-form .el-checkbox {
      width: 100%;
    }

    .login-form .el-button--primary {
      width: 100%;
    }

    .login-form .el-form-item.is-success .el-input__inner, .el-form-item.is-success .el-input__inner:focus, .el-form-item.is-success .el-textarea__inner, .el-form-item.is-success .el-textarea__inner:focus{
      border-color: #66b1ff !important;
    }

    .login-form .forget {
      font-size: 13px;
    }

    .login-form .oauth {
      font-size: 13px;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid rgba(0, 0, 0, 0.07);
    }

    .login-form .oauth .qq {
      color: red;
      margin-right: 5px;
    }

    .login-form .oauth .weixin {
      color: green;
    }

    .el-checkbox.is-bordered.el-checkbox--medium{padding:9px 20px 7px 10px;border-radius:4px;height:36px}
  }
</style>
