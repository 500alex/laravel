<template>
    <div class="left-menu">
        <div class="left-menu__item" v-for="(item,k) in leftMenu" :key="k" @click="goName(item.data)">
            <span class="left-menu-icon">
                <i class="fa" :class="item.icon" aria-hidden="true"></i>
            </span>
            <span class="left-menu-text">{{ item.label }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'leftMenu',
    data(){
        return {
            leftMenu: [
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
    .left-menu {
        color: #fff;
        padding-top: 10px;
        &__item {
            cursor: pointer;
            height: 54px;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 17px;
            &:hover {
background: #27a9e3;
            }

        }
    }
    .left-menu-icon {
        margin-right: 10px;
        font-size: 28px;
        width: 26px;

    }
</style>
