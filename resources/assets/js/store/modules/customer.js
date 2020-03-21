export default {
  namespaced: true,
  state: {
    title: '',
    logo: ''
  },
  mutations: {
    updateCustomerTitle (state, customer_name) {
      state.customer_name = customer_name
    },
    updateCustomerLogo (state, customer_logo) {
      state.customer_logo = customer_logo
    }
  }
}
