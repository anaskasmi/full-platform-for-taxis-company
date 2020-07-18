<template>
  <div>
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>
    <!-- back button -->
    <div class="row col-12">
      <v-btn color="grey" text tile class="float-left" :to="mldsRoute">
        <v-icon class="mr-2">keyboard_return</v-icon>mlds
      </v-btn>
    </div>
    <hr />

    <div v-if="validationErrors.length">
      <v-alert
        dense
        outlined
        type="warning"
        border="left"
        v-for="error in validationErrors"
        v-bind:key="error"
      >{{error}}</v-alert>
      <hr />
    </div>

    <!-- form -->
    <form id="form" @submit.prevent="storeMld">
      <v-app-bar dark color="#1F7087" dense flat class="mt-12 mb-5">
        <v-toolbar-title>Mld Information</v-toolbar-title>
      </v-app-bar>

      <!-- image -->

      <v-row align="center" justify="center" class="col-12">
        <div v-if="url" id="preview" class="pr-10">
          <img v-if="url" :src="url" />
        </div>
        <v-row>
          <div>
            <label for="image">
              <input
                
                type="file"
                @change="onFileChange"
                accept="image/png, image/jpeg, image/bmp"
                id="image"
                class="inputfile"
                name="image"
              />
              <div class="upload_btn upload_info border border-info ml-3">
                <v-icon color="blue darken-2">mdi-upload-outline</v-icon>Profile Image
              </div>
            </label>
          </div>
        </v-row>
      </v-row>


      <div class="form-group">
        <v-text-field :rules="rules" name="FirstName" v-model="mld.FirstName" label="First Name"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" name="LastName" v-model="mld.LastName" label="Last Name"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" name="BadgeID" v-model="mld.BadgeID" label="Badge ID"></v-text-field>
      </div>
      <div class="form-group">
        <v-text-field :rules="rules" name="car" v-model="mld.car" label="Car"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" v-model="mld.email" name="email" label="Email"></v-text-field>
      </div>

      <v-select v-model="mld.MLD_STATUS" :items="statuses" label="STATUS"  ></v-select>

      <!-- submit btn -->
      <div class="row col-12 mt-4">
        <v-btn color="success" tile class="float-left" type="submit">submit</v-btn>
        <v-btn
          color="grey lighten-1"
          dark
          tile
          class="float-left mx-4"
          @click="$router.go(-1)"
        >cancel</v-btn>
      </div>
    </form>
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
  data() {
    return {
      inputErrors: [],
      url: null,
      statuses: ["Active", "Inactive"],
      
      mldsRoute: { name: "AdminDashboard_mlds" },
      mld: {
        FirstName: "",
        LastName: "",
        BadgeID: "",
        email: "",
        MLD_STATUS: "Active",
        car: "",
        image:"",
        
      }
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    onFileChange(e) {
      const file = e.target.files[0];
      this.mld.image=file;
      this.url = URL.createObjectURL(file);
    },
    storeMld() {
      var form = document.getElementById("form");
      var formData = new FormData(form);
      formData.append("MLD_STATUS", this.mld.MLD_STATUS=="Active" ? "1" : "0");
      for (var pair of formData.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .post(this.BASE_URL() + "/api/admin/mld", formData)
        .then(response => {
          this.$router.push({
            name: "AdminDashboard_mlds_show",
            params: { id: response.data.data.BadgeID }
          });
        })
        .catch(error => {
          this.inputErrors = error.response.data.errors;
          // window.scrollTo(0,0);
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    }
  },
  computed: {
    validationErrors() {
      let errors = Object.values(this.inputErrors);
      errors = errors.flat();
      return errors;
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
#preview {
  display: flex;
  justify-content: left;
  align-items: left;
}

#preview img {
  max-width: 100%;
  max-height: 100px;
}
.inputfile {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}
.upload_btn {
  border: none;
  background-color: inherit;
  padding: 14px 28px;
  font-size: 16px;
  cursor: pointer;
  display: inline-block;
}

.upload_btn:hover {
  background: #eee;
}
.upload_info {
  color: dodgerblue;
}
</style>