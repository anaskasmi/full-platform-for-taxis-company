<template>
  <div>
    <NavbarDispatcher></NavbarDispatcher>
    <Drawer></Drawer>

    <v-btn color="grey" text tile class="float-left" :to="specialShiftsRoute">
      <v-icon class="mr-2">keyboard_return</v-icon> special Shifts
    </v-btn>
    <v-btn
      color="success"
      outlined
      class="float-right mx-4"
      :to="specialShiftsRoute_edit"
    >
      <v-icon class="mr-2">edit</v-icon>edit
    </v-btn>
    <v-btn
      color="red "
      outlined
      class="float-right"
      @click="deleteSpecialShift(specialShift.id,specialShift.FirstName)"
    >
      <v-icon class="mr-2">delete</v-icon>delete
    </v-btn>
    <div class="table-responsive">
      <div class="col-5"></div>
      <div class="col-5"></div>

      <table class="table table-bordered table-hover">
        <tbody>
          <tr>
            <td style="width: 30%;">Permit Number</td>
            <td>{{specialShift.PermitNumber}}</td>
          </tr>
          <tr>
            <td>First Name</td>
            <td>{{specialShift.FirstName}}</td>
          </tr>
          <tr>
            <td>Last Name</td>
            <td>{{specialShift.LastName}}</td>
          </tr>

          <tr>
            <td>Shift Type</td>
            <td class="text-uppercase">{{specialShift.ShiftType}}</td>
          </tr>
          <tr v-if="specialShift.ShiftType =='openshift'">
            <td>Car</td>
            <td>{{specialShift.Car}}</td>
          </tr>
          <tr v-if="specialShift.ShiftType =='coveringshift'">
            <td>Car Covered</td>
            <td>{{specialShift.Car}}</td>
          </tr>
          <tr v-if="specialShift.ShiftType =='coveringshift'">
            <td>Car Covered By</td>
            <td>{{specialShift.CarCoveredBy}}</td>
          </tr>

          <tr>
            <td>Sing In Time</td>
            <td>{{specialShift.SingInTime}}</td>
          </tr>

          <tr>
            <td>Created by</td>
            <td v-if="specialShift.dispatcher_id=='admin'" class="text-success">Admin</td>
            <td v-else>{{specialShift.dispatchersName}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script>
import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
import Drawer from "@/js/components/drawers/Dispatcher.vue";

export default {
  components: {
    NavbarDispatcher,
    Drawer
  },
  created() {
    this.id = this.$route.params.id;
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.state.token_dispatcher;
    axios
      .get(this.BASE_URL() + "/api/dispatcher/specialShift/" + this.id)
      .then(res => {
        this.specialShift = res.data.data;
        console.log(this.specialShift);
      })
      .catch(error => {
        console.log(error);
      });
  },
  data() {
    return {
      specialShift: {},
      id: ""
    };
  },
  computed: {
    specialShiftsRoute() {
      return { name: "DispatcherDashboard_specialShifts" };
    },
    specialShiftsRoute_edit() {
      return {name : 'DispatcherDashboard_mySpecialShifts_edit',params:{id:this.id}};
    },
   
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    deleteSpecialShift(id, name) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "SpecialShift " + name + " will be deleted !",
          type: "question",
          animation: false,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#424242",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          if (result.value) {
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + this.$store.state.token_dispatcher;
            axios
              .delete(this.BASE_URL() + "/api/dispatcher/specialShift/" + id)
              .then(data => {
                this.$router.push(this.specialShiftsRoute);
              })
              .catch(err => console.log(err));
            this.swal.fire(
              "Deleted!",
              "Your file has been deleted.",
              "success"
            );
          }
        });
    }
  }
};
</script>


<style scoped>
a:hover {
  text-decoration: none;
}
</style>