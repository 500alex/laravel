<template>
  <div>
    <div class="catalog-button">
      <div class="catalog-button__icon hide-catalog"><i class="fa fa-bars" aria-hidden="true"></i></div>
      <div class="catalog-button__icon show-catalog"><i class="fa fa-times" aria-hidden="true"></i></div>
      <div class="catalog-button__text">Каталог</div>
    </div>
    <div class="catalog-wrap">
      <div class="catalog-wrap__inner">
        <div class="catalog-content">
          <div class="catalog-content__category">
            <div class="category-item" :class="{active:selectedCategoryId === category.id}" v-for="(category,i) in getSub(0)" :key="i">
              <div class="item" @mouseover="hoverCategory(category.id)">{{ category.name }}&nbsp;&nbsp;<span>({{category.count}})</span></div>
            </div>
          </div>
          <div class="catalog-content__subcategory">
            <div class="subcategories">
              <div class="subcategory-item" v-for="sub in getSub(selectedCategoryId)" :key="sub.id">
                <div class="subcategory-item__label">
                  <a :href="getPublicPath(sub.link)">{{ sub.name }}</a>
                </div>
                <div class="subcategory-item__items" v-for="child in getSub(sub.id)" :key="child.id">
                  <a :href="getPublicPath(child.link)">{{ child.name }}</a>
                </div>
              </div>
            </div>
          </div>
          <div style="clear:both;"></div>
        </div>
      </div>
    </div>
  </div>


</template>

<script>

  import Config from '../config.js'

  export default {
    data() {
      return {
        categories: [],
        selectedCategoryId: null,
      }
    },
    methods: {
     getCategories(level = 0) {
       axios.get(`${Config.api}/categories?level=${level}`)
          .then(({data}) => {
            this.categories =  data;
            this.selectedCategoryId = this.getSub(0)[0].id
          })
          .catch(e => {
            console.log("Error get categories", e)
          })
      },
      setSelectedCategoryId (){
        this.selectedCategoryId = this.getSub(0)[0].id
      },
      getSub(level){
        return  this.categories.filter(el => {
          return el.parent_id == level
        })
      },
      hoverCategory(id){
       this.selectedCategoryId = id;
      },
      getPublicPath(link){
       if(link){
         return `${Config.public}/${link}`
       }else {
         return `${Config.public}`
       }
      }
    },
    created() {
      this.getCategories(-1);
    }
  }
</script>

<style lang="scss" scoped>
  @import '@/sass/variables';

  .catalog-button {
    width: 120px;
    height: 48px;
    background-color: $main;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: $white;
    cursor: pointer;
    font-weight: 400;
    font-style: normal;
    position: relative;

    .hide-catalog {
      display: block;
    }

    .show-catalog {
      display: none;
    }

    &.catalog-show {
      .hide-catalog {
        display: none;
      }

      .show-catalog {
        display: block;
      }
    }

    &__icon {
      margin-right: 12px;
      font-size: 20px;
    }
  }

  .catalog-wrap {
    width: 100%;
    display: none;
    background-color: rgba(0, 0, 0, 0.7);
    position: absolute;
    bottom: 0;
    left: 0;
    top: 0;
    height: 100vh;
    right: 0;
    z-index: -1;

    &.show {
      display: flex;
    }

    &__inner {
      width: 100%;
    }
  }

  .catalog-content {
    width: 100%;
    padding-top: 115px;
    background-color: $white;
    display: flex;

    &__category {
      background-color: $grey-light;
      padding-top: 48px;
      padding-bottom: 48px;
      position: relative;
      .category-item {
        height: 44px;
        border-right: 4px solid $grey-light;

        .item {
          text-decoration: none;
          color: $black;
          padding: 0 72px;
          display: flex;
          height: 100%;
          width: 100%;
          align-items: center;
          font-size: 19px;
          font-weight: 600;
        }

        &.active {
          border-right: 4px solid $main;
          background-color: $white;
        }
      }
    }

    &__subcategory {
      background-color: #fff;
      padding: 48px 72px;
flex-grow: 1;
      min-height: 100%;

    }
  }

  .subcategories {
    display: flex;
    flex-wrap: wrap;
  }

  .subcategory-item {
    width: calc(100% / 3);
    padding-bottom: 32px;

    &__label {
      a {
        text-decoration: none;
        color: $black;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;

        &:hover {
          opacity: .7;
        }
      }

    }

    &__items {
      height: 24px;
      margin-top: 12px;

      a {
        text-decoration: none;
        color: $black;
        font-size: 17px;

        &:hover {
          opacity: .7;
        }
      }

    }
  }
</style>
