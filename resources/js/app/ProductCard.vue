<template>
  <div class="product-card">
    {{product.name}}
  </div>
</template>

<script>
  export default {
    props:{
      product: {
        default: {},
        type: Object
      }
    }
  }
</script>
