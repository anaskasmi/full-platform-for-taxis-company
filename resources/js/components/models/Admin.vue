<template>
  <div class="text-center">
    <v-dialog v-model="showAdminLogin" width="450">
      <v-card>
        <v-toolbar color="info" dark flat>
          <v-icon class="mr-5">verified_user</v-icon>

          <v-toolbar-title>Admin Login</v-toolbar-title>

          <div class="flex-grow-1"></div>
        </v-toolbar>

        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation class="pt-8">
            <v-text-field
              v-if="!loading"
              v-model="email"
              :rules="emailRules"
              label="E-mail"
              required
            ></v-text-field>
            <v-progress-circular v-if="loading" indeterminate color="primary"></v-progress-circular>
            <v-text-field
              v-if="!loading"
              v-model="password"
              :rules="[passwordRules.required, passwordRules.min]"
              :append-icon="showPassword ? 'visibility' : 'visibility_off'"
              :type="showPassword ? 'text' : 'password'"
              name="input-10-1"
              label="Password"
              hint="At least 8 characters"
              counter
              @click:append="showPassword = !showPassword"
            ></v-text-field>

            <v-checkbox label="remeber me" v-model="checkbox" class="mt-5 pa-0"></v-checkbox>
            <v-btn
              :disabled="!valid"
              color="primary"
              class="mb-2"
              @click="login"
              outlined
              block
              tile
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
      name: "",
      email: "",
      password: "",
      showPassword: false,
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid"
      ],
      passwordRules: {
        required: v => !!v || "Password is required",
        min: v => v.length >= 8 || "Min 8 characters"
      },
      checkbox: false
    };
  },
  computed: {
    showAdminLogin: {
      get() {
        return this.$store.state.showAdminLogin;
      },
      set(newval) {
        if (newval === true) {
          this.$store.commit("showAdminLogin_on");
        } else {
          this.$store.commit("showAdminLogin_off");
        }
      }
    }
  },
  methods: {
    showAdminLogin_on: function() {
      this.$store.commit(showAdminLogin_on);
    },
    showAdminLogin_off: function() {
      this.$store.commit(showAdminLogin_off);
    },

    login() {
      this.loading = true;

      this.$store
        .dispatch("retrieveToken", {
          type: "admin",
          email: this.email,
          password: this.password,
          remember_me: this.checkbox
        })
        .then(res => {
          this.loading = false;
          this.showAdminLogin = false;
          this.$swal(
            "welcome !",
            "you were logged in successfully as an ADMIN !",
            "success"
          );
          this.$router.push({ name: "AdminDashboard_profile" });
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