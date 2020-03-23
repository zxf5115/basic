export default {
  route: {
    dashboard: '首页',
    article: '文章管理',
    createArticle: '添加文章',
    editArticle: '编辑文章',
    articleList: '文章列表',
    category: '栏目列表',
    createCategory: '增加栏目',
    editCategory: '编辑栏目',
    categoryList: '栏目列表',
    errorPages: '错误页面',
    page401: '401',
    page404: '404',
    Users: '用户管理',
    User: '个人中心',
    UserList: '用户列表',
    MessageList: '所有反馈',
    Messages: '系统反馈',
    FieldList: '字段列表'
  },
  lang: {
    set_lang_success: '切换语言成功！'
  },
  common: {
    handle_success: '操作成功',
    handle_error: '操作失败',
    empty_data: '暂无数据',
    enable: '正常',
    disable: '禁用',
    confirm: '确定',
    cancel: '取消',
    search: '查找',
    reset: '重置',
    more: '更多',
    create: '添加',
    update: '修改',
    view: '详情',
    permission: '权限',
    delete: '删除',
    batch_delete: '批量删除',
    export: '导出',
    import: '导入',
    startTime: '开始日期',
    endTime: '结束日期',
    logout: '退出',
    handle: '操作',
    for: '进行',
    prompt: '提示',
    to_determine_the: '确定对',

    id: '编号',
    title: '标题',
    content: '内容',
    sort: '排序',
    status: '状态',
    start_time: '开始时间',
    end_time: '结束时间',
    create_time: '创建时间',
    update_time: '更新时间',
    no_data: '暂无数据',
  },
  navbar: {
    logOut: '退出登录',
    dashboard: '首页',
    userCenter: '用户中心'
  },
  login: {
    title: '协呈服务',
    description: '欢迎您使用本系统',
    logIn: '登录',
    username: '用户名',
    password: '密码',
    keep: '记住我',
    forget: '忘记了密码 ? ',
    oauth: '第三方登录',
  },
  tagsView: {
    refresh: '刷新',
    close: '关闭',
    closeOthers: '关闭其它',
    closeAll: '关闭所有'
  },
  home: {
    lastLoginTime: '上次登录时间',
    lastLoginAddress: '上次登录地址',
    todayIp: '今日IP',
    todayVisit: '今日访问',
    TotalVisit: '总访问量',
  },

  user: {
    create: '添加用户',
    update: '修改用户',
    view: '用户详情',
    delete: '删除用户',
    export: '导出用户',
    reset: '重置密码',
    change: '修改密码',
    avatar: '用户头像',
    account: '登录账户',
    username: '用户名称',
    realname: '真实名称',
    password: '密码',
    comfirm_password: '确认密码',
    email: '邮箱',
    mobile: '手机号码',
    last_login_time: '最后登录时间',

    rules: {
      username: {
        require: '登录账户不能为空',
        length: '长度在 1 到 32 个字符',
      },
      password: {
        require: '登录密码不能为空',
        length: '长度在 1 到 32 个字符',
      },
      comfirmPassword: {
        require: '确认密码不能为空',
        equal: '确认密码与密码输入不一致',
      },
      avatar: {
        require: '用户头像不能为空',
        picture_type: '上传头像图片只能是 JPG 或 PNG 格式',
        picture_size: '上传头像图片大小不能超过 2MB',
      },
      realname: {
        require: '真实名称不能为空',
        length: '长度在 1 到 32 个字符',
      },
      email: {
        format: '邮箱格式错误',
      },
      mobile: {
        format: '手机号码格式错误',
      },
      role: {
        require: '角色不能为空',
      },
    },
  },

  role: {
    title: '角色名称',
    description: '备注',

    rules: {
      title: {
        require: '角色名称不能为空',
        length: '长度在 1 到 50 个字符',
      },
    },
  },

  menu: {
    title: '菜单标题',
    icon: '图标',
    type: '菜单类型',
    url: '菜单URL',
  },



  message: {
    receiver: '接收者',
    author: '发送者',
    type: '消息类型',
    user: '用户',
    role: '角色',
    accept_type: '接收类型',
    accept_user: '接收用户',
    accept_role: '接收角色',
    rules: {
      title: {
        require: '标题不能为空',
        length: '长度在 1 到 100 个字符',
      },
      type: {
        require: '消息类型不能为空',
      },
      content: {
        require: '内容不能为空',
        length: '长度在 1 到 1000 个字符',
      },
      accept: {
        require: '接受者不能为空',
      },
    },
  },



  advertising: {
    title: '广告标题',
    description: '广告描述',
    picture: '广告图片',
    valid_time: '有效时间',
    type: '广告类型',
    rules: {
      title: {
        require: '广告标题不能为空',
        length: '长度在 1 到 30 个字符',
      },
      type: {
        require: '广告类型不能为空',
      },
      valid_time: {
        require: '有效时间不能为空',
      },
      picture: {
        require: '广告图片不能为空',
      },
    },
  },



  dictionary: {
    title: '字典标题',
    code: '字典代码',
    description: '字典描述',
    picture: '广告图片',
    valid_time: '有效时间',
    rules: {
      title: {
        require: '广告标题不能为空',
        length: '长度在 1 到 30 个字符',
      },
      type: {
        require: '广告类型不能为空',
      },
      valid_time: {
        require: '有效时间不能为空',
      },
      picture: {
        require: '广告图片不能为空',
      },
    },
  },
}
