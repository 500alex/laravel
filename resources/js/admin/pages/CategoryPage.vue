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
              @input="setCategoryLink"
              clearable
            ></v-text-field>

            <v-text-field
              label="ссылка категории"
              required
              solo
              v-model="newCategoryLink"
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
              :disabled="!newCategoryName || !newCategoryLink"
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
  import {mapMutations} from 'vuex'
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
        newCategoryLink: '',
        selectedCategory: {}

      }
    },
    computed: {
      currentCategories (){
        return this.categories.filter(el => {
          return el.parent_id === 0
        })
      },
    },
    methods: {
      ...mapMutations([
        'setSnackbar'
      ]),
      setCategoryLink(){
        console.log(111)
        this.newCategoryLink = this.convert(this.newCategoryName);
      },
      convert(word){
        var answer = "";
        var a = {}

        a["Ё"]="YO";a["Й"]="I";a["Ц"]="TS";a["У"]="U";a["К"]="K";a["Е"]="E";a["Н"]="N";a["Г"]="G";a["Ш"]="SH";a["Щ"]="SCH";a["З"]="Z";a["Х"]="H";a["Ъ"]="'";
        a["ё"]="yo";a["й"]="i";a["ц"]="ts";a["у"]="u";a["к"]="k";a["е"]="e";a["н"]="n";a["г"]="g";a["ш"]="sh";a["щ"]="sch";a["з"]="z";a["х"]="h";a["ъ"]="'";
        a["Ф"]="F";a["Ы"]="I";a["В"]="V";a["А"]="a";a["П"]="P";a["Р"]="R";a["О"]="O";a["Л"]="L";a["Д"]="D";a["Ж"]="ZH";a["Э"]="E";
        a["ф"]="f";a["ы"]="i";a["в"]="v";a["а"]="a";a["п"]="p";a["р"]="r";a["о"]="o";a["л"]="l";a["д"]="d";a["ж"]="zh";a["э"]="e";
        a["Я"]="Ya";a["Ч"]="CH";a["С"]="S";a["М"]="M";a["И"]="I";a["Т"]="T";a["Ь"]="'";a["Б"]="B";a["Ю"]="YU";
        a["я"]="ya";a["ч"]="ch";a["с"]="s";a["м"]="m";a["и"]="i";a["т"]="t";a["ь"]="'";a["б"]="b";a["ю"]="yu";

        for (let i = 0; i < word.length; ++i){
          answer += a[word[i]] === undefined ? word[i] : a[word[i]];
        }
        return answer.toLowerCase().split(' ').join('_');
      },
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
          link: this.newCategoryLink,
          parentId: 0
        })
          .then(() => {
            this.setSnackbar({text:'Категория успешно создана',color:'success',show: true})
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
        if(typeof category == 'object'){
          this.selectedCategory = category
        }else {
          this.selectedCategory = this.categories.filter(el => {
            return el.id === category
          })[0]
        }

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

