<template>
  <div>
    <!-- date picker -->
    <!-- button to toggle the the date picker -->
    <!-- <div class="row col-12">
      <v-tooltip top v-if="!noShiftsFound">
        <template v-slot:activator="{ on }">
          <a
            :href="BASE_URL() +
          '/api/driver/reports/pastMonthShifts/' +
          idEncrypted +
          '/printPDF/'"
            target="_blank"
          >
            <v-btn
              v-on="on"
              depressed
              fab
              small
              color="success"
              class="mr-2 my-auto"
              @click="load()"
            >
              <v-icon small color="white lighten-4">save_alt</v-icon>
            </v-btn>
          </a>
        </template>
        <span>download this report as PDF</span>
      </v-tooltip>
    </div>-->
    <!-- table -->
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
        <v-icon class="mb-1" color="primary">description</v-icon>Current Month Shifts
      </v-alert>
    </div>
    <!-- progress bar -->
    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
    <!-- table -->
    <div class="table-responsive" v-if="!isLoading">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Driver</th>
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
            <td>{{shift.FirstName}} {{shift.LastName}}</td>
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
            <td class="table-primary text-uppercase" colspan="4">TOTALS</td>
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
</template>
<script>
export default {
  beforeMount() {
    this.table_show = false;
    this.fetchItems();
    this.$store.commit("showCurrentMonth");
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
    }
  },

  data() {
    return {
      isLoading:true,
      modal: false,
      table_show: false,
      shifts: {},
      total_fare: 0,
      total_tip: 0,
      total_total: 0,
      cpt: 0,
      totals: ""
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
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;
      axios
        .get(
          URL ||
            this.BASE_URL() +
              "/api/driver/reports/DailyLogSheet/currentMonthShifts/"
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
</style>