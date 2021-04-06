<template>
  <div class="product-list">
      <div v-for="(product,i) in products" :key="i">
        <card :product="product"/>
      </div>
  </div>
</template>

<script>
  import Config from '@/js/config.js'
  import Card from './ProductCard.vue'
  export default {
    components: {
      "card": Card
    },
    props: {
      category:{
        type: String,
        default: ''
      }
    },
    data (){
      return {
        products: []
      }
    },
    methods: {
      getProducts(){
        axios.get(`${Config.api}/products?categoryId=${parseInt(this.category)}`)
          .then(({data}) => {
            this.products = data
          })
          .catch(e => {
            console.log("Error get products", e)
          })
      }
    },
    created() {
      this.getProducts()
    }
  }
</script>
