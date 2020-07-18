<template>
  <div>
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>

    <v-btn color="grey" text tile class="float-left" :to="mldsRoute">
      <v-icon class="mr-2">keyboard_return</v-icon>mlds
    </v-btn>
    <v-btn
      color="success"
      outlined
      class="float-right mx-4"
      :to="{name : 'AdminDashboard_mlds_edit',params:{id:id}}"
    >
      <v-icon class="mr-2">edit</v-icon>edit
    </v-btn>
    <v-btn
      color="red "
      outlined
      class="float-right"
      @click="deleteMld(mld.BadgeID,mld.Name)"
    >
      <v-icon class="mr-2">delete</v-icon>delete
    </v-btn>

    <div class="table-responsive">
      <div class="row col-12">
        <div class="col-5"></div>
        <div class="col-2" v-if="profileImage !== false">
          <a :href="profileImage">
            <img :src="profileImage" alt class="img-fluid img-thumbnail rounded" />
          </a>
        </div>
        <div class="col-5"></div>

        <div class="col-5"></div>
        <div class="col-2" v-if="profileImage === false">
          <a :href="BASE_URL()+'/storage/uploads/IMAGES/man.png'">
            <img :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'" alt class="img-fluid rounded" />
          </a>
        </div>
        <div class="col-5"></div>
      </div>
      <br />
      <table class="table table-bordered table-hover">
        <tbody>
          <tr>
            <td style="width: 30%;">Name</td>
            <td>{{mld.Name}}</td>
          </tr>
          <tr>
            <td>First Name</td>
            <td>{{mld.FirstName}}</td>
          </tr>
          <tr>
            <td>Last Name</td>
            <td>{{mld.LastName}}</td>
          </tr>

          <tr>
            <td>Badge ID</td>
            <td>{{mld.BadgeID}}</td>
          </tr>


          <tr>
            <td>Car</td>
            <td>{{mld.car}}</td>
          </tr>
          


          <tr>
            <td>created at</td>
            <td>{{mld.created_at}}</td>
          </tr>

          
          <tr>
            <td>Status</td>
            <td v-if="mld.MLD_STATUS==1" style="color:green">Active</td>
            <td v-if="mld.MLD_STATUS==0" style="color:red">Inactive</td>
          </tr>

        </tbody>
      </table>
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
    this.id = this.$route.params.id;
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.state.token_admin;
    axios
      .get(this.BASE_URL() + "/api/admin/mld/" + this.id)
      .then(res => {
        this.mld = res.data.data;
      })
      .catch(error => {
        console.log(error);
      });
  },
  beforeDestroy() {
    this.$store.commit("showNavbarAdmin_off");
  },
  data() {
    return {
      mld: {},
      id: "",
      mldsRoute: { name: "AdminDashboard_mlds" }
    };
  },
  computed: {
    profileImage() {
      if (this.mld.image)
        return this.BASE_URL() + "/storage/" + this.mld.image;
      else return false;
    },
   
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    deleteMld(id, name) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "Mld " + name + " will be deleted !",
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
              .delete(this.BASE_URL() + "/api/admin/mld/" + id)
              .then(data => {
                this.$router.push({ name: "AdminDashboard_mlds" });
                this.$swal.fire(
                  "Deleted!",
                  "Your file has been deleted.",
                  "success"
                );
              })
              .catch(err => {
                this.$swal.fire({
                  title: "error",
                  text: "we couldnt delete this mld",
                  type: "warning"
                });
              });
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