<template>
        <v-app-bar  app color="grey lighten-5"
            class=""
            clipped-left
            hideOnScroll
            scroll-threshold="100"

        >
        <!--this on in v-app :  v-model="drawer" -->
            <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->

            <v-toolbar-title  >
              <!-- drawer start -->
              <v-app-bar-nav-icon @click="showDrawer()"></v-app-bar-nav-icon>
              <!-- drawer end -->
              <router-link to="/">
                <span class="font-weight-light  grey--text text--darken-4 title text-uppercase ml-12 ">Chilliwalk</span>
                <span class="  light-blue--text text--darken-4 title">Taxi</span>
              </router-link>
            </v-toolbar-title>

            <div class="flex-grow-1"></div>


            <v-toolbar-items >
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
</template>
<script>
export default {
    data () {
      return {
        items: [
          { title: 'My profil' ,link:{name:'DispatcherDashboard_profile'} },
          { title: 'Driver List',link:{name:'DispatcherDashboard_drivers'} },
          { title: 'Special Shifts',link:{name:'DispatcherDashboard_specialShifts'} },
          { title: 'My Special Shift' ,link:{name:'DispatcherDashboard_mySpecialShifts'}},
          { title: 'Account Types' ,link:{name:'DispatcherDashboard_accountTypes'}},
          { title: 'authorization Files' ,link:{name:'DispatcherDashboard_authorizationFiles'}},
          { title: 'Rotations Manager' ,link:{name:'DispatcherDashboard_rotationsManager'}},
          ],
      }
    },
    methods:{
      showDrawer(){
        this.$store.state.showDrawerDispatcher = !this.$store.state.showDrawerDispatcher;
      }
    }
}
</script>
<style scoped>
  a{
    text-decoration: none;
  }
</style>