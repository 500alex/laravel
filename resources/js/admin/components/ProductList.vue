<template>
  <div>
    <div class="product-list-page" v-if="tabs.list">
      <div class="page-header">
        <div class="page-header__label">{{ category.name}}</div>
        <div><button class="btn btn-primary" @click="showPage('add')">Добавить продукт</button></div>
      </div>
      <div class="page-body">
        <div class="search-product">
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Поиск продукта"
            single-line
            hide-details
          ></v-text-field>
        </div>

        <v-data-table
          :headers="headers"
          :items="products"
          :search="search"
        ></v-data-table>
      </div>
      <div class="page-dialogs">
      </div>
    </div>
    <div class="product-create-page" v-if="tabs.add">
      <div class="page-header">
        <div class="page-header__label">Добавление продукта</div>
        <div @click="showPage('list')" class="pointer"><i class="fa fa-times" aria-hidden="true"></i></div>
      </div>
    </div>
    <div class="product-create-page" v-if="tabs.edit">
      <div class="page-header">
        <div class="page-header__label">Редактирование продукта</div>
        <div @click="showPage('list')" class="pointer"><i class="fa fa-times" aria-hidden="true"></i></div>
      </div>
    </div>
  </div>
</template>

<script>
  import Config from '@/js/config.js'
  export default {
    props: {
      category:{
        type: Object,
        default () {
          return {}
        }
      }
    },
    data () {
      return {
        search: '',
        headers: [
          {
            text: 'Название продукта',
            align: 'start',
            value: 'name',
          },
          { text: 'Управление',
            value: 'options',
            align: 'end'
          },
        ],
        products: [
          {
            name: 'Frozen Yogurt'
          },

        ],
        tabs: {
          list: true,
          edit: false,
          add: false,
          preview: false
        },

      }
    },
    watch: {
      category: {
        deep: true,
        handler(){
          this.getProducts(this.category.id)
          this.showPage('list');
        }
      }
    },
    methods: {
      showPage(str){
          for(let prop in this.tabs){
            this.tabs[prop] = false
          }
          this.tabs[str] = true;
      },
      getProducts (id){
        axios.get(`${Config.api}/products?categoryId=${id}`)
          .then(({data}) => {
            this.products = data
          })
          .catch(e => {
            console.log("Error get products", e)
          })
      },
    },
    created() {


    }
  }
</script>

<style lang="scss" scoped>
  .search-product {
    margin-bottom: 22px;
  }
</style>
