<template>
  <v-app-bar app color="grey lighten-5" class clipped-left hideOnScroll scroll-threshold="100">
    <!--this on in v-app :  v-model="drawer" -->
    <!-- <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->

    <v-toolbar-title>
      <!-- drawer start -->
      <v-app-bar-nav-icon @click="showDrawer()"></v-app-bar-nav-icon>
      <!-- drawer end -->

      <router-link to="/">
        <span
          class="font-weight-light grey--text text--darken-4 title text-uppercase ml-12"
        >Chilliwalk</span>
        <span class="light-blue--text text--darken-4 title">Taxi</span>
      </router-link>
    </v-toolbar-title>

    <div class="flex-grow-1"></div>

    <v-toolbar-items>
      <v-menu open-on-hover offset-y center>
        <template v-slot:activator="{ on }">
          <v-btn v-on="on" link text class="grey--text text--darken-4">
            Drivers
            <v-icon small>keyboard_arrow_down</v-icon>
          </v-btn>
        </template>
        <v-list tile>
          <v-list-item to="/adminDashboard/drivers">
            <v-list-item-title>
              <v-icon>arrow_right</v-icon>DLD
            </v-list-item-title>
          </v-list-item>
          <v-list-item to="/adminDashboard/MLDs">
            <v-list-item-title>
              <v-icon>arrow_right</v-icon>MLD
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-btn link text to="/adminDashboard/directors" class="grey--text text--darken-4">Directors</v-btn>

      <v-btn
        link
        text
        to="/adminDashboard/dispatchers"
        class="grey--text text--darken-4"
      >Dispatchers</v-btn>

      <v-btn link text to="/adminDashboard/trips" class="grey--text text--darken-4">Trips</v-btn>

      <v-btn link text to="/adminDashboard/reports" class="grey--text text--darken-4">Reports</v-btn>

      <v-btn link text to="/adminDashboard/archives" class="grey--text text--darken-4">Archives</v-btn>

      <v-btn
        link
        text
        to="/adminDashboard/specialShifts"
        class="grey--text text--darken-4"
      >Special Shifts</v-btn>

      <v-btn
        link
        text
        :to="{name:'AdminDashboard_batches'}"
        class="grey--text text--darken-4"
      >Batches</v-btn>
    </v-toolbar-items>
  </v-app-bar>
</template>
<script>
export default {
  data() {
    return {
      items: [
        { title: "Drivers", link: "/adminDashboard/drivers" },
        { title: "Drirectors", link: "/adminDashboard/directors" },
        { title: "Dispatchers", link: "/adminDashboard/dispatchers" },
        { title: "Trips", link: "/adminDashboard/trips" },
        { title: "Reports", link: "/adminDashboard/reports" },
        { title: "Archives", link: "/adminDashboard/archives" },
        { title: "Special Shifts", link: "/adminDashboard/specialShifts" }
      ]
    };
  },
  methods: {
    showDrawer() {
      this.$store.state.showDrawerAdmin = !this.$store.state.showDrawerAdmin;
    }
  }
};
</script>
<style scoped>
a {
  text-decoration: none;
}
</style>