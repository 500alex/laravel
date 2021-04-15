<template>
  <div>
    <div class="product-list-page" v-if="tabs.list">
      <div class="page-header">
        <div class="page-header__label">{{ category.name}}</div>
        <div><button class="btn btn-primary" @click="showPage('add')" v-if="category.count == 0">Добавить продукт</button></div>
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
        >
          <template v-slot:item.image="{ item }">
            <div class="table-item-img" v-if="item.images.length">
              <img :src="`public/storage/${item.images[0].link}`"/>
            </div>
          </template>

          <template v-slot:item.name="{ item }">
            <div class="table-item">{{item.name}}</div>
          </template>

          <template v-slot:item.options="{ item }">
            <div class="options-block">
              <span class="mr-3" @click="editProduct(item)">
                <i class="fa fa-pencil" aria-hidden="true"></i>
              </span>
              <span @click="showDeleteProductDialog(item)">
                <i class="fa fa-trash" aria-hidden="true"></i>
              </span>

            </div>
          </template>
        </v-data-table>
      </div>
      <div class="page-dialogs">
        <v-dialog
          v-model="deleteProductDialog"
          persistent
          max-width="600px"
        >
          <v-card>
            <v-card-title>
              <span class="headline">Удаление продукта</span>
            </v-card-title>
            <v-card-text>
              Вы точно хотите удалить продукт {{ selectedProduct.name }}

            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="primary darken-1"
                text
                @click="deleteProductDialog = false"
              >
                Закрыть
              </v-btn>
              <v-btn
                color="primary darken-1"
                text
                @click="deleteProduct"
              >
                Удалить
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
    </div>
    <div class="product-create-page" v-if="tabs.add">
      <product-add
        :category="category"
        @showPage="showPage"
        @updateProducts="getProducts"/>
    </div>
    <div class="product-edit-page" v-if="tabs.edit">
      <product-edit
        :productId="selectedProduct.id"
        @showPage="showPage"
        @updateProducts="getProducts"
      />
    </div>
  </div>
</template>

<script>
  import ProductAdd from './ProductAdd.vue'
  import ProductEdit from './ProductEdit.vue'
  import Config from '@/js/config.js'
  import {mapMutations} from 'vuex'
  export default {
    components: {
      'product-edit': ProductEdit,
      'product-add': ProductAdd
    },
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
        selectedProduct: {},
        deleteProductDialog: false,
        products: [],
        search: '',
        headers: [
          {
            text: 'Фото продукта',
            align: 'start',
            value: 'image',
          },
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
        tabs: {
          list: true,
          edit: false,
          add: false,
          preview: false
        }
      }
    },
    watch: {
      category: {
        deep: true,
        handler(){
          this.getProducts(this.category.id)
          this.showPage('list');
        }
      },
    },
    methods: {
      ...mapMutations([
        "setSnackbar"
      ]),
      editProduct(item){
        this.selectedProduct = item
        this.showPage('edit')
      },
      showDeleteProductDialog(item){
        this.deleteProductDialog = true
        this.selectedProduct = item
      },
      deleteProduct(){
        axios.delete(`${Config.api}/products/${this.selectedProduct.id}`)
          .then(({data}) => {
            this.deleteProductDialog = false
            this.setSnackbar({text: 'Продукт успешно удален', color:'success',show: true})
            this.getProducts(this.category.id)
            this.$root.$emit('updateCatalog', this.category.id)
            this.selectedProduct = {}
          })
          .catch(e => {
            console.log("Error delete product", e)
          })
      },
      showPage(str){
          for(let prop in this.tabs){
            this.tabs[prop] = false
          }
          this.tabs[str] = true;
      },
      async getProducts (id){
        let res = await axios.get(`${Config.api}/products?categoryId=${id}`)
        this.products = res.data
      },

    }
  }
</script>

<style lang="scss" scoped>
  .images-list {
    display: flex;
    margin: 40px 0;
    &__item {
      width: 250px;
      margin: 0 6px;
      float: left;
      img {
        width: 100%;
        border: 1px solid #cecece;
      }
    }
  }
  .search-product {
    margin-bottom: 22px;
  }
  .width-50 {
    width: 50%;
  }
  .inputs-group {
    h5 {
      margin-bottom: 12px;
    }
  }
  .options-block {
    color: #63788A;
    font-size: 24px;
    span {
      cursor: pointer;
    }
  }
  .table-item {
    font-size: 18px;
  }
  .table-item-img {
    width: 65px;
    padding: 12px 0;
    img {
      width: 100%;
    }
  }
</style>
