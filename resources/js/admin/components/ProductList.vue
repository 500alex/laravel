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
      <div class="page-header">
        <div class="page-header__label">Добавление продукта в категорию {{ category.name }}</div>
        <div @click="showPage('list')" class="pointer"><i class="fa fa-times" aria-hidden="true"></i></div>
      </div>
      <div class="page-body">
        <div class="inputs-group width-50">
          <h5>Название продукта</h5>
          <div class="input-group mb-7">
            <input  type="text"
                    class="form-control"
                    placeholder="Название продукта"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                    v-model="product.name"
            >
          </div>
          <div class="form-group">
            <label for="image">Фото продукта</label>
            <input type="file" multiple class="form-control-file" name="image" id="image" @change="onFileChange" accept="image/*">
          </div>
          <div class="images-list">
            <div class="images-list__item" v-for="(image,i) in imageSrc" :key="i">
              <img :src="image" v-if="imageSrc.length" />
            </div>

          </div>
        </div>
        <div class="buttons-group text-right width-50">
          <button class="btn btn-primary" @click="SaveNewProduct" :disabled="!valid()">Сохранить</button>
        </div>
      </div>
    </div>
    <div class="product-edit-page" v-if="tabs.edit">
      <div class="page-header">
        <div class="page-header__label">Редактирование продукта</div>
        <div @click="showPage('list')" class="pointer"><i class="fa fa-times" aria-hidden="true"></i></div>
      </div>
    </div>
  </div>
</template>

<script>
  import Config from '@/js/config.js'
  import {mapMutations} from 'vuex'
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
        selectedProduct: {},
        deleteProductDialog: false,
        products: [],
        product: {
          name: '',
          categoryId: null
        },
        imageSrc: [],
        files: [],
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
      onFileChange(event){
        this.files = event.target.files

        // const reader = new FileReader();
        // reader.onload = e => {
        //   console.log('load images')
        //   // this.imageSrc = reader.result
        // }

        for (let i = 0; i < this.files.length; i++) {
          var reader = new FileReader();
          reader.onload = (e) =>{
            this.imageSrc.push(e.target.result)
          }
          reader.readAsDataURL(this.files[i])

        }

        // const reader = new FileReader();
        // reader.onload = e => {
        //    this.imageSrc.push(reader.result)
        // }
        // let file = this.files[i];
        // this.sendFile(file,i)

         // this.sendFile()

        // reader.readAsDataURL(this.file)
        // this.product.image = this.file
      },
      sendFile(file, ownerId){
        let formData = new FormData();

        formData.set('file',file)
        formData.set('ownerId', ownerId)
        axios({
          method: "post",
          url: `${Config.api}/upload`,
          data: formData,
          headers: { "Content-Type": "multipart/form-data" }
        })
          .then((res) => {
            console.log('uploaded file id', res);
            this.files = []
            this.imageSrc = ''
          })
          .catch(error => {
            console.log(error);
          });
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
      getProducts (id){
        axios.get(`${Config.api}/products?categoryId=${id}`)
          .then(({data}) => {
            this.products = data
          })
          .catch(e => {
            console.log("Error get products", e)
          })
      },
      SaveNewProduct(){
        this.product.categoryId = this.category.id
        axios.post(`${Config.api}/products`, this.product)
          .then(({data}) => {
            if(this.files.length){
              for (let i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                this.sendFile(file,data)
              }
            }
            this.setSnackbar({text: 'Продукт успешно сохранен', color:'success', show: true})
            this.getProducts(this.category.id)
            this.$root.$emit('updateCatalog', this.category.id)
            this.product.name = ''
            this.showPage('list')
          })
          .catch(e => {
            console.log("Error get products", e)
          })


      },
      valid(){
        if(!this.product.name) return false
        return true
      }
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
