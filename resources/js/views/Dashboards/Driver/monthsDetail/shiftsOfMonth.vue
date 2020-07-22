<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div>
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <v-btn color="grey" text tile class="float-left" :to="lastRoute">
                    <v-icon class="mr-2">keyboard_return</v-icon>Back to months
                </v-btn>
                <br>
                <br>
                <hr>
                <div>
                    <v-alert
                        border="left"
                        tile
                        dense
                        dark
                        color="primary "
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >
                        <v-icon class="mb-1" color="primary">description</v-icon>
                        {{monthName}} {{year}} Shifts
                    </v-alert>
                </div>
                <!-- progress bar -->
                <v-progress-linear
                    v-if="isLoading"
                    indeterminate
                    color="cyan"
                ></v-progress-linear>
                <!-- table -->
                <div class="table-responsive" v-if="!isLoading">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Taxi Number</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Fare</th>
                            <th scope="col">Tip</th>
                            <th scope="col">Total</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="(shift,i) in shifts" :key="i">
                            <td>{{shift.vehicle_id}}</td>
                            <td>{{shift.shift_start_time}}</td>
                            <td>{{shift.shift_end_time}}</td>
                            <td>$ {{shift.shiftFare}}</td>
                            <td>$ {{shift.shiftTip}}</td>
                            <td>$ {{shift.shiftTotal}}</td>

                            <td @click="showShift(shift.driver_shift_id)">
                                <v-icon class="pa-0 ma-0" style="cursor: pointer;" color="primary">description</v-icon>
                            </td>
                            <!-- is total row 1 -->
                        </tr>
                        <tr>
                            <td class="table-primary text-uppercase" colspan="3">TOTALS</td>
                            <td class="table-primary">${{totals.allShiftsFare}}</td>
                            <td class="table-primary">${{totals.allShiftsTip}}</td>
                            <td class="table-primary">${{totals.allShiftsTotal}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- alert -->
                <v-alert
                    v-if="noShiftsFound"
                    border="left"
                    tile
                    type="warning"
                    dense
                    dark
                    elevation="2"
                    class="mb-12"
                    align="center"
                >Sorry, No shifts Found For this Month!</v-alert>
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
                <div>


                    <v-btn color="grey" text tile class="float-left" :to="lastRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>Back to months
                    </v-btn>
                    <br>
                    <br>
                    <hr>
                    <div>
                        <v-alert
                            border="left"
                            tile
                            dense
                            dark
                            color="primary "
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >
                            <v-icon class="mb-1" color="primary">description</v-icon>
                            {{monthName}} {{year}} Shifts
                        </v-alert>
                    </div>
                    <!-- progress bar -->
                    <v-progress-linear
                        v-if="isLoading"
                        indeterminate
                        color="cyan"
                    ></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Taxi Number</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Fare</th>
                                <th scope="col">Tip</th>
                                <th scope="col">Total</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(shift,i) in shifts" :key="i">
                                <td class="fitCell">{{shift.vehicle_id}}</td>
                                <td class="fitCell">{{shift.shift_start_time}}</td>
                                <td class="fitCell">{{shift.shift_end_time}}</td>
                                <td class="fitCell">$ {{shift.shiftFare}}</td>
                                <td class="fitCell">$ {{shift.shiftTip}}</td>
                                <td class="fitCell">$ {{shift.shiftTotal}}</td>

                                <td @click="showShift(shift.driver_shift_id)">
                                    <v-icon class="pa-0 ma-0" style="cursor: pointer;" color="primary">description</v-icon>
                                </td>
                                <!-- is total row 1 -->
                            </tr>
                            <tr>
                                <td  class="table-primary text-uppercase fitCell" colspan="3">TOTALS</td>
                                <td class="table-primary fitCell  " >${{totals.allShiftsFare}}</td>
                                <td class="table-primary fitCell">${{totals.allShiftsTip}}</td>
                                <td class="table-primary fitCell">${{totals.allShiftsTotal}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- alert -->
                    <v-alert
                        v-if="noShiftsFound"
                        border="left"
                        tile
                        type="warning"
                        dense
                        dark
                        elevation="2"
                        class="mb-12"
                        align="center"
                    >Sorry, No shifts Found For this Month!</v-alert>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
import NavbarDriver from "@/js/components/navbars/Driver.vue";
import Drawer from "@/js/components/drawers/Driver.vue";

export default {
  components: {
    NavbarDriver,
    Drawer
  },
  beforeMount() {
    this.table_show = false;
    this.fetchItems();
  },
  computed: {
    nbr_shifts() {
      return this.shifts.length;
    },
    noShiftsFound() {
      if (this.shifts == "" || this.shifts == null || this.shifts.length <= 0) {
        return true;
      } else {
        return false;
      }
    },
    monthName() {
      let Month = "";
      switch (this.month) {
        case "1":
          Month = "January";
          break;
        case "2":
          Month = "February";
          break;
        case "3":
          Month = "March";
          break;
        case "4":
          Month = "April";
          break;
        case "5":
          Month = "May";
          break;
        case "6":
          Month = "June";
          break;
        case "7":
          Month = "July";
          break;
        case "8":
          Month = "August";
          break;
        case "9":
          Month = "September";
          break;
        case "10":
          Month = "October";
          break;
        case "11":
          Month = "November";
          break;
        case "12":
          Month = "December";
          break;
      }
      return Month;
    }
  },

  data() {
    return {
      isLoading:true,
      lastRoute: { name: "DriverDashboard_reports_dailyLogSheet" },
      modal: false,
      table_show: false,
      shifts: {},
      total_fare: 0,
      total_tip: 0,
      total_total: 0,
      cpt: 0,
      totals: "",
      month: "",
      year: ""
    };
  },
  props: {
    idEncrypted: ""
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    showShift(driver_shift_id) {
      this.$router.push({
        name: "DriverDashboard_TripsOfShift_show",
        params: { id: driver_shift_id }
      });
    },
    load() {
      this.$swal.fire("", "Your PDF is Ready!", "success");
    },
    pdf() {
      this.$swal.fire("", "loading..", "success");
      axios
        .get(
          this.BASE_URL() +
            "/api/driver/reports/currentMonthShifts/" +
            this.id +
            "/printPDF/"
        )
        .then(res => {
          this.$swal.fire("", "Your PDF is ready !", "success");
        })
        .catch(error => {
          console.log("error pdf :");
          console.log(error);
          this.$swal.fire("", "Somthing wrong happend !", "warning");
        });
    },
    fetchItems(URL) {
      this.month = this.$route.params.month;
      this.year = this.$route.params.year;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;
      axios
        .get(
          URL ||
            this.BASE_URL() +
              "/api/driver/shiftsOfMonth/" +
              this.month +
              "/" +
              this.year
        )
        .then(res => {
          this.shifts = res.data.shifts;
          this.totals = res.data.totals;
          this.isLoading=false;
        })
        .catch(error => {
          this.$swal("something wrong", error.response.data, "warning");
        });
    }
  }
};
</script>
<style scoped>
.wrapper_box {
  display: grid;
  grid-gap: 1em;
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
  margin-top: 4em;
  font-family: "Quicksand", sans-serif;
  justify-self: center;
  font-size: 3em;
  justify-self: center;
}
.fitCell{
    max-width:100%;white-space:nowrap;
}
</style>
