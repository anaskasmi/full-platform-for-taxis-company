<template>
  <div class="wrapper_box">
    <NavbarDispatcher></NavbarDispatcher>
    <Drawer></Drawer>

    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Drivers</div>
      <v-btn link tile class="ma-2 button_header" outlined color="success " @click="addDriver">
        <v-icon left>mdi-pencil</v-icon>
        <div>New Driver</div>
      </v-btn>
    </div>
    <hr />
    <!-- search start -->
    <div class="row">
      <div class="col-md-12 md-form active-cyan-2 mb-3" style="margin-bottom: -15px !important;">
        <form @submit.prevent="search(searchValue)">
          <div class="input-group mb-4">
            <div class="input-group-prepend">
              <button id="button-addon7" type="submit" class="btn btn-dark">
                <!-- <i class="fa fa-search"></i> -->
                <v-icon dark small>search</v-icon>
              </button>
            </div>
            <input
              type="search"
              name="searchValue"
              v-model="searchValue"
              placeholder="Search by name"
              aria-describedby="button-addon7"
              class="form-control"
            />
          </div>
        </form>
      </div>
    </div>
    <!-- search end -->

    <!-- table -->
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="thead-dark">
          <tr class>
            <th scope="col">Name</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Permit Number</th>
            <th scope="col">License Number</th>
            <th scope="col">License Expiry</th>
            <th scope="col">Permit Expiry</th>
            <th scope="col">Abstract Expiry</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="driver in drivers" v-bind:key="driver.PermitNumber" class>
            <td>{{driver.Name}}</td>
            <td>{{driver.FirstName}}</td>
            <td>{{driver.LastName}}</td>
            <td>{{driver.PermitNumber}}</td>
            <td>{{driver.LicenseNumber}}</td>
            <td>{{driver.LicenseExpiry}}</td>
            <td>{{driver.PermitExpiry}}</td>
            <td>{{driver.AbstractExpiry}}</td>
            <td class="text-right">
              <v-icon color="success" class="mx-1" @click="editDriver(driver)">edit</v-icon>

              <v-icon
                color="red lighten-1"
                class="mx-1"
                @click="deleteDriver(driver.PermitNumber,driver.Name)"
              >delete</v-icon>
              <v-icon color="info" class="mx-1" @click="showDriver(driver)">person</v-icon>
            </td>
          </tr>
        </tbody>
      </table>
      <v-alert
        v-if="noDriverfound"
        border="left"
        tile
        type="warning"
        dense
        dark
        elevation="2"
        class="mb-12"
        align="center"
      >Sorry, No driver Found !</v-alert>
    </div>

    <v-pagination :total-visible="5" v-model="current_page" :length="last_page" @input="next"></v-pagination>
  </div>
</template>
<script>
import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
import Drawer from "@/js/components/drawers/Dispatcher.vue";

export default {
  beforeMount() {
    this.noDriverfound = false;
    this.fetchItems();
  },
  components: {
    NavbarDispatcher,
    Drawer
  },
  data() {
    return {
      drivers: [],
      current_page: 1,
      last_page: 1,
      noDriverfound: false,
      searchValue: ""
    };
  },
  methods: {
    BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
    search(searchValue) {
      if (searchValue == "") {
        this.fetchItems();
        this.noDriverfound = false;
        return;
      }
      let vm = this;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(
          this.BASE_URL()+"/api/dispatcher/drivers/search?searchValue=" +
            searchValue
        )
        .then(res => {
          console.log(res.data);
          if (res.data.data.length == 0) {
            console.log("no drii");
            this.noDriverfound = true;
            this.drivers = {};
          } else {
            console.log("driver found meta =>");
            console.log(res.data.meta);
            this.noDriverfound = false;
            this.drivers = res.data.data;
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
      page_url = page_url || this.BASE_URL()+"/api/dispatcher/drivers";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(page_url)
        .then(res => {
          this.drivers = res.data.data;
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
      this.fetchItems(this.BASE_URL() + "/api/dispatcher/drivers?page=" + page);
    },

    BASE_URL() {
      return this.$store.state.BASE_URL;
    },

    deleteDriver(id, name) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "Driver " + name + " will be deleted !",
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
              .delete(this.BASE_URL()+"/api/dispatcher/driver/" + id)
              .then(data => {
                this.fetchItems();
              })
              .catch(err => console.log(err));
            this.$swal.fire("Deleted!", "Driver has been deleted.", "success");
          }
        });
    },

    addDriver() {
      this.$router.push({ name: "DispatcherDashboard_drivers_add" });
    },

    showDriver: function(driver) {
      this.$router.push({
        name: "DispatcherDashboard_drivers_show",
        params: { id: driver.PermitNumber }
      });
    },

    editDriver(driver) {
      this.$router.push({
        name: "DispatcherDashboard_drivers_edit",
        params: { id: driver.PermitNumber }
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
</style>