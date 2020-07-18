<template>
        <v-app-bar  app color="grey lighten-5"
            class=""
            clipped-left
            hideOnScroll
        >
        <!--this on in v-app :  v-model="drawer" -->
            <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->

            <v-toolbar-title  >
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
          { title: 'Admin' ,link:{name:'AdminDashboard_profile'} },
          { title: 'Driver',link:{name:'DriverDashboard_profile'}},
          { title: 'Director',link:{name:'DirectorDashboard_profile'} },
          { title: 'Dispatcher' ,link:{name:'DispatcherDashboard_profile'}},
        ],
      }
    },
}
</script>
<style scoped>
  a{
    text-decoration: none;
  }
</style>
