<template>
  <div >
    <NavbarDriver  ></NavbarDriver>
    <Drawer></Drawer>

    <div v-if="loading" class="text-center">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </div>
    <div v-if="!loading" class="text-center">
      <v-avatar size="130">
        <img class="shadow-sm" :src="image" />
      </v-avatar>
      <div class="title text-uppercase text-center my-4">{{name}}</div>
      <v-col class="text-center pa-0 ma-0" cols="12">
        <v-btn rounded small class="title text-uppercase text-center mb-6 mt-2" color="info">Driver</v-btn>
      </v-col>
      <v-btn class="title text-uppercase text-center my-4" color="primary" tile outlined>{{email}}</v-btn>
    </div>
  </div>
</template>

<script>
import NavbarDriver from "@/js/components/navbars/Driver.vue";
import Drawer from "@/js/components/drawers/Driver.vue";

export default {
  created() {
    this.fetchItems();
  },
  components: {
    NavbarDriver,
    Drawer
  },
  data() {
    return {
      image: "",
      email: "",
      name: "",
      loading: false
    };
  },
  methods: {
      BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
    fetchItems() {
      this.loading = true;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_driver;

      axios
        .get(this.BASE_URL()+"/api/driver/auth/user")
        .then(res => {
          this.loading = false;
          console.log(res);
          this.name = res.data.FirstName + " " + res.data.LastName;
          if (res.data.image !== "" && res.data.image !== null) {
            this.image = this.BASE_URL()+"/storage/" + res.data.image;
          } else {
            this.image = this.BASE_URL()+"/storage/uploads/IMAGES/man.png";
          }
          this.email = res.data.email;
        })
        .catch(err => {
          this.loading = false;
          console.log(err);
        });
    }
  }
};
</script>
<style scoped>

</style>
