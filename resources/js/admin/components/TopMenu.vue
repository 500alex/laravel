<template>
  <div class="top-menu-list">
    <div class="list-item" v-for="(item,k) in topMenu" :key="k" @click="goName(item.data)">
            <!--<span class="left-menu-icon">-->
                <!--<i class="fa" :class="item.icon" aria-hidden="true"></i>-->
            <!--</span>-->
      {{ item.label }}
    </div>
  </div>
</template>

<script>
  export default {
    name: 'TopMenu',
    data(){
      return {
        topMenu: [
          {
            label: 'Категории',
            icon: 'fa-home',
            data:'category-page'
          },
          {
            label: 'Настройки',
            icon: 'fa-bandcamp',
            data: 'settings-page'
          }
        ]
      }
    },
    methods: {
      goName(name){
        this.$root.$emit('goPage', {name:name,category:{}})
      }
    }
  }

</script>

<style lang="scss" scoped>
.top-menu-list {
  display: flex;
  align-items: center;
}
  .list-item {
    cursor: pointer;
    margin: 0 12px;
    &:hover {
      text-decoration: underline;
    }
  }
</style>
