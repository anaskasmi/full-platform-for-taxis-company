<template>
<v-app>
    <!-- <v-navigation-drawer app>
    </v-navigation-drawer> -->
    <!-- <Drawer app></Drawer> -->

    <NavbarIndex v-if="showNavbarIndex && $mq=='desktop'"/>
    <NavbarAdmin v-if="showNavbarAdmin"/>
    <NavbarDirector v-if="showNavbarDirector"/>
    <NavbarDriver v-if="showNavbarDriver"/>
    <NavbarDispatcher v-if="showNavbarDispatcher"/>

      <!-- Sizes your content based upon application components -->
    <v-content>

    <!-- Provides the application the proper gutter -->
        <div class="ma-12" v-if="$mq == 'desktop'">
            <router-view></router-view>
        </div>
        <div v-if="$mq == 'mobile'">
            <router-view></router-view>
        </div>
    </v-content>


                <v-footer
                app
                absolute
                class="font-weight-medium grey--text text--darken-4"
                light
                color="grey lighten-4"
                >
                <v-col
                    class="text-center"
                    cols="12"
                >
                    {{ new Date().getFullYear() }} — <strong>chilliwack taxi</strong>
                </v-col>
                </v-footer>
</v-app>
</template>

<script>
import NavbarIndex from '@/js/components/navbars/Index.vue';
import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import NavbarDriver from '@/js/components/navbars/Driver.vue';
import NavbarDirector from '@/js/components/navbars/Director.vue';
import NavbarDispatcher from '@/js/components/navbars/Dispatcher.vue';
import Drawer from '@/js/components/drawers/Driver.vue';
export default {
    name : 'App',
    components :{
        NavbarIndex,
        NavbarAdmin,
        NavbarDriver,
        NavbarDirector,
        NavbarDispatcher,
        Drawer,
    },
    computed:{
        showNavbarIndex:function(){
            return this.$store.state.showNavbarIndex;
            },
        showNavbarAdmin:function(){
            return this.$store.state.showNavbarAdmin;
            },
        showNavbarDriver:function(){
            return this.$store.state.showNavbarDriver;
            },
        showNavbarDirector:function(){
            return this.$store.state.showNavbarDirector;
            },
        showNavbarDispatcher:function(){
            return this.$store.state.showNavbarDispatcher;
            }
    }
}
</script>
<style scoped>

</style>
