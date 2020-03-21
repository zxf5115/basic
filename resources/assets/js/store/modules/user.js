export default {
  namespaced: true,
  state: {
    id: 0,
    name: '',
    avatar: '/images/avatar.png',
    unread_count: '',
    last_login_time: '',
    last_login_address: '',
  },
  mutations: {
    updateId (state, id) {
      state.id = id
    },
    updateName (state, name) {
      state.name = name
    },
    updateAvatar (state, avatar) {
      state.avatar = avatar
    },
    updateUnreadCount (state, count) {
      state.unread_count = count
    },
    updateLastLoginTime (state, time) {
      state.last_login_time = time
    },
    updateLastLoginAddress (state, address) {
      state.last_login_address = address
    }
  }
}
