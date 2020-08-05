<template>
    <div class="ma-0 pa-0">

        <v-app-bar app color="grey lighten-5"
                   class=""
                   clipped-left
                   hideOnScroll
                   scroll-threshold="100"
                   v-if="$mq=='desktop'"
        >
            <!--this on in v-app :  v-model="drawer" -->
            <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->

            <v-toolbar-title>
                <!-- drawer start -->
                <v-app-bar-nav-icon @click="showDrawer()"></v-app-bar-nav-icon>
                <!-- drawer end -->
                <router-link to="/">
                    <span
                        class="font-weight-light  grey--text text--darken-4 title text-uppercase ml-12 ">Chilliwalk</span>
                    <span class="  light-blue--text text--darken-4 title">Taxi</span>
                </router-link>
            </v-toolbar-title>

            <div class="flex-grow-1"></div>


            <v-toolbar-items>
                <v-btn
                    v-for="item in items"
                    :key="item.title"
                    link
                    text
                    :to='item.link'
                    v-text="item.title"
                    class="grey--text text--darken-4"
                >
                </v-btn>
            </v-toolbar-items>
        </v-app-bar>

        <!----------------------Mobile version---------------------->
        <v-app-bar
            color="primary"
            class="white--text mb-2"
            hide-on-scroll
            v-if="$mq=='mobile'"

        >

            <v-toolbar-title>
                <router-link to="/" class="text-white">
                    My Taxi Office
                </router-link>
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <v-menu
                left
                bottom

            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                        color="white"
                    >
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item
                        v-for="item in mobileItems"
                        :key="item.title"
                        :to='item.link'
                        class=" ma-0 pa-0"
                    >

                        <v-btn :ripple="false" text link tile v-text="item.title" block
                               class="ma-0 my-5 grey--text text--darken-4"></v-btn>
                    </v-list-item>
                    <v-list-item class=" ma-0 pa-0">
                        <v-btn :ripple="false" text link tile block
                               class="ma-0 my-5 grey--text text--darken-4"
                               @click="logout"
                        >
                            Logout
                        </v-btn>
                    </v-list-item>

                </v-list>
            </v-menu>
        </v-app-bar>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                items: [
                    {title: 'My profile', link: {name: 'DispatcherDashboard_profile'}},
                    {title: 'Driver List', link: {name: 'DispatcherDashboard_drivers'}},
                    {title: 'Special Shifts', link: {name: 'DispatcherDashboard_specialShifts'}},
                    {title: 'My Special Shift', link: {name: 'DispatcherDashboard_mySpecialShifts'}},
                    {title: 'Account Types', link: {name: 'DispatcherDashboard_accountTypes'}},
                    {title: 'authorization Files', link: {name: 'DispatcherDashboard_authorizationFiles'}},
                    {title: 'Rotations Manager', link: {name: 'DispatcherDashboard_rotationsManager'}},
                    {title: 'PreInspections', link: {name: 'DispatcherDashboard_preInspections_index'}},
                ],
                mobileItems: [
                    {title: 'profile', link: {name: 'DispatcherDashboard_profile'}},
                    {title: 'Driver List', link: {name: 'DispatcherDashboard_drivers'}},
                    {title: 'Special Shifts', link: {name: 'DispatcherDashboard_specialShifts'}},
                    {title: 'My Special Shift', link: {name: 'DispatcherDashboard_mySpecialShifts'}},
                    {title: 'Account Types', link: {name: 'DispatcherDashboard_accountTypes'}},
                    {title: 'authorization Files', link: {name: 'DispatcherDashboard_authorizationFiles'}},
                    {title: 'Rotations Manager', link: {name: 'DispatcherDashboard_rotationsManager'}},
                    {title: 'PreInspections', link: {name: 'DispatcherDashboard_preInspections_index'}},
                    {title: 'change my password', link:{ name: 'DispatcherDashboard_changePassword'}},
                ],
            }
        },
        methods: {
            showDrawer() {
                this.$store.state.showDrawerDispatcher = !this.$store.state.showDrawerDispatcher;
            },
            logout() {
                this.$store
                    .dispatch("destroyToken_dispatcher")
                    .then(response => {
                        this.$router.push({ name: "LoginPage" });
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
        }
    }
</script>
<style scoped>
    a {
        text-decoration: none;
    }
</style>
