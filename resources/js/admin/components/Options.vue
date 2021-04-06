<template>

  <div class="options">
    <div class="options__menu">
      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <i class="fa fa-cog" v-bind="attrs" v-on="on" aria-hidden="true"></i>
        </template>
        <v-list>
          <v-list-item link>
            <v-list-item-title @click="showEditDialog">Редактировать</v-list-item-title>
          </v-list-item>

          <v-list-item link v-if="item.count === 0">
            <v-list-item-title @click="goName('product-page')">Добавить продукт</v-list-item-title>
          </v-list-item>

          <v-list-item link v-if="item.count == 0">
            <v-list-item-title @click="showDeleteDialog">Удалить категорию</v-list-item-title>
          </v-list-item>

          <v-list-item link v-if="item.count > 1">
            <v-list-item-title @click="showOrderDialog">Порядок дочерних элементов</v-list-item-title>
          </v-list-item>

          <v-list-item link v-if="item.productCount == 0">
            <v-list-item-title @click="showAddCategoryDialog">Добавить подкатегорию</v-list-item-title>
          </v-list-item>


        </v-list>
      </v-menu>
    </div>
    <div class="options__dialogs">
      <v-dialog
        v-model="editDialog"
        persistent
        max-width="600px"
      >
        <v-card>
          <v-card-title>
            <span class="headline">Редактирование категории</span>
          </v-card-title>
          <v-card-text>
            <v-text-field
              label="Категория"
              required
              solo
              v-model="editName"
              clearable
            ></v-text-field>
            <v-text-field
              label="Ссылка"
              required
              solo
              v-model="editLink"
              clearable
            ></v-text-field>
          </v-card-text>


          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary darken-1"
              text
              @click="editDialog = false"
            >
              Закрыть
            </v-btn>
            <v-btn
              color="primary darken-1"
              text
              @click="saveEditName"
              :disabled="!editName || !editLink"
            >
              Сохранить
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog
        v-model="deleteDialog"
        persistent
        max-width="600px"
      >
        <v-card>
          <v-card-title>
            <span class="headline">Удаление категории</span>
          </v-card-title>
          <v-card-text>
            Вы точно хотите удалить категорию {{ item.name }}

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary darken-1"
              text
              @click="deleteDialog = false"
            >
              Закрыть
            </v-btn>
            <v-btn
              color="primary darken-1"
              text
              @click="deleteCategory"
            >
              Удалить
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog
        v-model="orderDialog"
        persistent
        max-width="600px"
      >
        <v-card>
          <v-card-title>
            <span class="headline">Порядок дочерних категорий</span>
          </v-card-title>
          <v-card-text>

            <div class="childes-list">
              <div class="divider" data-dividerid="-1" @dragover.prevent @drop="drop($event)">
                <div class="divider-inner" data-dividerid="-1">
                  <i class="fa fa-caret-right" data-dividerid="-1" aria-hidden="true"></i>
                  <div class="divider-line" data-dividerid="-1"></div>
                </div>
              </div>
              <div class="childes-list__item" v-for="(item,i) in categoryList" :key="i">
                <div class="item" draggable="true" :data-elindex="i" @dragstart="dragStart(i)">{{item.name}}</div>
                <div class="divider" :data-dividerid="i" @dragover.prevent @drop="drop(i)">
                  <div class="divider-inner" :data-dividerid="i">
                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                    <div class="divider-line"></div>
                  </div>
                </div>
              </div>
            </div>

          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary darken-1"
              text
              @click="orderDialog = false"
            >
              Закрыть
            </v-btn>
            <v-btn
              color="primary darken-1"
              text
              @click="saveOrder"
            >
              Сохранить порядок
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

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
              @input="setCategoryLink"
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
              :disabled="!newCategoryName"
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
  import Config from '@/js/config.js'
  import {mapMutations} from 'vuex'
  export default {
    props: {
      item: {
        type: Object,
        default() {
          return {}
        }
      }
    },
    data() {
      return {
        editDialog: false,
        deleteDialog: false,
        orderDialog: false,
        addCategoryDialog: false,
        editName: '',
        editLink: '',
        categoryList: [],
        selectedDragId: null,
        newCategoryName: '',
        newCategoryLink: ''
      }
    },
    methods: {
      ...mapMutations([
        "setSnackbar"
      ]),
      showEditDialog() {
        this.editDialog = true
      },
      showAddCategoryDialog () {
        this.addCategoryDialog = true
      },
      showDeleteDialog() {
        this.deleteDialog = true
      },
      async showOrderDialog() {
        await axios.get(`${Config.api}/drag?parentId=${this.item.id}`)
          .then(({data}) => {
            this.categoryList = data
          })
          .catch(e => {
            console.log("Error get childes", e)
          })
        this.orderDialog = true
      },
      saveEditName() {
        axios.put(`${Config.api}/categories/${this.item.id}`, {
          name: this.editName,
          link: this.editLink
        })
          .then(() => {
            this.editDialog = false
            this.$root.$emit('updateCatalog', this.item.parent_id)
          })
          .catch(e => {
            console.log("Error get categories", e)
          })
      },
      deleteCategory() {
        axios.delete(`${Config.api}/categories/${this.item.id}`)
          .then(() => {
            this.deleteDialog = false
            this.$root.$emit('updateCatalog', this.item.parent_id)
            this.$root.$emit('selectCategory', this.item.parent_id)
          })
          .catch(e => {
            console.log("Error delete categories", e)
          })
      },
      saveOrder() {
        this.categoryList.forEach((el, i) => {
          el.position = i
        })

        axios.put(`${Config.api}/drag/order`, {
          list: this.categoryList
        })
          .then(() => {
            this.$root.$emit('updateCatalog', this.item.id)
          })
          .catch(e => {
            console.log("Error update order categories", e)
          })
        this.orderDialog = false
      },
      drop(i) {
        let targetId = i
        if (targetId === undefined) {
          return
        }
        let el = this.categoryList.splice(this.selectedDragId, 1)

        if (targetId == -1) {
          this.categoryList.unshift(el[0])
          return
        }
        if (this.selectedDragId > targetId) {
          this.categoryList.splice(targetId + 1, 0, el[0])
        } else {
          this.categoryList.splice(targetId, 0, el[0])
        }
      },
      dragStart(i) {
        this.selectedDragId = i
      },
      goName(name){
        this.$root.$emit('goPage', {name: name, category: this.item})
      },
      saveNewCategoryName(){
        axios.post(`${Config.api}/categories`, {
          name: this.newCategoryName,
          link: this.newCategoryLink,
          parentId: this.item.id
        })
          .then(() => {
            this.addCategoryDialog = false
            this.newCategoryName = ''
            this.$root.$emit('updateCatalog', this.item.id)
          })
          .catch(e => {
            console.log("Error create new category", e)
          })
      },
      setCategoryLink(){
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
    },
    watch: {
      categoryList: {
        deep: true,
        handler() {

        }
      }
    },
    created() {
      this.editName = this.item.name
      this.editLink = this.item.link
    }
  }

</script>


<style lang="scss" scoped>
  @import '@/sass/helpers.scss';

  .childes-list {

    &__item {
      font-size: 18px;
    }
  }

  .divider {
    height: 18px;
    align-items: center;
    display: flex;
  }

  .divider-inner {
    display: flex;
    width: 100%;
    align-items: center;
    font-size: 20px;
  }

  .divider-line {
    height: 2px;
    background: #cecece;
    flex-grow: 1;
  }
</style>
