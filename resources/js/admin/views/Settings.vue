<template>
  <div class="settings">
    <div class="settings__left">
      <left-settings-menu @changeComponent="setComponent"/>
    </div>
    <div class="settings__right">
    <component :is="currentComponent" @changeComponent="setComponent" :selectedSet="selectedSet" />
    </div>
  </div>
</template>
<script>
import LeftSettingsMenu from '@/js/admin/components/LeftSettingsMenu.vue'
import SetsList from "@/js/admin/components/SetsList.vue"
import FillSet from "@/js/admin/components/FillSet.vue"
  export default {
    components: {
      'left-settings-menu': LeftSettingsMenu,
      'sets-list': SetsList,
      'fill-set':FillSet
    },
  data(){
    return {
      currentComponent: 'sets-list',
      selectedSet: null
    }
  },
    methods: {
      setComponent(val){
        this.currentComponent = val.component
        if(val.data){
          this.selectedSet = val.data
        }
      }
    }

  }
</script>

<style lang="scss" scoped>
  .settings {
    display: flex;
    height: calc(100vh - 64px);
    &__left {
      width: 369px;
      border-right: 1px solid #cecece;
    }
    &__right {
      padding: 24px;
      flex-grow: 1;
    }
  }
</style>
