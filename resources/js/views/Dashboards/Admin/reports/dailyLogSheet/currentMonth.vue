<template>
  <div>
    <!-- date picker -->
    <!-- button to toggle the the date picker -->
    <div class="row col-12">
      <v-tooltip top v-if="!noShiftsFound">
        <template v-slot:activator="{ on }">
          <a
            :href="BASE_URL() +
          '/api/admin/reports/currentMonth/' +
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
    </div>
    <!-- table -->
    <div v-if="table_show" class="table-responsive-lg">
      <table class="table table-bordered table-hover">
        <thead class="thead-dark">
          <tr>
            <th class="align-middle" scope="col">JOBID</th>
            <th class="align-middle" scope="col">LOAD TIME</th>
            <th class="align-middle" scope="col">UNLOAD TIME</th>
            <th class="align-middle" scope="col">CUSTOMER</th>
            <th class="align-middle" scope="col">PICK UP</th>
            <th class="align-middle" scope="col">DROP OFF</th>
            <th class="align-middle" scope="col">FARE</th>
            <th class="align-middle" scope="col">TIP</th>
            <th class="align-middle" scope="col">TOTAL</th>
            <th class="align-middle" scope="col">TRIP TYPE</th>
            <th class="align-middle" scope="col">ACCOUNT NAME</th>
            <th class="align-middle" scope="col">ACCOUNT NUMBER</th>
            <th class="align-middle" scope="col">PO</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="trip in trips" v-bind:key="trip.trip_id">
            <td>{{trip.job_id}}</td>
            <td>{{trip.pickup_date}}</td>
            <td>{{trip.dropoff__date}}</td>
            <td>{{trip.customer_name}}</td>
            <td>{{trip.pickup}}</td>
            <td>{{trip.dropoff}}</td>
            <td>{{trip.fare}}</td>
            <td>{{trip.tip}}</td>
            <td>{{trip.total}}</td>
            <td>{{trip.account}}</td>
            <td></td>
            <td></td>
            <td>{{trip.po}}</td>
          </tr>
          <tr class="table table-borderless">
            <td colspan="6" class="table-success" style="font-size: 1.2em;">Total</td>
            <td
              class="table-primary font-weight-bold"
              style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
            >${{total_fare}}</td>
            <td
              class="table-primary font-weight-bold"
              style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
            >${{total_tip}}</td>
            <td
              class="table-primary font-weight-bold"
              style="font-size: 0.8em;  font-weight: bold; max-width:100%; white-space:nowrap;"
            >${{total_total}}</td>

            <td colspan="3" class="table-success" style="font-size: 1.2em;">Trips</td>
            <td class="table-primary font-weight-bold">{{nbr_trips}}</td>
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
import NavbarAdmin from "@/js/components/navbars/Admin.vue";

export default {
  beforeMount() {
    this.noShiftsFound = false;
    this.table_show = false;
    this.getReport(this.date);
  },
  components: {
    NavbarAdmin
  },
  computed: {
    nbr_trips() {
      return this.trips.length;
    }
  },
  data() {
    return {
      date: new Date().toISOString().substr(0, 10),
      modal: false,
      noShiftsFound: false,
      table_show: false,
      trips: {},
      total_fare: 0,
      total_tip: 0,
      total_total: 0,
      cpt: 0
    };
  },
  props: {
    idEncrypted: "",
    id: ""
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    load() {
      this.$swal.fire("", "Your PDF is Ready!", "success");
    },
    pdf() {
      this.$swal.fire("", "Loading..", "success");

      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;

      axios
        .get(
          this.BASE_URL() +
            "/api/admin/reports/currentMonth/" +
            this.id +
            "/printPDF/"
        )
        .then(res => {
          this.$swal.fire("", "Your PDF is ready !", "success");
        })
        .catch(error => {
          console.log("error pdf :");
          console.log(error.response.data.error);
          if (
            error.response.data.error != null &&
            error.response.data.error != ""
          ) {
            this.$swal.fire("Sorry !", error.response.data.error, "warning");
          } else {
            this.$swal.fire("", "Somthing wrong happend !", "warning");
          }
        });
    },
    getReport(date) {
      this.noShiftsFound = false;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(
          this.BASE_URL() +
            "/api/admin/reports/DailyLogSheet/currentMonth/" +
            this.id,
          {
            date: this.date
          }
        )
        .then(res => {
          if (res.data.data.trips.length == 0) {
            this.noShiftsFound = true;
            this.table_show = false;
          } else {
            this.table_show = true;
            this.trips = res.data.data.trips;
            this.total_fare = res.data.data.total_fare;
            this.total_tip = res.data.data.total_tip;
            this.total_total = res.data.data.total_total;
          }
        })
        .catch(error => {
          console.log("error :");
          console.log(error);
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