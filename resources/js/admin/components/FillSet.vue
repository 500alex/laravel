<template>
  <div class="direction">
    <div class="direction__header d-flex align-center justify-content-between">
      <h2>Управление набором: {{ selectedSet.name }}</h2>
      <span @click="goTolist" class="text-decoration-underline pointer">К списку</span>
    </div>
    <div class="direction__body">

        <v-row>
          <v-col cols="12" class="d-flex flex-wrap">
            <div class="set-value-cheep" style="margin: 0 12px;" v-for="(value,i) in fullItems" :key="i">
              <span class="text">{{value.name}}</span>
            <span class="close" @click="deleteValue(value)"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>
          </v-col>
        </v-row>
        <v-row>
          <h4>добавьте значения через запятую</h4>
          <v-col cols="12">
            <v-textarea
              outlined
              name="setValues"
              placeholder="Список значений"
              v-model="setValuesString"
              @input="changeArea"
            ></v-textarea>
          </v-col>
        </v-row>

      <div style="text-align: right;">
        <v-btn
          color="blue darken-1"
          text
          @click="saveSetValues"
        >
          Сохранить
        </v-btn>
      </div>

    </div>
  </div>
</template>
<script>
  import Config from '@/js/config.js'
  import {mapMutations} from 'vuex'
  export default {
    props: {
      selectedSet: {
        type: Object,
        default: ()=>{
          return {}
        }
      }
    },
    data (){
      return {
        getItems: [],
        setValuesString: '',
      }
    },
    computed:{
      valuesArray (){
        let arr = this.setValuesString.split(',')
        return arr.filter(el => {
          el.trim()
          return el != ''
        })
      },
      userItems(){
        let temp = []
        this.valuesArray.forEach((el,i) => {
          temp.push({
            name: el,
            id: `local-${i}`
          })
        })
        return temp
      },
      fullItems () {
        return [...this.getItems,...this.userItems]
      }
    },
    methods: {
      ...mapMutations([
        "setSnackbar"
      ]),
      changeArea(){
        this.setValuesString = this.setValuesString.trim()
        this.valuesArray.forEach((item)=>{
          this.items.push(item)
        })
      },
      goTolist(){
        this.$emit('changeComponent', {
          component: 'sets-list',
          data: null
        })
      },
      saveSetValues(){
        axios.post(`${Config.api}/set-items`, {
          values: this.valuesArray,
          setId: this.selectedSet.id
        })
          .then(() => {
            this.setSnackbar({text: 'Набор успешно обновлен', color:'success', show: true})
            this.goTolist()
          })
          .catch(e => {
            console.log("Error post set items", e)
          })
      },
      getSetItems(){
        axios.get(`${Config.api}/set-items?setId=${this.selectedSet.id}`)
          .then(({data}) => {
            this.getItems = data
          })
          .catch(e => {
            console.log("Error get set items", e)
          })
      }
    },
    created() {
      this.getSetItems()
    }

  }
</script>
<style lang="scss" scoped>
  .set-value-cheep {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px;
    border-radius: 18px;
    border: 1px solid #cecece;
    .text {
      margin-right: 12px;
    }
  }
</style>
