<template>
  <div>
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>
    <!-- back button -->
    <div class="row col-12">
      <v-btn color="grey" text tile class="float-left" :to="directorsRoute">
        <v-icon class="mr-2">keyboard_return</v-icon>special shifts
      </v-btn>
    </div>
    <hr />

    <v-tabs  color="white" grow background-color="primary" dark class="mb-10">
      <v-tab @click="openShift_tab()">Open shift</v-tab>
      <v-tab @click="coveringShift_tab()">Covering shift</v-tab>
    </v-tabs>

    <div v-if="openShift_show">
      <!-- badge id  -->
      <div class="form-group">
        <v-text-field label="Driver ID" v-model="openShift.PermitNumber" type="text"></v-text-field>
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
            :return-value.sync="openShift.SingInDate"
            persistent
            width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="openShift.SingInDate"
                label="Date"
                prepend-icon="event"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="openShift.SingInDate" scrollable>
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateDate = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateDate.save(openShift.SingInDate)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-col>

        <!-- time -->
        <v-col cols="6" class="pr-0 mr-0 float-right">
          <v-dialog
            ref="dateTime"
            v-model="modal_dateTime"
            :return-value.sync="openShift.SingInTime"
            persistent
            width="350px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="openShift.SingInTime"
                label="Time"
                prepend-icon="access_time"
                v-on="on"
              ></v-text-field>
            </template>
            <v-time-picker v-if="modal_dateTime" v-model="openShift.SingInTime" format="24hr">
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateTime = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateTime.save(openShift.SingInTime)">OK</v-btn>
            </v-time-picker>
          </v-dialog>
        </v-col>
      </div>
      <!-- badge id  -->
      <div class="form-group">
        <v-text-field label="Car" v-model="openShift.Car" type="text" name="job_id"></v-text-field>
      </div>
      <!-- submit btn -->
      <div class="row col-12 mt-4">
        <v-btn color="primary" tile class="float-left" type="submit" @click.prevent="AddOpenShift()">submit</v-btn>
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
        <v-text-field label="Driver ID" v-model="coveringShift.PermitNumber" type="text"></v-text-field>
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
            :return-value.sync="coveringShift.SingInDate"
            persistent
            width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="coveringShift.SingInDate"
                label="Date"
                prepend-icon="event"
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="coveringShift.SingInDate" scrollable>
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateDate = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateDate.save(coveringShift.SingInDate)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-col>

        <!-- time -->
        <v-col cols="6" class="pr-0 mr-0 float-right">
          <v-dialog
            ref="dateTime"
            v-model="modal_dateTime"
            :return-value.sync="coveringShift.SingInTime"
            persistent
            width="350px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="coveringShift.SingInTime"
                label="Time"
                prepend-icon="access_time"
                v-on="on"
              ></v-text-field>
            </template>
            <v-time-picker v-if="modal_dateTime" v-model="coveringShift.SingInTime" format="24hr">
              <div class="flex-grow-1"></div>
              <v-btn text color="primary" @click="modal_dateTime = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dateTime.save(coveringShift.SingInTime)">OK</v-btn>
            </v-time-picker>
          </v-dialog>
        </v-col>
      </div>




      <div class="form-group row col-12 px-0 mx-0">
        <v-col cols="6" class="pl-0 float-left">
            <!-- car   -->
            <div class="form-group">
              <v-text-field label="Car" v-model="coveringShift.Car" type="text" name="job_id"></v-text-field>
            </div>
        </v-col>

        <v-col cols="6" class="pr-0 mr-0 float-right">
            <!-- car covered by  -->
            <div class="form-group">
              <v-text-field label="Car Covered by" v-model="coveringShift.CarCoveredBy" type="text" name="job_id"></v-text-field>
            </div>
        </v-col>

      </div>





    

      <!-- submit btn -->
      <div class="row col-12 mt-4">
        <v-btn color="primary" tile class="float-left" type="submit" @click.prevent="AddCoveringShift()">submit</v-btn>
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
    this.openShift_tab();
  },
  data() {
    return {
      inputErrors: [],
      directorsRoute: { name: "AdminDashboard_specialShifts" },
      openShift: {
        PermitNumber: "",
        SingInDate: "",
        SingInTime: "",
        Car: "",
        ShiftType: "openshift"
      },
      coveringShift: {
        PermitNumber: "",
        SingInDate: "",
        SingInTime: "",
        Car: "",
        CarCoveredBy: "",
        ShiftType: "coveringshift"
      },
      openShift_show: false,
      coveringShift_show: false,
      modal_dateDate: false,
      modal_dateTime: false
    };
  },

  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },

    AddOpenShift() {
      this.openShift.ShiftType = "openshift";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .post(this.BASE_URL() + "/api/admin/specialShift", this.openShift)
        .then(response => {
          console.log('Add OpenShift :')
          console.log(response)
          this.$router.push({
            name: "AdminDashboard_specialShifts_show",
            params: { id: response.data.data.id }
          });
        })
        .catch(error => {
          this.inputErrors = error.response.data.errors;
          // window.scrollTo(0,0);
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    },

    AddCoveringShift() {
      this.coveringShift.ShiftType = "coveringshift";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .post(
          this.BASE_URL() + "/api/admin/specialShift",
          this.coveringShift
        )
        .then(response => {
          console.log('add covering :')
          console.log(response)
          this.$router.push({
            name: "AdminDashboard_specialShifts_show",
            params: { id: response.data.data.id }
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
