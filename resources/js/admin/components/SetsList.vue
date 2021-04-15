<template>
  <div class="add-set">
    <div class="add-set__header">
      <h2>Спис
        ок наборов</h2>
      <v-btn
        depressed
        color="primary"
        @click="addSetDialog = true"
      >
        Добавить
      </v-btn>
    </div>
    <div class="add-set__body">
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
      <v-data-table
        :headers="headers"
        :items="sets"
        :search="search"
      >
        <template
          v-slot:item.options="{ item }"
        >
          <div class="options-list">
            <span @click="fillSet(item)" class="pointer"><i class="fa fa-pencil" aria-hidden="true"></i></span>
          </div>
        </template>
      </v-data-table>

<div class="page-dialogs">
  <v-dialog
    v-model="addSetDialog"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">Добавить набор</span>
      </v-card-title>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-text-field
                label="Название набора"
                required
                v-model="set.name"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="blue darken-1"
          text
          @click="addSetDialog = false"
        >
          Отмена
        </v-btn>
        <v-btn
          color="blue darken-1"
          text
          @click="saveSet"
        >
          Сохранить
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</div>


    </div>
  </div>
</template>
<script>
  import Config from '@/js/config.js'
  export default {
    data () {
      return {

        addSetDialog: false,
        set:{
          name: ''
        },
        search: '',
        headers: [
          {
            text: 'ID',
            align: 'start',
            sortable: false,
            value: 'id',
          },
          { text: 'Название набора', value: 'name' },
          { text: 'Опции', value: 'options',align: 'end', }
        ],
        sets: [],
      }
    },
    methods: {
      saveSet () {
        axios.post(`${Config.api}/sets`, this.set)
          .then(({data}) => {
            this.set.name = ''
            this.getSets()
          })
          .catch(e => {
            console.log("Error get sets", e)
          })
          .finally(()=>{
            this.addSetDialog = false
          })

      },
      getSets () {
        axios.get(`${Config.api}/sets`)
          .then(({data}) => {
            this.sets =  data;
          })
          .catch(e => {
            console.log("Error get sets", e)
          })
      },
      fillSet (set){
        this.$emit('changeComponent', {
          component: 'fill-set',
          data: set
        })
      }
    },
    created() {
      this.getSets();
    }
  }
</script>
<style lang="scss" scoped>
.add-set {
  &__header {
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
  }
}
</style>
