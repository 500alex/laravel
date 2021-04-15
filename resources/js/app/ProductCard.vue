<template>

    <div class="cart">
      <div class="cart__image">
        <img :src="`public/storage/${product.images[0].link}`"/>
      </div>
      <div class="cart__text">{{product.name}}</div>
      <div class="cart__star"></div>
      <div class="cart__price">147<span>P</span></div>
      <div class="cart__options">
        <div class="option">
          <div class="option__button">
            <button>В корзину</button>
          </div>
          <div class="option__like"></div>
          <div class="option__match"></div>
        </div>
      </div>
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

<style lang="scss" scoped>
  @import '@/sass/variables.scss';
  .cart {
    width: 200px;
    height: 380px;
    display: flex;
    flex-direction: column;
    &__image {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 200px;
      width: 100%;
      overflow: hidden;
      img {
        width: 100%;
      }
    }
    &__text {
      font-size: 16px;
      color: #333;
      margin: 12px 0;
      height: 48px;
      overflow: hidden;
    }
    &__price {
      font-size: 20px;
      margin: 6px 0;
    }
  }
  .option {
    &__button {
      button {
        width: 109px;
        height: 40px;
        background-color: $primary;
        font-family: Roboto,Helvetica,Arial,sans-serif;
        color: $white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: all .4s;
        cursor: pointer;
        border-radius: .4rem;
        outline: none;
        &:hover {
          background-color: #bd0f2c;
        }
      }
    }
  }
</style>
