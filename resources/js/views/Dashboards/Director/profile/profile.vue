<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div>
                <NavbarDirector></NavbarDirector>
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
                        <v-btn rounded small class="title text-uppercase text-center mb-6 mt-2" color="info">Director</v-btn>
                    </v-col>
                    <v-btn class="title text-uppercase text-center my-4" color="primary" tile outlined>{{email}}</v-btn>
                </div>
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDirector></NavbarDirector>
            <div class="container px-4">

                <div>


                    <div v-if="loading" class="text-center">
                        <v-progress-circular indeterminate color="primary"></v-progress-circular>
                    </div>
                    <div v-if="!loading" class="text-center">
                        <v-avatar size="130">
                            <img class="shadow-sm" :src="image" />
                        </v-avatar>
                        <div class="title text-uppercase text-center my-4">{{name}}</div>
                        <v-col class="text-center pa-0 ma-0" cols="12">
                            <v-btn rounded small class="title text-uppercase text-center mb-6 mt-2" color="info">Director</v-btn>
                        </v-col>
                        <v-btn class="title text-uppercase text-center my-4" color="primary" tile outlined>{{email}}</v-btn>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import NavbarDirector from "@/js/components/navbars/Director.vue";
import Drawer from "@/js/components/drawers/Director.vue";

export default {
  created() {
    this.fetchItems();
  },
  components: {
    NavbarDirector,
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
        "Bearer " + this.$store.state.token_director;

      axios
        .get(this.BASE_URL()+"/api/director/auth/user")
        .then(res => {
          this.loading = false;
          console.log(res);
          this.name = res.data.name;
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
