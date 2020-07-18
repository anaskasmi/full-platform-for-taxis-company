<template>
  <div class="wrapper_box">
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>

    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase"> My Special Shifts</div>
      <v-btn
        link
        tile
        class="ma-2 button_header"
        outlined
        color="success "
        @click="addSpecialShift"
      >
        <v-icon left>mdi-pencil</v-icon>
        <div>New SpecialShift</div>
      </v-btn>
    </div>
    <hr />
    <!-- search start -->
    <div class="row">
      <div class="col-md-12 md-form active-cyan-2 mb-3" style="margin-bottom: -15px !important;">
        <form @submit.prevent="search(searchValue)">
          <div class="input-group mb-4">
            <div class="input-group-prepend">
              <button type="submit" class="btn btn-dark">
                <!-- <i class="fa fa-search"></i> -->
                <v-icon dark small>search</v-icon>
              </button>
            </div>
            <input
              type="search"
              name="searchValue"
              v-model="searchValue"
              placeholder="Search by name or badge ID"
              aria-describedby="button-addon7"
              class="form-control"
            />
          </div>
        </form>
      </div>
    </div>
    <!-- search end -->
    <div class="table-responsive">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Permit Number</th>
            <th scope="col">Shift Type</th>
            <th scope="col">Car</th>
            <th scope="col">Car covered</th>
            <th scope="col">Car covered by</th>
            <th scope="col">Sing In Time</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(specialShift,i) in specialShifts" :key="i">
            <td>{{specialShift.FirstName}} {{specialShift.FirstName}}</td>
            <td>{{specialShift.PermitNumber}}</td>
            <td>{{specialShift.ShiftType}}</td>
            <!-- if open shift -->
            <td v-if="specialShift.ShiftType =='openshift'">{{specialShift.Car}}</td>
            <td v-if="specialShift.ShiftType =='openshift'">-</td>
            <td v-if="specialShift.ShiftType =='openshift'">-</td>
            <!-- not open shift -->
            <td v-if="specialShift.ShiftType !='openshift'">-</td>
            <td v-if="specialShift.ShiftType !='openshift'">{{specialShift.Car}}</td>
            <td v-if="specialShift.ShiftType !='openshift'">{{specialShift.CarCoveredBy}}</td>

            <td>{{specialShift.SingInTime}}</td>




            <td class="text-right">
              <v-icon color="success" class="mx-1" @click="editSpecialShift(specialShift)">edit</v-icon>

              <v-icon
                color="red lighten-1"
                class="mx-1"
                @click="deleteSpecialShift(specialShift.id,specialShift.Name)"
              >delete</v-icon>
              <v-icon color="info" class="mx-1" @click="showSpecialShift(specialShift)">description</v-icon>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <v-pagination :total-visible="5" v-model="current_page" :length="last_page" @input="next"></v-pagination>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  beforeMount() {
    this.fetchItems();
  },
  components: {
    NavbarAdmin,
    Drawer
  },
  data() {
    return {
      specialShifts: [],
      current_page: 1,
      last_page: 1,
      noSpecialShiftfound: false,
      searchValue: ""
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    search(searchValue) {
      if (searchValue == "") {
        this.fetchItems();
        this.noSpecialShiftfound = false;
        return;
      }
      let vm = this;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(
          this.BASE_URL() +
            "/api/admin/specialShifts/searchspecialShifts?searchValue=" +
            searchValue
        )
        .then(res => {
          console.log(res.data);
          if (res.data.data.length == 0) {
            console.log("no drii");
            this.noSpecialShiftfound = true;
            this.specialShifts = {};
          } else {
            console.log("specialShift found meta =>");
            console.log(res.data.meta);
            this.noSpecialShiftfound = false;
            this.specialShifts = res.data.data;
          }
          vm.makePagination(res.data.meta, res.data.links);
        })
        .catch(error => {
          console.log("search errors : ");
          console.log(error);
          this.inputErrors = error.response.data.errors;
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    },
    fetchItems(page_url) {
      let vm = this;
      page_url = page_url || this.BASE_URL() + "/api/admin/specialShifts";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(page_url)
        .then(res => {
          this.specialShifts = res.data.data;
          vm.makePagination(res.data.meta, res.data.links);
        })
        .catch(error => {
          console.log(error);
        });
    },

    makePagination(meta, links) {
      this.last_page = meta.last_page;
      this.current_page = meta.current_page;
    },

    next(page) {
      this.fetchItems(
        this.BASE_URL() + "/api/admin/specialShifts?page=" + page
      );
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
              "Bearer " + this.$store.state.token_admin;
            axios
              .delete(this.BASE_URL() + "/api/admin/specialShift/" + id)
              .then(data => {
                console.log(data);
                this.fetchItems();
              })
              .catch(err => console.log(err));
            this.$swal.fire(
              "Deleted!",
              "SpecialShift has been deleted.",
              "success"
            );
          }
        });
    },

    addSpecialShift() {
      this.$router.push({ name: "AdminDashboard_specialShifts_add" });
    },

    showSpecialShift: function(specialShift) {
      this.$router.push({
        name: "AdminDashboard_specialShifts_show",
        params: { id: specialShift.id }
      });
    },

    editSpecialShift(specialShift) {
      this.$router.push({
        name: "AdminDashboard_specialShifts_edit",
        params: { id: specialShift.id }
      });
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
  grid-template-columns: 10fr 4fr;
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
</style>