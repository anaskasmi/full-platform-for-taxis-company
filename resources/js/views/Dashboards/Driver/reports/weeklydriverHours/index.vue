<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div class="wrapper_box">
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <!-- header -->
                <!-- header -->
                <div class="wrapper_header mt-10">
                    <div class="title_header text-uppercase float-left">Weekly Driver Hours</div>
                </div>
                <div class="title_header text-uppercase float-left">
                    <v-btn color="grey" text tile class="float-left" :to="backRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>Reports
                    </v-btn>
                </div>

                <hr />

                <div v-if="datePickerShow">
                    <div class="sentence">Pick a week !</div>
                    <div v-if="datePickerShow">
                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#grey lighten-5 "
                            elevation="2"
                            class="mt-12 font-weight-black"
                            align="center"
                        >
                            {{startOfWeek(date)}}
                            <v-icon class="mb-1" color="primary">keyboard_arrow_left</v-icon>
                            <v-icon class="mb-1" color="primary">keyboard_arrow_right</v-icon>
                            {{endOfWeek(date)}}
                        </v-alert>
                    </div>
                    <div>
                        <v-date-picker
                            v-model="date"
                            full-width
                            show-current="false"
                            :landscape="$vuetify.breakpoint.smAndUp"
                            class="mt-4"
                        ></v-date-picker>
                    </div>
                    <v-btn class="my-2" block color="primary" @click="fetchItems()">Get repport</v-btn>
                </div>
                <div v-if="noShiftsFound">
                    <v-alert
                        border="left"
                        tile
                        dense
                        type="warning"
                        elevation="2"
                        class="mt-0 font-weight-medium"
                        align="center"
                    >Sorry no shifts Found! try another Week!</v-alert>
                </div>
                <shifts v-if="shiftsShow" :date="date" :id="id" :idEncrypted="idEncrypted"></shifts>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDriver></NavbarDriver>
            <div class="container px-4">
                <div class="">
                    <!-- header -->
                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        Weekly Driver Hours
                    </div>



                    <hr />

                    <div v-if="datePickerShow">
                        <div class="sentence text-center">Pick a week !</div>
                        <v-alert

                            tile
                            dense
                            color="#2A3B4D"
                            elevation="2"
                            class="mt-12 font-weight-light"
                            align="center"
                        >
                            <div class=" text-center text-white">PS : To select a week, try to click on any Day from withing that week !</div>

                        </v-alert>
                        <div v-if="datePickerShow">
                            <v-alert
                                border="top"
                                tile
                                dense
                                color="#grey lighten-5 "
                                elevation="2"
                                class="mt-12 font-weight-black"
                                align="center"
                            >
                                <div class="">
                                    <span class="row justify-content-center mb-2 ">
                                        Week :
                                </span>
                                    <span class=" row justify-content-center ">
                                    {{startOfWeek(date)}}
                                <v-icon class="mb-1" color="primary">keyboard_arrow_left</v-icon>
                                <v-icon class="mb-1" color="primary">keyboard_arrow_right</v-icon>
                                {{endOfWeek(date)}}
                                </span>
                                </div>


                            </v-alert>
                        </div>
                        <div>
                            <v-date-picker
                                v-model="date"
                                full-width
                                show-current="false"
                                :landscape="$vuetify.breakpoint.smAndUp"
                                class="mt-4"
                            ></v-date-picker>
                        </div>
                        <v-btn class="my-2" block color="primary" @click="fetchItems()">Get repport</v-btn>
                    </div>
                    <div v-if="noShiftsFound">
                        <v-alert
                            border="left"
                            tile
                            dense
                            type="warning"
                            elevation="2"
                            class="mt-0 font-weight-medium"
                            align="center"
                        >Sorry no shifts Found! try another Week!</v-alert>
                    </div>
                    <shifts v-if="shiftsShow" :date="date" :id="id" :idEncrypted="idEncrypted"></shifts>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
import NavbarDriver from "@/js/components/navbars/Driver.vue";
import Drawer from "@/js/components/drawers/Driver.vue";
import shifts from "@/js/views/Dashboards/Driver/reports/weeklydriverHours/shifts.vue";

export default {
  created() {
    this.datePickerShow = true;
    this.getUser();
  },

  components: {
    NavbarDriver,
    Drawer,
    shifts,
    Drawer
  },

  data() {
    return {
      shifts: null,
      dateTitle: "",
      datePickerShow: true,
      dateTitleShow: false,
      date: new Date().toISOString().substr(0, 10),
      noShiftsFound: false,
      name: "",
      id: ""
    };
  },
  computed: {
    backRoute() {
      return { name: "DriverDashboard_reports" };
    },
    idEncrypted() {
      return this.id * 2 + 2019;
    },
    shiftsShow() {
      if (this.shifts) {
        if (this.shifts.data.length != 0) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    getUser() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;
      axios
        .get(this.BASE_URL() + "/api/driver/auth/user")
        .then(res => {
          console.log(res);
          this.name = res.data.FirstName + " " + res.data.LastName;
          this.id = res.data.PermitNumber;
        })
        .catch(err => {
          console.log(err);
        });
    },
    fetchItems() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;
      axios
        .get(
          this.BASE_URL() +
            "/api/driver/reports/WeeklyDriverHours?date=" +
            this.date
        )
        .then(res => {
          if (res.data.shifts.data.length != 0) {
            this.datePickerShow = false;
            this.noShiftsFound = false;
          } else {
            this.datePickerShow = true;
            this.noShiftsFound = true;
          }
          this.shifts = res.data.shifts;
        })
        .catch(error => {
          console.log("error in fetch items in driver Hours: " + error);
        });
    },
    startOfWeek(d) {
      return this.$moment(d)
        .startOf("isoWeek")
        .format("YYYY-MM-DD"); //previous monday
    },
    endOfWeek(d) {
      return this.$moment(d)
        .day(7)
        .format("YYYY-MM-DD"); //next sunday
    },
    dateRange(d) {
      return this.startOfWeek(d) + " - " + this.endOfWeek(d);
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
