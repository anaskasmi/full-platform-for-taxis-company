<template>
  <div class="wrapper_box">
    <NavbarDriver></NavbarDriver>
    <Drawer></Drawer>

    <!-- header -->
    <div class="mt-8 col-12">
      <div class="title_header text-uppercase col-6 mb-10">Rotations Categories</div>
    </div>
    <hr />

    <div class="sentence">Pick a Category !</div>
    <!-- progress -->
    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
    <!-- table -->
    <div class="table-responsive" v-if="!isLoading">
      <table class="table">
        
        <!-- <thead class="thead-dark">
          <tr>
            <th scope="col" class="text-center text-uppercase">Rotations categories</th>
          </tr>
        </thead> -->
        <tbody>
          <tr v-for="(rotationsCategory,i) in rotationsCategories" :key="i">
            <td @click="openRotationsCategory(rotationsCategory)">
              <v-btn color="info" block outlined tile>{{rotationsCategory.name}}</v-btn>
            </td>
          </tr>
        </tbody>
      </table>
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
  created() {
    this.fetchItems();
  },
  data() {
    return {
      rotationsCategories: {},
      isLoading: true
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    fetchItems() {
      this.isLoading = true;
      let page_url = this.BASE_URL() + "/api/driver/rotationsCategories";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;
      axios
        .get(page_url)
        .then(res => {
          this.rotationsCategories = res.data.data;
          this.isLoading = false;
        })
        .catch(error => {
          console.log(error);
          this.isLoading = false;
          if (


            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            {
              this.$swal("Try again", error.response.data.errors, "warning");
            }
          } else {
            this.$swal("Try again", "Unknown error has occurred ! ", "warning");
          }
        });
    },


    openRotationsCategory(category) {
      this.$router.push({ name: "DriverDashboard_rotations_vehicles" ,params: { category_id:category.id} });
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