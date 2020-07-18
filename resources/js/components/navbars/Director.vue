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
            { title: 'My Profile',link:{name:'DirectorDashboard_profile'} },
          { title: 'Weekly Shifts' ,link:{name:'DirectorDashboard_reports_weeklyTaxiShifts_index'} },
          { title: 'Comments',link:{name:'DirectorDashboard_comments'} },

        ],
      }
    },
    methods:{
      showDrawer(){
        this.$store.state.showDrawerDirector = !this.$store.state.showDrawerDirector;
      }
    }
}
</script>
<style scoped>
  a{
    text-decoration: none;
  }
</style>