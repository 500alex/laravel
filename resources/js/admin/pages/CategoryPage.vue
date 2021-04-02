<template>
  <div class="page catetories">
    <div class="page-left">
      <div class="menu-wrapper">
        <div>
          <span @dragover.prevent @drop="drop($event)" data-category-id="0">Каталог</span>
          <span class="catalog-options">
            <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <i class="fa fa-cog" v-bind="attrs" v-on="on" aria-hidden="true"></i>
        </template>
        <v-list>

          <v-list-item link>
            <v-list-item-title @click="showCategoryDialog">Добавить категорию</v-list-item-title>
          </v-list-item>

          <v-list-item link>
            <v-list-item-title @click="showOrderCategoryDialog">Изменить порядок категорий</v-list-item-title>
          </v-list-item>

        </v-list>
      </v-menu>
          </span>
        </div>
        <div class="list-wrapper">
          <menu-item :categories="categories"/>
        </div>
      </div>
    </div>
    <div class="page-right">
      <div class="page-right__body">
        <product-list :category="selectedCategory"/>
      </div>
    </div>

    <div class="page__dialogs">
      <v-dialog
        v-model="addCategoryDialog"
        persistent
        max-width="600px"
      >
        <v-card>
          <v-card-title>
            <span class="headline">Создание категории</span>
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Название категории"
              required
              solo
              v-model="newCategoryName"
              clearable
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary darken-1"
              text
              @click="addCategoryDialog = false"
            >
              Закрыть
            </v-btn>
            <v-btn
              color="primary darken-1"
              text
              @click="saveNewCategoryName"
              :disabled="!newCategoryName"
            >
              Сохранить
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog
        v-model="orderCategoryDialog"
        persistent
        max-width="600px"
      >
        <v-card>
          <v-card-title>
            <span class="headline">Изменить порядок категорий</span>
          </v-card-title>
          <v-card-text>
            <div class="order-list">
              <div class="order-item" v-for="(item,i) in currentCategories" :key="i">
                <span class="order-item__name">{{item.name}}</span>
                <span class="order-item__input">
                  <v-text-field
                    label="Позиция категории"
                    required
                    solo
                    v-model="item.position"
                    clearable
                  ></v-text-field>
                </span>
              </div>
            </div>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary darken-1"
              text
              @click="orderCategoryDialog = false"
            >
              Закрыть
            </v-btn>
            <v-btn
              color="primary darken-1"
              text
              @click="saveNewCatalogOrder"
            >
              Сохранить
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>

  </div>

</template>

<script>
  import Config from '../../config.js'
  import MenuItem from '../components/MenuItem.vue'
  import ProductList from '../components/ProductList.vue'

  export default {
    components: {
      'menu-item': MenuItem,
      'product-list': ProductList
    },
    data() {
      return {
        categories: [],
        categoryId: null,
        addCategoryDialog: false,
        orderCategoryDialog: false,
        newCategoryName: '',
        selectedCategory: {}

      }
    },
    computed: {
      currentCategories (){
        return this.categories.filter(el => {
          return el.parent_id === 0
        })
      }
    },
    methods: {
      getCategories(id = null) {
        axios.get(`${Config.api}/categories?level=-1`)
          .then(({data}) => {
            if(id){
              data.forEach(el => {
                if(el.id === id){
                  this.$set(el, 'active', true)
                  if(el.parent_id != 0) {
                    data.forEach(par => {
                      if(par.id === el.parent_id){
                        this.$set(par, 'active', true)
                      }
                    })
                  }
                }
              })
            }
            this.categories = data

          })
          .catch(e => {
            console.log("Error get categories", e)
          })
      },
      getDefaultCategory(){
        axios.get(`${Config.api}/categories?level=0`)
          .then(({data}) => {
            this.selectedCategory = data[0]
          })
          .catch(e => {
            console.log("Error get categories", e)
          })
      },
      showCategoryDialog() {
        this.addCategoryDialog = true
      },
      saveNewCategoryName() {
        axios.post(`${Config.api}/categories`, {
          name: this.newCategoryName,
          parentId: 0
        })
          .then(() => {
            this.addCategoryDialog = false
            this.newCategoryName = ''
            this.getCategories();
            // this.$root.$emit('updateCatalog', 0)
          })
          .catch(e => {
            console.log("Error create new category", e)
          })
      },
      drop(event) {
        this.$root.$emit('drop', event.target.dataset.categoryId)
      },
      showOrderCategoryDialog() {
        this.orderCategoryDialog = true;
      },
      saveNewCatalogOrder() {
        axios.put(`${Config.api}/drag/order`, {
          list: this.categories
        })
          .then(() => {
            this.$root.$emit('updateCatalog', 0)
          })
          .catch(e => {
            console.log("Error update order categories", e)
          })
        this.orderCategoryDialog = false;
      },
    },
    created() {
      this.getCategories();
      this.getDefaultCategory();
      this.$root.$on("dragStart", (id) => {
        this.categoryId = id
      })
      this.$root.$on("drop", (id) => {
        if (this.categoryId !== null && (this.categoryId !== id)) {
          axios.put(`${Config.api}/drag`, {
            categoryId: this.categoryId,
            parentId: id
          })
            .then(() => {
              this.$root.$emit('updateCatalog', id)
            })
            .catch(e => {
              console.log("Error get categories", e)
            })
        }
      })
      this.$root.$on('updateCatalog', (id) => {
        this.getCategories(id);
      })
      this.$root.$on('selectCategory', (category)=>{
        this.selectedCategory = category
      })

    },
  }

</script>

<style lang="scss" scoped>



  .page {
    display: flex;
    height: 100%;
  }
  .page-left {
    background: #f7f7f7;
    padding: 22px;
    border-right: 1px solid #cecece;
    height: 100%;

  }
  .page-right {
    padding: 22px;
    height: 100%;
    flex-grow: 1;
  }

  .menu-wrapper {
    font-size: 18px;
  }

  .order-item {
    display: flex;
    align-items: center;
    font-size: 22px;
    justify-content: space-between;
    margin-bottom: 12px;
    &__input {
      height: 50px;
    }
  }

  .list-wrapper {
    padding-left: 19px;
    border-left: 1px solid #cecece;
    position: relative;
  }

  .catalog-options {
    cursor: pointer;
    margin-left: 6px;
    color: rgba(105, 105, 105, .87);

  }
</style>

