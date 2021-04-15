import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from "@/js/admin/views/Home.vue"
import Settings from "@/js/admin/views/Settings.vue"

export default new VueRouter({
  mode: 'history',
  routes: [
    {
      path:'/admin',
      component: Home,
      name: 'home'
    },
    {
      path:'/admin/settings',
      component: Settings,
      name: 'settings'
    }
  ]
})
