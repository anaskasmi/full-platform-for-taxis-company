<template>
  <div class="text-center">
    <v-dialog v-model="showDriverLogin" width="450">
      <v-card>
        <v-toolbar color="info" dark flat>
          <v-icon class="mr-5">verified_user</v-icon>

          <v-toolbar-title>Driver Login</v-toolbar-title>

          <div class="flex-grow-1"></div>
        </v-toolbar>

        <v-card-text>
          <v-form ref="form" v-model="valid" class="pt-8">
            <v-text-field
            v-if="!loading"
              v-model="PermitNumber"
              :rules="[PermitNumberRules.required]"
              label="Badge ID"
              required
            ></v-text-field>
   <v-progress-circular
   v-if="loading"
      indeterminate
      color="primary"
    ></v-progress-circular>

            <v-text-field
            v-if="!loading"
              v-model="password"
              :append-icon="showPassword ? 'visibility' : 'visibility_off'"
              :rules="[passwordRules.required, passwordRules.min]"
              :type="showPassword ? 'text' : 'password'"
              name="input-10-1"
              label="Password"
              hint="At least 6 characters"
              counter
              @click:append="showPassword = !showPassword"
            ></v-text-field>

            <v-checkbox label="remeber me" v-model="checkbox" class="mt-5 pa-0"></v-checkbox>
            <v-btn
              :disabled="!valid"
              color="primary"
              class="mb-2"
              outlined
              block
              tile
              @click.prevent="login()"
            >Login</v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      valid: false,
      PermitNumber: "",
      password: "",
      showPassword: false,
      PermitNumberRules: {
        required: v => !!v || "Permit Number is required"
      },
      passwordRules: {
        required: v => !!v || "Password is required",
        min: v => v.length >= 6 || "Min 6 characters"
      },
      checkbox: false
    };
  },
  computed: {
    showDriverLogin: {
      get() {
        return this.$store.state.showDriverLogin;
      },
      set(newval) {
        if (newval === true) {
          this.$store.commit("showDriverLogin_on");
        } else {
          this.$store.commit("showDriverLogin_off");
        }
      }
    }
  },
  methods: {
    showDriverLogin_on: function() {
      this.$store.commit(showDriverLogin_on);
    },
    showDriverLogin_off: function() {
      this.$store.commit(showDriverLogin_off);
    },
    login() {
      this.loading = true;
      this.$store
        .dispatch("retrieveToken", {
          type: "driver",
          PermitNumber: this.PermitNumber,
          password: this.password,
          remember_me: this.checkbox
        })
        .then(res => {
          this.loading = false;
          this.showDriverLogin = false;
          if (!res.data.changePassword) {
            this.$swal(
              "welcome !",
              "you were logged in successfully as a Driver!",
              "success"
            );
            this.$router.push({ name: "DriverDashboard_profile" });
          } else {
            let vm = this;
            this.$router.push({ name: "DriverDashboard_profile" });
            this.$swal(
              "welcome !",
              "you were logged in successfully as a Driver!",
              "success"
            ).then(async function(res) {
              await vm.$swal("", "Please change your password!", "warning");
              await vm.$router.push({
                name: "DriverDashboard_changePassword"
              });
            });
          }
        })
        .catch(err => {
          this.loading = false;
          this.password = null;
          this.$swal(
            "Try again !",
            "Sorry this does not match our records !",
            "warning"
          );
        });
    }
  }
};
</script>
