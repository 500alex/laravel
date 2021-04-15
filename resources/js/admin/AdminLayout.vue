<template>
  <div class="admin-grid">
    <div class="admin-grid__header">
      <div class="header">
        <div class="header__logo">
          <div class="admin-logo">
            <img src="/public/img/logo-icon.png"/>
            <img src="/public/img/logo-text.png"/>
          </div>
        </div>
        <div class="header__menu"><top-menu/></div>
        <div class="header__options">
          <span class="goToSite">
            <a href="http://laravel/">
               <i class="fa fa-university" aria-hidden="true"></i>
            </a>
            </span>
        </div>
      </div>
    </div>
    <div class="admin-grid__page">
      <router-view></router-view>
    </div>
    <div class="admin-grid__options">
      <v-snackbar
        v-model="snackbar.show"
        :right="true"
        :color="snackbar.color"
      >
        {{snackbar.text}}
      </v-snackbar>
    </div>
  </div>
</template>

<script>
  import TopMenu from "./components/TopMenu.vue";
  import {mapState, mapMutations} from 'vuex';
  export default {
    data() {
      return {

      }
    },
    computed: {
      ...mapState([
        'snackbar'
      ])
    },
    components: {
      'top-menu': TopMenu,
    },
  }
</script>

<style lang="scss" scoped>
@import '@/sass/variables.scss';

.admin-grid {
  display: flex;
  flex-direction: column;
  height: 100%;
  &__page {
    flex-grow: 1;
  }
}

.header {
  display: flex;
  align-items: center;
  background: #23272d;
  color: #fff;
  height: 64px;
  padding: 0 22px;
  &__menu {
    margin-left: 42px;
  }
  &__options {
    margin-left: auto;
    a {
      color: #fff;
    }
  }
}
</style>
