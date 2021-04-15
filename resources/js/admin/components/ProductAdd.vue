<template>
  <div>
    <div class="page-header">
      <div class="page-header__label">Добавление продукта в категорию {{ category.name }}</div>
      <div @click="$emit('showPage', 'list')" class="pointer"><i class="fa fa-times" aria-hidden="true"></i></div>
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
          <div class="image-block" v-for="(image,i) in imageSrc" :key="i">
            <div class="image-block__icon" @click="deleteFile(image.id)">
              <i class="fa fa-times" aria-hidden="true"></i>
            </div>
            <img :src="image.image" v-if="imageSrc.length" />
          </div>
          <div style="clear:both;"></div>
        </div>
      </div>
      <div class="buttons-group text-right width-50">
        <button class="btn btn-primary" @click="SaveNewProduct" :disabled="!valid()">Сохранить</button>
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
        imageSrc: [],
        files: [],
        product: {
          name: '',
          categoryId: null
        },
      }
    },
    methods: {
      ...mapMutations([
        "setSnackbar"
      ]),
      deleteFile(id) {
        console.log('id',id)
          this.imageSrc.forEach(img => {
            if(img.id === id){
              this.imageSrc.splice(id,1)
            }
          })

        this.files.splice(id,1)
      },
      valid(){
        if(!this.product.name) return false
        return true
      },
      onFileChange(event){
        this.files = Array.from(event.target.files)
        for (let i = 0; i < this.files.length; i++) {
          var reader = new FileReader();
          reader.onload = (e) =>{
            this.imageSrc.push({
              image: e.target.result,
              id: i
            })
          }
          reader.readAsDataURL(this.files[i])

        }
      },
      async SaveNewProduct(){
        this.product.categoryId = this.category.id
        let res = await axios.post(`${Config.api}/products`, this.product)
        if(res.data){
          if(this.files.length){
            for (let i = 0; i < this.files.length; i++) {
              let file = this.files[i];
              this.sendFile(file,res.data)
            }
          }
          this.setSnackbar({text: 'Продукт успешно сохранен', color:'success', show: true})
          this.product.name = ''
          this.product.categoryId = null
          this.$root.$emit('updateCatalog', this.category.id)
          this.$emit('updateProducts',this.category.id)
          this.$emit('showPage', 'list')
        }
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
    }
  }
</script>
