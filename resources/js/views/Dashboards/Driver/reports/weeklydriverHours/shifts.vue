<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div>
                <!-- pdf download button -->
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <a
                            :href="BASE_URL() +
          '/api/driver/reports/WeeklyDriverHours/' +
          idEncrypted +
          '/printPDF/'+date"
                            target="_blank"
                        >
                            <v-btn v-on="on" depressed fab small color="success" class="mr-2 my-auto" @click="load()">
                                <v-icon small color="white lighten-4">save_alt</v-icon>
                            </v-btn>
                        </a>
                    </template>
                    <span>download this report as PDF</span>
                </v-tooltip>
                <div>
                    <v-alert
                        border="left"
                        tile
                        dense
                        dark
                        color="primary "
                        elevation="2"
                        class="mt-4"
                        align="center"
                    >
                        <v-icon class="mb-1" color="primary">description</v-icon>Weekly Shifts
                    </v-alert>
                </div>
                <!-- table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Driver</th>
                            <th scope="col">Taxi Number</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">
                                Total Time
                                <small class="text-light">(min)</small>
                            </th>
                            <th scope="col">
                                Break Time
                                <small class="text-light">(min)</small>
                            </th>
                            <th scope="col">
                                Work Time
                                <small class="text-light">(min)</small>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="(shift,i) in shifts" :key="i">
                            <td>{{shift.FirstName}} {{shift.LastName}}</td>
                            <td>{{shift.vehicle_id}}</td>
                            <td>{{shift.shift_start_time}}</td>
                            <td>{{shift.shift_end_time}}</td>
                            <td>{{shift.total_time}}</td>
                            <td>{{shift.break_time}}</td>
                            <td>{{shift.work_time}}</td>
                        </tr>
                        <!-- total row 1 -->
                        <tr class="table-primary">
                            <td colspan="4" class="text-uppercase">{{name}} TOTAL</td>

                            <td v-if="total_row['total_time_hours']==0 && total_row['total_time_min'] ==0">-</td>
                            <td v-else>{{total_row['total_time_hours']}}H:{{total_row['total_time_min']}}Min</td>
                            <td v-if="total_row['break_time_hours']==0 && total_row['break_time_min'] ==0">-</td>
                            <td v-else>{{ total_row['break_time_hours']}}H: {{total_row['break_time_min']}}Min</td>
                            <td v-if="total_row['work_time_hours']==0 && total_row['work_time_min'] ==0">-</td>
                            <td v-else>{{total_row['work_time_hours']}}H: {{total_row['work_time_min']}}Min</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <v-pagination :total-visible="5" v-model="current_page" :length="last_page" @input="next"></v-pagination>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <div>
                <!-- pdf download button -->
                <v-tooltip top>
                    <template v-slot:activator="{ on }">
                        <a
                            :href="BASE_URL() +
          '/api/driver/reports/WeeklyDriverHours/' +
          idEncrypted +
          '/printPDF/'+date"
                            target="_blank"
                        >
                            <v-btn v-on="on" depressed fab small color="success" class="mr-2 my-auto" @click="load()">
                                <v-icon small color="white lighten-4">save_alt</v-icon>
                            </v-btn>
                        </a>
                    </template>
                    <span>download this report as PDF</span>
                </v-tooltip>
                <div>
                    <v-alert
                        border="left"
                        tile
                        dense
                        dark
                        color="primary "
                        elevation="2"
                        class="mt-4"
                        align="center"
                    >
                        <v-icon class="mb-1" color="primary">description</v-icon>Weekly Shifts
                    </v-alert>
                </div>
                <!-- table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="align-middle fitCell">Driver</th>
                            <th scope="col" class="align-middle fitCell">Taxi Number</th>
                            <th scope="col" class="align-middle fitCell">Start Time</th>
                            <th scope="col" class="align-middle fitCell">End Time</th>
                            <th scope="col" class="align-middle fitCell">
                                Total Time
                                <small class="text-light">(min)</small>
                            </th>
                            <th scope="col" class="align-middle fitCell">
                                Break Time
                                <small class="text-light">(min)</small>
                            </th>
                            <th scope="col" class="align-middle fitCell">
                                Work Time
                                <small class="text-light">(min)</small>
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="(shift,i) in shifts" :key="i">
                            <td class="align-middle fitCell">{{shift.FirstName}} {{shift.LastName}}</td>
                            <td class="align-middle fitCell">{{shift.vehicle_id}}</td>
                            <td class="align-middle fitCell">{{shift.shift_start_time}}</td>
                            <td class="align-middle fitCell">{{shift.shift_end_time}}</td>
                            <td class="align-middle fitCell">{{shift.total_time}}</td>
                            <td class="align-middle fitCell">{{shift.break_time}}</td>
                            <td class="align-middle fitCell">{{shift.work_time}}</td>
                        </tr>
                        <!-- total row 1 -->
                        <tr class="table-primary">
                            <td colspan="4" class="text-uppercase">{{name}} TOTAL</td>

                            <td v-if="total_row['total_time_hours']==0 && total_row['total_time_min'] ==0">-</td>
                            <td v-else class="align-middle fitCell">{{total_row['total_time_hours']}}H:{{total_row['total_time_min']}}Min</td>
                            <td v-if="total_row['break_time_hours']==0 && total_row['break_time_min'] ==0">-</td>
                            <td v-else class="align-middle fitCell">{{ total_row['break_time_hours']}}H: {{total_row['break_time_min']}}Min</td>
                            <td v-if="total_row['work_time_hours']==0 && total_row['work_time_min'] ==0">-</td>
                            <td v-else class="align-middle fitCell">{{total_row['work_time_hours']}}H: {{total_row['work_time_min']}}Min</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <v-pagination :total-visible="5" v-model="current_page" :length="last_page" @input="next"></v-pagination>
            </div>
        </div>

    </div>

</template>
<script>
export default {
  created() {
    this.fetchItems();
  },
  props: {
    date: "",
    id: "",
    idEncrypted: "",

  },
  // computed: {
  //   noShiftsFound(){
  //     if(this.shifts == null || this.shifts.length == 0 )
  //     {
  //       return true;
  //     }
  //     else
  //     {
  //       return false;
  //     }
  //   }
  // },
  data() {
    return {
      last_page: 1,
      current_page: 1,
      shifts: {},
      name: "",
      total_row: {}
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    pdf() {
      this.$swal.fire("", "loading..", "success");
      axios
        .get(
          this.BASE_URL() +
            "/api/driver/reports/WeeklyDriverHours/" +
            this.id +
            "/printPDF/" +
            this.date
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
    next(page) {
      this.fetchItems(
        this.BASE_URL() +
          "/api/driver/reports/WeeklyDriverHours?date=" +
          this.date +
          "&page=" +
          page
      );
    },
    fetchItems(URL) {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;
      axios
        .get(
          URL ||
            this.BASE_URL() +
              "/api/driver/reports/WeeklyDriverHours?date=" +
              this.date
        )
        .then(res => {
          this.name = res.data.name;
          this.shifts = res.data.shifts.data;
          this.total_row = res.data.total_row;
          if (
            res.data.shifts.last_page != "" &&
            res.data.shifts.last_page != null &&
            res.data.shifts.last_page != 0
          ) {
            this.last_page = res.data.shifts.last_page;
          } else {
            this.last_page = 1;
          }
          this.current_page = res.data.shifts.current_page;
        })
        .catch(error => {
          console.log("error in fetch shifts child drovers hours: " + error);
        });
    }
  }
};
</script>
<style>
    .fitCell{
        max-width:100%;white-space:nowrap;
    }
</style>
