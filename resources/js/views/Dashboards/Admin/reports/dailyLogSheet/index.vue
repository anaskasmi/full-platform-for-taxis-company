<template>
  <div class="wrapper_box">
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>

    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header">
        <v-btn color="grey" text tile class="float-left" :to="backRoute">
          <v-icon class="mr-2">keyboard_return</v-icon>Drivers List
        </v-btn>
      </div>
      <v-btn class="ma-2 float-right" tile outlined color="light-blue darken-4">
        <v-icon left>person</v-icon>
        {{name}}
      </v-btn>
    </div>
    <hr />
    <!-- <div class="sentence">
                    Pick a date !
    </div>-->

    <!-- tabs -->
    <v-tabs fixed-tabs background-color="light-blue darken-4" dark>
      <v-tab @click="day">Daily Report</v-tab>

      <v-tab @click="currentMonth">Current Month</v-tab>

      <v-tab @click="pastMonth">Past Month</v-tab>

      <v-tab @click="currentYear">Current year</v-tab>

      <v-tab @click="pastYear">Past year</v-tab>
    </v-tabs>
    <!-- end tabs -->
    <day v-if="day_show" :idEncrypted="idEncrypted" :id="id"></day>
    <currentMonth v-if="currentMonth_show" :idEncrypted="idEncrypted" :id="id"></currentMonth>
    <pastMonth v-if="pastMonth_show" :idEncrypted="idEncrypted" :id="id"></pastMonth>
    <currentYear v-if="currentYear_show" :idEncrypted="idEncrypted" :id="id"></currentYear>
    <pastYear v-if="pastYear_show" :idEncrypted="idEncrypted" :id="id"></pastYear>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import day from "@/js/views/Dashboards/Admin/reports/dailyLogSheet/day.vue";
import currentMonth from "@/js/views/Dashboards/Admin/reports/dailyLogSheet/currentMonth.vue";
import pastMonth from "@/js/views/Dashboards/Admin/reports/dailyLogSheet/pastMonth.vue";
import currentYear from "@/js/views/Dashboards/Admin/reports/dailyLogSheet/currentYear.vue";
import pastYear from "@/js/views/Dashboards/Admin/reports/dailyLogSheet/pastYear.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  beforeMount() {
    this.id = this.$route.params.id;
    this.day();
    this.getUser();
  },
  components: {
    NavbarAdmin,
    day,
    currentMonth,
    pastMonth,
    currentYear,
    pastYear,
    Drawer
  },
  data() {
    return {
      day_show: false,
      currentMonth_show: false,
      pastMonth_show: false,
      currentYear_show: false,
      id: "",
      name: ""
    };
  },
  computed: {
    backRoute() {
      return { name: "AdminDashboard_reports_dailyLogSheet_list" };
    },
    idEncrypted(){
      return this.id*2+2019;
    }
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },

    getUser() {
              axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;

      axios
        .get(this.BASE_URL() + "/api/admin/driver/" + this.id)
        .then(res => {
            this.name = res.data.data.FirstName + " "+res.data.data.LastName;
        })
        .catch(error => {
          console.log(error.response.data.error);
          if (
            error.response.data.error != null &&
            error.response.data.error != ""
          ) {
            this.$swal.fire("Sorry !", error.response.data.error, "warning");
          } else {
            this.$swal.fire(
              "",
              "Somthing wrong happend while retrieving driver name !",
              "warning"
            );
          }
        });
    },
    hideAll() {
      this.day_show = false;
      this.currentMonth_show = false;
      this.pastMonth_show = false;
      this.currentYear_show = false;
      this.pastYear_show = false;
    },

    day() {
      this.hideAll();
      this.day_show = true;
    },
    currentMonth() {
      this.hideAll();
      this.currentMonth_show = true;
    },
    pastMonth() {
      this.hideAll();
      this.pastMonth_show = true;
    },
    currentYear() {
      this.hideAll();
      this.currentYear_show = true;
    },
    pastYear() {
      this.hideAll();
      this.pastYear_show = true;
    }
  }
};
</script>
<style scoped>
.wrapper_box {
  display: grid;
  grid-gap: 2em;
  grid-auto-rows: minmax(10px, auto);
}
.search_box {
  background-color: rgb(255, 51, 61);
}
.table_box {
  background-color: rgb(65, 35, 150);
}
.pagination_box {
  background-color: rgb(255, 153, 0);
}
a,
a:hover {
  text-decoration: none;
}
.wrapper_header {
  display: grid;
  grid-template-columns: 10fr 2fr;
  grid-auto-rows: minmax(50px, auto);
  align-items: center;
  /* background-color: rgb(255, 153, 0); */
}
.title_header {
  justify-self: start;
  font-family: "roboto";
  font-size: 3em;
  color: rgb(124, 124, 124);
}
.button_header {
  justify-self: end;
}
.sentence {
  margin-top: 2em;
  font-family: "Quicksand", sans-serif;
  justify-self: center;
  font-size: 2.5em;
}
</style>