<template>
  <div class="top-menu-list">
    <div class="list-item" v-for="(item,k) in topMenu" :key="k" @click="goName(item.link)">
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
            label: 'Продукты',
            link:'/admin'
          },
          {
            label: 'Настройки',
            link: '/admin/settings'
          }
        ]
      }
    },
    methods: {
      goName(link){
        this.$router.push(link)
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
