<template>
  <div class="menu-block">
    <div class="item-block" @dragstart="dragStart($event)" :class="{'active':category.active}"
         v-for="category in currentCategories" :key="category.id">
      <div class="item" draggable="true" :data-category-id="category.id">
        <div class="item__has-childe">
          <div v-if="category.count > 0" @click="showList(category)" class="pointer">
            <i class="fa fa-plus" aria-hidden="true" v-if="!category.active"></i>
            <i class="fa fa-minus" aria-hidden="true" v-if="category.active"></i>
          </div>
        </div>
        <div class="item__img d-flex">
          <span class="folder mr-3" @dragover.prevent @drop="drop(category.id)">
            <i class="fa fa-folder"
               aria-hidden="true"
               :data-category-id="category.id">
            </i>
          </span>
          <options :item="category"/>
        </div>
        <div class="item__name" @click="showList(category)">{{category.name }}&nbsp;&nbsp;<span v-if="category.productCount > 0">({{category.productCount}})</span>
        </div>
      </div>
      <div class="item-list">
        <menu-item :level="category.id" :categories="categories"/>
      </div>
    </div>

  </div>
</template>

<script>
  import Options from "./Options.vue";

  export default {
    name: 'menu-item',
    components: {Options},
    props: {
      level: {
        type: Number,
        default: 0
      },
      categories: {
        type: Array,
        default: function () {
          return []
        }
      }

    },
    computed: {
      currentCategories () {
        return this.categories.filter(el => {
          return el.parent_id == this.level
        })
      }
    },
    methods: {
      showList(category) {
        if (!category.active) {
          this.$set(category, 'active', true)
        } else {
          category.active = false;
        }
        this.selectCategory(category);
      },
      dragStart(event) {
        this.$root.$emit('dragStart', event.target.dataset.categoryId)
      },
      drop(i) {
        this.$root.$emit('drop', i)
      },
      selectCategory(category){
        this.$root.$emit('selectCategory', category)
      }
    },
  }
</script>

<style lang="scss" scoped>
  @import '@/sass/helpers.scss';
  @import '@/sass/variables.scss';

  .item-block {
    &.active {
      & > .item-list {
        height: auto;
      }

    }

    .item {
      @extend .d-flex, .align-items-center;

      &__has-childe {
        position: relative;
        left: -5px;
        color: $grafit-light;
        width: 15px;
      }

      &__img {
        font-size: 25px;
        color: $grafit-light;
        @extend .mr-3;

        span {
          cursor: move;
        }
      }

      &__name {
        cursor: pointer;
      }
    }

    .item-list {
      padding-left: 32px;
      height: 0px;
      overflow: hidden;
      transition: all .4s;
    }

    .item-space {
      width: 75px;
      height: 17px;
      /*background: red;*/
      position: absolute;
      left: -6px;
      line-height: 0;
      top: -23px;
    }

    .menu-block {
      /*position: relative;*/
      &:before {
        content: "";
        width: 1px;
        height: 100%;
        left: -22px;
        position: relative;
        background: #cecece;
      }
    }
  }
</style>
