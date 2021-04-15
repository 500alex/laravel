<template>
  <div class="menu-list">
    <v-list flat>
      <v-list-item-group
        v-model="selectedItem"
        color="primary"
      >
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
        >
          <v-list-item-icon>
            <i class="fa" :class="item.icon" aria-hidden="true"></i>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title v-text="item.text"></v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
    </v-list>
  </div>
</template>
<script>
  export default {
    data(){
      return {
        selectedItem: 0,
        items: [
          { text: 'Список наборов', icon: 'fa-plus',component:'sets-list' },
          { text: 'Управление набором', icon: 'fa-battery-three-quarters', component: 'fill-set' },
          { text: 'Conversions', icon: 'mdi-flag' },
        ],
      }
    },
    watch: {
      selectedItem(val){
        this.$emit('changeComponent', this.items[val].component)
      }
    }
  }
</script>
<style lang="scss" scoped>
  .menu-list {
    width: 100%;
    height: 100%;
  }
  .menu-item {
    width: 100%;
    height: 38px;
    display: flex;
    align-items: center;
    padding: 0 24px;
    color: #fff;
    font-size: 18px;
    font-family: Roboto-bold;
    cursor: pointer;
    &:hover {
      background: #cecece;
      color: #000;
    }
  }
</style>
