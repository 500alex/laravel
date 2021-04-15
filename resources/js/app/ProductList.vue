<template>
  <div class="product-list">
  <card :product="product" v-for="(product,i) in products" :key="i"/>
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
<style lang="scss" scoped>
  .product-list {
   display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 22px;
  }
</style>
