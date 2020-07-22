<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div>
                <NavbarDirector></NavbarDirector>
                <Drawer></Drawer>

                <div class="sentence text-center mb-10">Change My Password</div>

                <hr />

                <v-form ref="form" v-model="valid" lazy-validation class="mt-12">
                    <!-- <v-text-field v-model="formData.PermitNumber" label="Bagde ID" required></v-text-field> -->
                    <div class="row col-12 d-flex justify-content-center">
                        <v-text-field
                            :append-icon="oldPassword_show ? 'visibility' : 'visibility_off'"
                            :type="oldPassword_show ? 'text' : 'password'"
                            @click:append="oldPassword_show = !oldPassword_show"
                            class="text-center col-6"
                            v-model="formData.oldPassword"
                            :rules="oldPasswordRules"
                            label="Old Password"
                            required
                        ></v-text-field>
                    </div>

                    <div class="row col-12 d-flex justify-content-center">
                        <v-text-field
                            :append-icon="newPassword_show ? 'visibility' : 'visibility_off'"
                            :type="newPassword_show ? 'text' : 'password'"
                            hint="At least 8 characters"
                            @click:append="newPassword_show = !newPassword_show"
                            class="text-center col-6"
                            v-model="formData.newPassword"
                            :rules="newPasswordRules"
                            label="New Password"
                            required
                        ></v-text-field>
                    </div>

                    <div class="row col-12 d-flex justify-content-center">
                        <v-text-field
                            :append-icon="newPassword_confirmation_show ? 'visibility' : 'visibility_off'"
                            :type="newPassword_confirmation_show ? 'text' : 'password'"
                            @click:append="newPassword_confirmation_show = !newPassword_confirmation_show"
                            class="text-center col-6"
                            v-model="formData.newPassword_confirmation"
                            :rules="newPassword_confirmationRules"
                            label="New Password Confirmation"
                            required
                        ></v-text-field>
                    </div>
                    <div class="row col-12 d-flex justify-content-center mt-10">
                        <v-btn
                            color="primary lighten-1"
                            tile
                            outlined
                            :disabled="!valid"
                            class="col-2"
                            @click="validate"
                        >Submit</v-btn>
                        <v-btn
                            color="grey"
                            tile
                            outlined
                            @click="$router.push({ name: 'DirectorDashboard_profile' })"
                            class="col-2 ml-3"
                        >Cancel</v-btn>
                    </div>
                </v-form>
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

                    <div class="sentenceMobile text-center mb-10">Change My Password</div>

                    <hr />

                    <v-form ref="form" v-model="valid" lazy-validation class="mt-12">
                        <!-- <v-text-field v-model="formData.PermitNumber" label="Bagde ID" required></v-text-field> -->
                        <div class="">
                            <v-text-field
                                :append-icon="oldPassword_show ? 'visibility' : 'visibility_off'"
                                :type="oldPassword_show ? 'text' : 'password'"
                                @click:append="oldPassword_show = !oldPassword_show"
                                class=""
                                v-model="formData.oldPassword"
                                :rules="oldPasswordRules"
                                label="Old Password"
                                required
                            ></v-text-field>
                        </div>

                        <div class="">
                            <v-text-field
                                :append-icon="newPassword_show ? 'visibility' : 'visibility_off'"
                                :type="newPassword_show ? 'text' : 'password'"
                                hint="At least 8 characters"
                                @click:append="newPassword_show = !newPassword_show"
                                class=""
                                v-model="formData.newPassword"
                                :rules="newPasswordRules"
                                label="New Password"
                                required
                            ></v-text-field>
                        </div>

                        <div class="">
                            <v-text-field
                                :append-icon="newPassword_confirmation_show ? 'visibility' : 'visibility_off'"
                                :type="newPassword_confirmation_show ? 'text' : 'password'"
                                @click:append="newPassword_confirmation_show = !newPassword_confirmation_show"
                                class=""
                                v-model="formData.newPassword_confirmation"
                                :rules="newPassword_confirmationRules"
                                label="New Password Confirmation"
                                required
                            ></v-text-field>
                        </div>
                            <v-btn
                                block
                                color="primary lighten-1"
                                tile
                                outlined
                                :disabled="!valid"
                                class="my-4"
                                @click="validate"
                            >Submit</v-btn>
                            <v-btn
                                block
                                color="grey"
                                tile
                                outlined
                                @click="$router.push({ name: 'DirectorDashboard_profile' })"
                                class="mb-3"
                            >Cancel</v-btn>
                    </v-form>
                </div>
            </div>
        </div>

    </div>


</template>

<script>
import NavbarDirector from "@/js/components/navbars/Director.vue";
import Drawer from "@/js/components/drawers/Director.vue";

export default {
  components: {
    NavbarDirector,
    Drawer
  },
  created() {
    this.valid = false;
  },
  data() {
    return {
      formData: {
        PermitNemmber: "",
        oldPassword: "",
        newPassword: "",
        newPassword_confirmation: ""
      },
      //password visibility
      oldPassword_show: false,
      newPassword_show: false,
      newPassword_confirmation_show: false,
      //rules
      oldPasswordRules: [
        v => !!v || "Old password is required !",
        v =>
          this.formData.oldPassword.length > 8 ||
          "Password length must be more than 8 characters"
      ],
      newPasswordRules: [
        v => !!v || "New password is required !",
        v =>
          (this.formData.newPassword && this.formData.newPassword.length > 8) ||
          "Password length must be more than 8 characters"
      ],
      newPassword_confirmationRules: [
        v => !!v || "Password confirmation is required !",
        v =>
          this.formData.newPassword_confirmation == this.formData.newPassword ||
          "Password Confirmation Dont match !"
      ],

      valid: false,
      lazy: true
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },

    validate() {
      if (this.$refs.form.validate()) {
        this.snackbar = true;
        //change password
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_director;

        axios
          .post(
            this.BASE_URL() + "/api/director/auth/changePassword",
            this.formData
          )
          .then(res => {
            this.$swal.fire("", "Password changed successfully!", "success");
            this.$router.push({ name: "DirectorDashboard_profile" });
          })
          .catch(err => {
            this.$swal.fire("", err.response.data, "warning");
          });
      } else {
        this.$swal.fire(
          "invalide inputs",
          "Please try again with correct inputs",
          "warning"
        );
      }
    }
  }
};
</script>
<style scoped>
.sentence {
  margin-top: 2em;
  font-family: "Quicksand", sans-serif;
  justify-self: center;
  font-size: 2.5em;
}

.sentenceMobile {
    margin-top: 1em;
    font-family: "Quicksand", sans-serif;
    justify-self: center;
    font-size: 2em;
}
</style>
