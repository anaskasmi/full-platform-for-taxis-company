<template>
  <div>
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>
    <!-- back button -->
    <div class="row col-12">
      <v-btn color="grey" text tile class="float-left" :to="specialShiftsRoute">
        <v-icon class="mr-2">keyboard_return</v-icon>special shifts
      </v-btn>
    </div>
    <hr />

    <v-tabs color="white" grow background-color="primary" dark class="mb-10">
      <v-tab v-if="openShift_show">Open shift</v-tab>
      <v-tab v-if="specialShift.ShiftType=='coveringshift'">Covering shift</v-tab>
    </v-tabs>

    <div v-if="openShift_show">
      <!-- badge id  -->
      <div class="form-group">
        <v-text-field label="Driver ID" v-model="specialShift.PermitNumber" type="text"></v-text-field>
      </div>

      <!-- start date time of trip  -->
      <div class="form-group row col-12 px-0 mx-0">
        <!--<v-text-field label="DATE AND TIME" v-model="trip.date"  type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00" name="date">
        </v-text-field>-->

        <!-- date -->
        <v-col cols="6" class="pl-0 float-left">
          <v-dialog
            ref="dateDate"
            v-model="modal_dateDate"
            :return-value.sync="specialShift.SingInDate"
            persistent
            width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="specialShift.SingInDate"
                label="Date"
                prepend-icon="event"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="specialShift.SingInDate" scrollable>
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateDate = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateDate.save(specialShift.SingInDate)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-col>

        <!-- time -->
        <v-col cols="6" class="pr-0 mr-0 float-right">
          <v-dialog
            ref="dateTime"
            v-model="modal_dateTime"
            :return-value.sync="specialShift.SingInTime"
            persistent
            width="350px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="specialShift.SingInTime"
                label="Time"
                prepend-icon="access_time"
                v-on="on"
              ></v-text-field>
            </template>
            <v-time-picker v-if="modal_dateTime" v-model="specialShift.SingInTime" format="24hr">
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateTime = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateTime.save(specialShift.SingInTime)">OK</v-btn>
            </v-time-picker>
          </v-dialog>
        </v-col>
      </div>
      <!-- badge id  -->
      <div class="form-group">
        <v-text-field label="Car" v-model="specialShift.Car" type="text" name="job_id"></v-text-field>
      </div>
      <!-- submit btn -->
      <div class="row col-12 mt-4">
        <v-btn
          color="primary"
          tile
          class="float-left"
          type="submit"
          @click.prevent="AddOpenShift()"
        >submit</v-btn>
        <v-btn
          color="grey lighten-1"
          dark
          tile
          class="float-left mx-4"
          @click="$router.go(-1)"
        >cancel</v-btn>
      </div>
    </div>

    <!-- covering shift -->
    <div v-if="coveringShift_show">
      <!-- badge id  -->
      <div class="form-group">
        <v-text-field label="Driver ID" v-model="specialShift.PermitNumber" type="text"></v-text-field>
      </div>

      <!-- start date time of trip  -->
      <div class="form-group row col-12 px-0 mx-0">
        <!--<v-text-field label="DATE AND TIME" v-model="trip.date"  type="datetime-local"  min="1000-01-01T00:00" max="3000-01-01T00:00" name="date">
        </v-text-field>-->

        <!-- date -->
        <v-col cols="6" class="pl-0 float-left">
          <v-dialog
            ref="dateDate"
            v-model="modal_dateDate"
            :return-value.sync="specialShift.SingInDate"
            persistent
            width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="specialShift.SingInDate"
                label="Date"
                prepend-icon="event"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="specialShift.SingInDate" scrollable>
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateDate = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateDate.save(specialShift.SingInDate)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-col>

        <!-- time -->
        <v-col cols="6" class="pr-0 mr-0 float-right">
          <v-dialog
            ref="dateTime"
            v-model="modal_dateTime"
            :return-value.sync="specialShift.SingInTime"
            persistent
            width="350px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="specialShift.SingInTime"
                label="Time"
                prepend-icon="access_time"
                v-on="on"
              ></v-text-field>
            </template>
            <v-time-picker v-if="modal_dateTime" v-model="specialShift.SingInTime" format="24hr">
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateTime = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateTime.save(specialShift.SingInTime)">OK</v-btn>
            </v-time-picker>
          </v-dialog>
        </v-col>
      </div>

      <div class="form-group row col-12 px-0 mx-0">
        <v-col cols="6" class="pl-0 float-left">
          <!-- car   -->
          <div class="form-group">
            <v-text-field label="Car" v-model="specialShift.Car" type="text" name="job_id"></v-text-field>
          </div>
        </v-col>

        <v-col cols="6" class="pr-0 mr-0 float-right">
          <!-- car covered by  -->
          <div class="form-group">
            <v-text-field
              label="Car Covered by"
              v-model="specialShift.CarCoveredBy"
              type="text"
              name="job_id"
            ></v-text-field>
          </div>
        </v-col>
      </div>

      <!-- submit btn -->
      <div class="row col-12 mt-4">
        <v-btn
          color="primary"
          tile
          class="float-left"
          type="submit"
          @click.prevent="AddCoveringShift()"
        >submit</v-btn>
        <v-btn
          color="grey lighten-1"
          dark
          tile
          class="float-left mx-4"
          @click="$router.go(-1)"
        >cancel</v-btn>
      </div>
    </div>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  components: {
    NavbarAdmin,
    Drawer
  },
  created() {
    this.fetchItems();
  },
  data() {
    return {
      inputErrors: [],
      specialShiftsRoute: { name: "AdminDashboard_specialShifts" },
      specialShift: {
        PermitNumber: "",
        SingInDate: "",
        SingInTime: "",
        Car: "",
        CarCoveredBy: "",
        ShiftType: "",
        _method: "patch"
      },
      modal_dateDate: false,
      modal_dateTime: false,
      id: "",
      openShift_show: false,
      coveringShift_show: false
    };
  },

  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    pad(n) {
      return n < 10 ? "0" + n : n;
    },
    separateDates() {

      var date = new Date(this.specialShift.SingInTime);
      var day = date.getDate();
      var month = date.getMonth(); //Be careful! January is 0 not 1
      var year = date.getFullYear();
      var hour = date.getHours();
      var minute = date.getMinutes();
      this.specialShift.SingInDate =
        year + "-" + this.pad(month + 1) + "-" + this.pad(day);
      this.specialShift.SingInTime = this.pad(hour) + ":" + this.pad(minute);

    },
    fetchItems() {
      this.id = this.$route.params.id;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/specialShift/" + this.id)
        .then(res => {
          this.specialShift = res.data.data;
            console.log(this.specialShift);
          this.separateDates();

          if (this.specialShift.ShiftType == "openshift") {
            this.hideAll();
            this.openShift_tab();
          } else if (this.specialShift.ShiftType == "coveringshift") {
            this.hideAll();
            this.coveringShift_tab();
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    AddOpenShift() {
      this.id = this.$route.params.id;
      this.specialShift.ShiftType = "openshift";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .put(
          this.BASE_URL() + "/api/admin/specialShift/" + this.id,
          this.specialShift
        )
        .then(response => {
          this.$router.push({
            name: "AdminDashboard_specialShifts_show",
            params: { id: this.id }
          });
        })
        .catch(error => {
          this.inputErrors = error.response.data.errors;
          // window.scrollTo(0,0);
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    },

    AddCoveringShift() {
      this.specialShift.ShiftType = "coveringshift";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .put(
          this.BASE_URL() + "/api/admin/specialShift/" + this.id,
          this.specialShift
        )
        .then(response => {
          console.log("add covering :");
          console.log(response);
          this.$router.push({
            name: "AdminDashboard_specialShifts_show",
            params: { id: this.id }
          });
        })
        .catch(error => {
          this.inputErrors = error.response.data.errors;
          // window.scrollTo(0,0);
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    },

    hideAll() {
      this.openShift_show = false;
      this.coveringShift_show = false;
    },
    openShift_tab() {
      this.hideAll();
      this.openShift_show = true;
    },
    coveringShift_tab() {
      this.hideAll();
      this.coveringShift_show = true;
    }
  }
};
</script>


<style scoped>
.component-fade-enter-active,
.component-fade-leave-active {
  transition: opacity 0.4s ease;
}
.component-fade-enter, .component-fade-leave-to
    /* .component-fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
a:hover {
  text-decoration: none;
}
</style>
