<template>
  <div>
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>

    <!-- back button -->
    <div class="row col-12">
      <v-btn color="grey" text tile class="float-left" :to="driversRoute">
        <v-icon class="mr-2">keyboard_return</v-icon>drivers
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
    <form id="form" @submit.prevent="editDriver">
      <div class="form-group">
        <v-alert
          border="left"
          tile
          dense
          color="#1F7087"
          dark
          elevation="2"
          class="mt-12"
          align="center"
        >Driver Informations</v-alert>
        <v-text-field :rules="rules" name="FirstName" v-model="driver.FirstName" label="First Name"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" name="LastName" v-model="driver.LastName" label="Last Name"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field
          :rules="rules"
          name="PermitNumber"
          v-model="driver.PermitNumber"
          label="permit Number"
        ></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field
          :rules="rules"
          name="LicenseNumber"
          v-model="driver.LicenseNumber"
          label="License Number"
        ></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" v-model="driver.HomePhone" name="HomePhone" label="Home Phone"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" v-model="driver.TAXI_HOST" name="TAXI_HOST" label="TAXI HOST"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" v-model="driver.ADDRESS" name="ADDRESS" label="ADDRESS"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field :rules="rules" v-model="driver.email" name="email" label="Email"></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field
          :rules="rules"
          name="DRIVER_STATUS"
          v-model="driver.DRIVER_STATUS"
          label="DRIVER STATUS"
        ></v-text-field>
      </div>

      <div class="form-group">
        <v-text-field
          :rules="rules"
          name="LICENSE_CLASS"
          v-model="driver.LICENSE_CLASS"
          label="LICENSE CLASS"
        ></v-text-field>
      </div>

      <!-- dates -->
      <hr class="mt-12" />
      <v-alert
        border="left"
        tile
        dense
        color="#1F7087"
        dark
        elevation="2"
        class="mt-12"
        align="center"
      >Driver Dates</v-alert>

      <!-- START DATE -->
      <v-col cols="12" class="pa-0">
        <v-dialog
          ref="START_DATE"
          v-model="modal_START_DATE"
          :return-value.sync="driver.START_DATE"
          persistent
          width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="driver.START_DATE"
              label="START DATE"
              name="START_DATE"
              prepend-icon="event"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="driver.START_DATE" scrollable>
            <div class="flex-grow-1"></div>
            <v-btn text color="primary" @click="modal_START_DATE = false">Cancel</v-btn>
            <v-btn text color="primary" @click="$refs.START_DATE.save(driver.START_DATE)">OK</v-btn>
          </v-date-picker>
        </v-dialog>
      </v-col>

      <!-- END DATE -->
      <v-col cols="12" class="pa-0">
        <v-dialog
          ref="END_DATE"
          v-model="modal_END_DATE"
          :return-value.sync="driver.END_DATE"
          persistent
          width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="driver.END_DATE"
              label="END DATE"
              name="END_DATE"
              prepend-icon="event"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="driver.END_DATE" scrollable>
            <div class="flex-grow-1"></div>
            <v-btn text color="primary" @click="modal_END_DATE = false">Cancel</v-btn>
            <v-btn text color="primary" @click="$refs.END_DATE.save(driver.END_DATE)">OK</v-btn>
          </v-date-picker>
        </v-dialog>
      </v-col>

      <!-- permit expiry -->
      <v-col cols="12" class="pa-0">
        <v-dialog
          ref="PermitExpiry"
          v-model="modal_PermitExpiry"
          :return-value.sync="driver.PermitExpiry"
          persistent
          width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="driver.PermitExpiry"
              label="Permit Expiry"
              name="PermitExpiry"
              prepend-icon="event"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="driver.PermitExpiry" scrollable>
            <div class="flex-grow-1"></div>
            <v-btn text color="primary" @click="modal_PermitExpiry = false">Cancel</v-btn>
            <v-btn text color="primary" @click="$refs.PermitExpiry.save(driver.PermitExpiry)">OK</v-btn>
          </v-date-picker>
        </v-dialog>
      </v-col>

      <!-- License Expiry -->
      <v-col cols="12" class="pa-0">
        <v-dialog
          ref="LicenseExpiry"
          v-model="modal_LicenseExpiry"
          :return-value.sync="driver.LicenseExpiry"
          persistent
          width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="driver.LicenseExpiry"
              label="License Expiry"
              name="LicenseExpiry"
              prepend-icon="event"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="driver.LicenseExpiry" scrollable>
            <div class="flex-grow-1"></div>
            <v-btn text color="primary" @click="modal_LicenseExpiry = false">Cancel</v-btn>
            <v-btn text color="primary" @click="$refs.LicenseExpiry.save(driver.LicenseExpiry)">OK</v-btn>
          </v-date-picker>
        </v-dialog>
      </v-col>

      <!-- Abstract Expiry -->
      <v-col cols="12" class="pa-0">
        <v-dialog
          ref="AbstractExpiry"
          v-model="modal_AbstractExpiry"
          :return-value.sync="driver.AbstractExpiry"
          persistent
          width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="driver.AbstractExpiry"
              label="Abstract Expiry"
              name="AbstractExpiry"
              prepend-icon="event"
              readonly
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker v-model="driver.AbstractExpiry" scrollable>
            <div class="flex-grow-1"></div>
            <v-btn text color="primary" @click="modal_AbstractExpiry = false">Cancel</v-btn>
            <v-btn text color="primary" @click="$refs.AbstractExpiry.save(driver.AbstractExpiry)">OK</v-btn>
          </v-date-picker>
        </v-dialog>
      </v-col>

      <!-- <v-app-bar  color="#eee " dense  class="mt-12 mb-5 ">
                <v-toolbar-title class="text-center" >Driver Files</v-toolbar-title>
      </v-app-bar>-->

      <!-- files -->
      <hr class="mt-12" />
      <v-alert
        border="left"
        tile
        dense
        color="#1F7087"
        dark
        elevation="2"
        class="mt-12"
        align="center"
      >Driver Files</v-alert>

      <div class="form-group">
        <v-card flat outlined max-width="100%" class="mx-auto">
          <v-app-bar dark color="blue lighten-3" dense flat>
            <v-toolbar-title dense>Driver image</v-toolbar-title>
          </v-app-bar>

          <div class="row col-12">
            <v-card-text>
              <img
                v-if="!imageUrl"
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="profileImage"
                @click="openImage(profileImage)"
              />
              <img
                v-if="imageUrl"
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="imageUrl"
              />
              <v-file-input
                @change="onFileChange"
                v-model="driver.image"
                accept="image/png, image/jpeg, image/bmp"
                placeholder="New Driver image"
                prepend-icon="mdi-camera"
                name="image"
                class
              ></v-file-input>
            </v-card-text>
          </div>
        </v-card>
      </div>

      <div class="form-group">
        <v-card flat outlined max-width="100%" class="mx-auto">
          <v-app-bar dark color="blue lighten-3" dense flat>
            <v-toolbar-title>License file</v-toolbar-title>
          </v-app-bar>

          <div class="row col-12">
            <v-card-text>
              <img
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="LICENSE_PATH"
                @click="openImage(LICENSE_PATH)"
              />
              <v-file-input
                accept="image/png, image/jpeg, image/bmp"
                placeholder="New License file"
                prepend-icon="mdi-camera"
                name="LICENSE_PATH"
                class
              ></v-file-input>
            </v-card-text>
          </div>
        </v-card>
      </div>

      <div class="form-group">
        <v-card flat outlined max-width="100%" class="mx-auto">
          <v-app-bar dark color="blue lighten-3" dense flat>
            <v-toolbar-title>SP file</v-toolbar-title>
          </v-app-bar>

          <div class="row col-12">
            <v-card-text>
              <img
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="SP_FILE_PATH"
                @click="openImage(SP_FILE_PATH)"
              />
              <v-file-input
                accept="image/png, image/jpeg, image/bmp"
                placeholder="New SP file"
                prepend-icon="mdi-camera"
                name="SP_FILE_PATH"
                class
              ></v-file-input>
            </v-card-text>
          </div>
        </v-card>
      </div>

      <div class="form-group">
        <v-card flat outlined max-width="100%" class="mx-auto">
          <v-app-bar dark color="blue lighten-3" dense flat>
            <v-toolbar-title>PERMIT file</v-toolbar-title>
          </v-app-bar>

          <div class="row col-12">
            <v-card-text>
              <img
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="PERMIT_PATH"
                @click="openImage(PERMIT_PATH)"
              />
              <v-file-input
                accept="image/png, image/jpeg, image/bmp"
                placeholder="New PERMIT file"
                prepend-icon="mdi-camera"
                name="PERMIT_PATH"
                class
              ></v-file-input>
            </v-card-text>
          </div>
        </v-card>
      </div>

      <div class="form-group">
        <v-card flat outlined max-width="100%" class="mx-auto">
          <v-app-bar dark color="blue lighten-3" dense flat>
            <v-toolbar-title>TAXIHOST file</v-toolbar-title>
          </v-app-bar>

          <div class="row col-12">
            <v-card-text>
              <img
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="TAXIHOST_PATH"
                @click="openImage(TAXIHOST_PATH)"
              />
              <v-file-input
                accept="image/png, image/jpeg, image/bmp"
                placeholder="New TAXIHOST file"
                prepend-icon="mdi-camera"
                name="TAXIHOST_PATH"
                class
              ></v-file-input>
            </v-card-text>
          </div>
        </v-card>
      </div>

      <div class="form-group">
        <v-card flat outlined max-width="100%" class="mx-auto">
          <v-app-bar dark color="blue lighten-3" dense flat>
            <v-toolbar-title>ABSTRACT file</v-toolbar-title>
          </v-app-bar>

          <div class="row col-12">
            <v-card-text>
              <img
                style="max-width:20%;cursor:pointer;"
                class="rounded float-left mr-10"
                :src="ABSTRACT_PATH"
                @click="openImage(ABSTRACT_PATH)"
              />
              <v-file-input
                accept="image/png, image/jpeg, image/bmp"
                placeholder="New ABSTRACT file"
                prepend-icon="mdi-camera"
                name="ABSTRACT_PATH"
                class
              ></v-file-input>
            </v-card-text>
          </div>
        </v-card>
      </div>

      <hr />
      <!-- assigne new password -->
      <v-btn class="pl-0" color="info" text @click="show_password_assigne = !show_password_assigne">
        <!-- icon -->
        <v-icon>arrow_drop_down</v-icon>
        <!-- text -->
        Assigne Password
      </v-btn>

      <!-- hint -->
      <v-chip color="warning" outlined class="text-muted text-small ma-2" style="font-size:0.8em;">
        <v-icon small left>warning</v-icon>If no password assigned, 'taxi-office' will be the password by default
      </v-chip>
      <!-- @if ($show_password_assigne) -->
      <transition name="component-fade" mode="out-in">
        <div v-if="show_password_assigne">
          <v-text-field
            v-model="driver.password"
            :append-icon="showPassword ? 'visibility' : 'visibility_off'"
            :type="showPassword ? 'text' : 'password'"
            name="password"
            label="Password"
            hint="At least 8 characters"
            counter
            @click:append="showPassword = !showPassword"
          ></v-text-field>

          <v-text-field
            :type="showPassword ? 'text' : 'password'"
            name="password_confirmation"
            label="Confirm Password"
            v-model="driver.password_confirmation"
          ></v-text-field>
        </div>
      </transition>
      <!-- @else
         
      @endif-->
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
      <input type="hidden" name="_method" value="patch" />
    </form>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  created: function() {
    this.id = this.$route.params.id;
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.state.token_admin;
    axios
      .get(this.BASE_URL() + "/api/admin/driver/" + this.id)
      .then(res => {
        this.driver = res.data.data;
        this.PermitExpiry = res.data.data.PermitExpiry;
        this.LicenseExpiry = res.data.data.LicenseExpiry;
        this.modal_AbstractExpiry = res.data.data.modal_AbstractExpiry;
        this.AbstractExpiry = res.data.data.AbstractExpiry;
        this.START_DATE = res.data.data.START_DATE;
        this.END_DATE = res.data.data.END_DATE;
      })
      .catch(error => {
        console.log(error);
      });
  },
  components: {
    NavbarAdmin,
    Drawer
  },
  data() {
    return {
      inputErrors: [],
      // PermitExpiry: new Date().toISOString().substr(0, 10),
      modal_PermitExpiry: false,
      // LicenseExpiry: new Date().toISOString().substr(0, 10),
      modal_LicenseExpiry: false,
      // AbstractExpiry: new Date().toISOString().substr(0, 10),
      modal_AbstractExpiry: false,
      // START_DATE: new Date().toISOString().substr(0, 10),
      modal_START_DATE: false,
      // END_DATE: new Date().toISOString().substr(0, 10),
      modal_END_DATE: false,
      show_password_assigne: false,
      showPassword: false,
      driversRoute: { name: "AdminDashboard_drivers" },
      driver: {},
      imageUrl:null,
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    onFileChange(e) {
      const file = e.target.files[0];
      this.imageUrl = URL.createObjectURL(file);
    },
    openImage(url) {
      console.log("clicked");
      var win = window.open(url, "_blank");
      win.focus();
    },
    editDriver() {
      var form = document.getElementById("form");
      var formData = new FormData(form);
      console.log("formdata ====> ");
      for (var pair of formData.entries()) {
        console.log(pair[0] + ", " + pair[1]);
      }
      console.log(formData);
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .post(
          this.BASE_URL() + "/api/admin/driver/" + this.driver.PermitNumber,
          formData
        )
        .then(response => {
          this.$router.push({
            name: "AdminDashboard_drivers_show",
            params: { id: this.driver.PermitNumber }
          });
        })
        .catch(error => {
          console.log(error.response.data.errors);
          this.inputErrors = error.response.data.errors;
          $("html, body").animate({ scrollTop: 0 }, "200");
        });
    }
  },
  computed: {
    validationErrors() {
      let errors = Object.values(this.inputErrors);
      errors = errors.flat();
      return errors;
    },
    profileImage() {
      if (this.driver.image)
        return this.BASE_URL() + "/storage/" + this.driver.image;
      else return false;
    },
    LICENSE_PATH() {
      if (this.driver.LICENSE_PATH)
        return this.BASE_URL() + "/storage/" + this.driver.LICENSE_PATH;
      else return false;
    },
    PERMIT_PATH() {
      if (this.driver.PERMIT_PATH)
        return this.BASE_URL() + "/storage/" + this.driver.PERMIT_PATH;
      else return false;
    },
    TAXIHOST_PATH() {
      if (this.driver.TAXIHOST_PATH)
        return this.BASE_URL() + "/storage/" + this.driver.TAXIHOST_PATH;
      else return false;
    },
    ABSTRACT_PATH() {
      if (this.driver.ABSTRACT_PATH)
        return this.BASE_URL() + "/storage/" + this.driver.ABSTRACT_PATH;
      else return false;
    },
    SP_FILE_PATH() {
      if (this.driver.SP_FILE_PATH)
        return this.BASE_URL() + "/storage/" + this.driver.SP_FILE_PATH;
      else return false;
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