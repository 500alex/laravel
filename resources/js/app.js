import "normalize.css"
import "../fonts/font-awesome/css/font-awesome.min.css"
import "bootstrap/dist/css/bootstrap.min.css"
import json from "../json/test"

console.log(json.title)

import "@/sass/style.scss"
import Vue from "vue"
import vuetify from './plugins/vuetify.js'
import bootstrap from "bootstrap"

import "./typeScript/test.ts"
import "./scripts"

/////////////////////////////////////////
import AppMenu from "./app/AppMenu.vue"
import ProductList from "./app/ProductList.vue"
import AdminLayout from "./admin/AdminLayout.vue"

////////////////////////////////////////

import store from './store.js'

window.axios = require("axios")

window.app = new Vue({
  el: "#app",
  vuetify,
  store,
  components: {
    "app-menu": AppMenu,
    "admin-layout": AdminLayout,
    "product-list": ProductList
  }
})
