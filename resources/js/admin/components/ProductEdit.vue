<template>
  <div>
    <div class="page-header">
    <div class="page-header__label">Редактирование продукта</div>
    <div class="pointer" @click="$emit('showPage', 'list')"><i class="fa fa-times" aria-hidden="true"></i></div>
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
        <button class="btn btn-primary" @click="SaveEditProduct" :disabled="!valid()">Сохранить</button>
      </div>
    </div>
  </div>
</template>

<script>
  import Config from '@/js/config.js'
  import {mapMutations} from 'vuex'
  export default {
    props: {
      productId: {
        type: Number,
        default: null
      }
    },
    data () {
      return {
        imageSrc: [],
        files: [],
        product:{
          name: ''
        }
      }
    },
    methods: {
      ...mapMutations([
        "setSnackbar"
      ]),
      getProduct(){
        axios.get(`${Config.api}/products/${this.productId}`)
          .then(({data}) => {
            this.product =   data[0];
            this.convertToBase(this.product.images)
          })
          .catch(e => {
            console.log("Error delete file", e)
          })
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
            this.imageSrc.push(
              {
                image: e.target.result,
                id: `local-${i}`
              }
            )
          }
          reader.readAsDataURL(this.files[i])

        }
      },
      SaveEditProduct(){
        axios.put(`${Config.api}/products/${this.product.id}`, this.product)
          .then(()=>{
            if(this.files.length){
              for (let i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                this.sendFile(file,this.product.id)
              }
            }
            this.setSnackbar({text: 'Продукт успешно обновлен', color:'success', show: true})
            this.$root.$emit('updateCatalog', this.product.category_id)
            this.$emit('updateProducts',this.product.category_id)
            this.$emit('showPage', 'list')
            this.product = {}
          })
          .catch(e => {
            console.log('error update product',e)
          })
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
      convert(url,callback){
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
          var reader = new FileReader();
          reader.onloadend = function() {
            callback(reader.result);
          }
          reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
        this.files.push(xhr.response)
      },
      deleteFile(id) {
        let pattern = new RegExp("^(local-)", "i")
        let local = pattern.test(id)
        if(local){
          let index = parseInt(id.substring(6,id.length))

          this.imageSrc.forEach((img,index) => {
            if(img.id === id){
              this.imageSrc.splice(index,1)
            }
          })
          this.files.splice(index,1)
        }else {
          axios.delete(`${Config.api}/files/${id}`)
            .then(() => {
              this.imageSrc = []
              this.getProduct()
            })
            .catch(e => {
              console.log("Error delete file", e)
            })
        }
      },
      convertToBase(arr){
        arr.forEach(image => {
          this.convert(`public/storage/${image.link}`, (dataUrl) =>{
            this.imageSrc.push({
              image: dataUrl,
              id: image.id
            });
          })
        })
      }

    },
    created() {
      this.getProduct()
    }
  }
</script>

<style lang="scss">
  .images-list {
    margin: 32px 0;
  }
  .image-block {
    position: relative;
    width: 140px;
    float: left;
    margin: 12px;
    border: 1px solid #cecece;
    height: 140px;
    overflow: hidden;
    padding: 6px;
    display: flex;
    align-items: center;
    img {
      width: 100%;
    }
    &__icon {
      position: absolute;
      right: 6px;
      top: 6px;
      cursor: pointer;
      width: 23px;
      height: 23px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff;
      border-radius: 50%;
      border: 1px solid #cecece;
    }
  }
</style>
