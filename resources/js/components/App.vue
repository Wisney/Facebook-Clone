<template>
    <div class="flex flex-col flex-1 h-screen overflow-y-hidden" v-if="authUser">
        <Nav></Nav>

        <div class="flex overflow-y-hidden flex-1">
            <Sidebar></Sidebar>

            <div class="overflow-x-hidden w-2/3">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>

    </div>
</template>

<script>
import Nav from './Nav';
import Sidebar from './Sidebar';
import { mapGetters } from 'vuex';

export default {
    name: "App",

    components: {
        Nav,
        Sidebar
    },

    watch: {
        $route(to, from) {
            this.$store.dispatch('setPageTitle', to.meta.title);
        }
    },

    computed: {
        ...mapGetters({
            authUser: 'authUser'
        })
    },

    created() {
        this.$store.dispatch('setPageTitle', this.$route.meta.title);
    },

    mounted() {
        this.$store.dispatch('fetchAuthUser');
    },
}
</script>

<style scoped></style>
