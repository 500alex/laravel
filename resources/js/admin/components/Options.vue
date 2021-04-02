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

          <v-list-item link>
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
  import Config from '../../config.js'

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
        newCategoryName: ''
      }
    },
    methods: {
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
      }
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
