<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div>
                <NavbarDispatcher></NavbarDispatcher>
                <Drawer></Drawer>
                <!-- page loading start -->
                <div class="text-center">
                    <v-dialog v-model="pageIsLoading" hide-overlay persistent width="500">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Please wait !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
                <!-- page loading end -->
                <!-- back button -->
                <div class="row col-12">
                    <v-btn color="grey" text tile class="float-left" :to="authorizationFilesRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>Authorization Files
                    </v-btn>
                </div>
                <hr />

                <div>
                    <form id="form" v-on:submit.prevent>
                        <v-app-bar dark color="#1F7087" dense class="mt-12 mb-10 align-center">
                            <v-toolbar-title>Document Information</v-toolbar-title>
                        </v-app-bar>
                        <!-- first name  -->
                        <div class="form-group">
                            <v-text-field
                                label="First name"
                                name="first_name"
                                type="text"
                                v-model="authorizationFile.first_name"
                            ></v-text-field>
                        </div>
                        <!-- last name  -->
                        <div class="form-group">
                            <v-text-field
                                label="Last name"
                                name="last_name"
                                type="text"
                                v-model="authorizationFile.last_name"
                            ></v-text-field>
                        </div>
                        <!-- Account Number  -->
                        <div class="form-group">
                            <v-text-field
                                label="Account Number"
                                name="account_number"
                                type="text"
                                v-model="authorizationFile.account_number"
                            ></v-text-field>
                        </div>
                        <!-- Account Type  -->
                        <v-select
                            label="Account Type"
                            name="account_type"
                            ref="edit_envelopes_ref"
                            :items="accountTypesNames"
                            v-model="authorizationFile.account_type"
                        ></v-select>
                        <!-- PO Number  -->
                        <div class="form-group">
                            <v-text-field
                                label="PO Number"
                                name="po_number"
                                type="text"
                                v-model="authorizationFile.po_number"
                            ></v-text-field>
                        </div>
                        <!-- Status  -->

                        <v-select
                            label="Status"
                            name="status"
                            ref="edit_envelopes_ref"
                            :items="statuses"
                            v-model="authorizationFile.status"
                        ></v-select>

                        <!-- Expiry Date -->
                        <v-menu
                            v-model="expiry_date_modal"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{  }">
                                <v-text-field
                                    readonly
                                    autocomplete="off"
                                    v-model="authorizationFile.expiry_date"
                                    label="Expiry Date (yyyy-mm-dd)"
                                    prepend-icon="event"
                                    name="expiry_date"
                                    @click="expiry_date_modal=true;"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="authorizationFile.expiry_date"
                                @input="expiry_date_modal = false;"
                            ></v-date-picker>
                        </v-menu>
                        <!-- Comment  -->
                        <div class="form-group">
                            <v-textarea
                                class="my-5"
                                outlined
                                shaped
                                label="Modification Comment"
                                name="comment"
                                type="textarea"
                                v-model="authorizationFile.comment"
                            ></v-textarea>
                        </div>
                        <!-- input file -->
                        <v-row align="center" justify="center" class="col-12">
                            <v-row>
                                <div>
                                    <label for="image">
                                        <input
                                            type="file"
                                            @change="onFileChange"
                                            accept="image/png, image/jpeg, image/bmp, application/pdf"
                                            id="image"
                                            class="inputfile"
                                            name="file_path"
                                        />
                                        <div
                                            v-if="document_url && fileExtention=='pdf'"
                                            class="upload_btn upload_info border border-info ml-3"
                                        >
                                            <v-icon color="blue darken-2">mdi-upload-outline</v-icon>
                                            {{filename}}
                                        </div>

                                        <div v-else class="upload_btn upload_info border border-info ml-3">
                                            <v-icon color="blue darken-2">mdi-upload-outline</v-icon>UPLOAD AUTHORIZATION FILE
                                        </div>
                                    </label>
                                </div>
                            </v-row>
                        </v-row>
                        <!-- Document image  -->
                        <div v-if=" fileExtention!='pdf'">
                            <v-app-bar dark color="#1F7087" dense class="mt-12 mb-10 align-center">
                                <v-toolbar-title>Document File</v-toolbar-title>
                            </v-app-bar>

                            <div id="preview" class="col-12 ma-auto align-center">
                                <img
                                    v-if="!document_url"
                                    :src="BASE_URL()+'/storage/'+ authorizationFile.file_path"
                                    class="shadow-lg p-3 mb-5 bg-white rounded"
                                />
                                <img
                                    v-if="document_url"
                                    :src="document_url"
                                    class="shadow-lg p-3 mb-5 bg-white rounded"
                                />
                            </div>
                        </div>
                        <!-- Document pdf  -->
                        <div v-if=" fileExtention=='pdf'">
                            <v-app-bar dark color="#1F7087" dense class="mt-12 mb-10 align-center">
                                <v-toolbar-title>Document File</v-toolbar-title>
                            </v-app-bar>
                            <div id="preview" class="col-12 ma-auto align-center">
                                <embed v-if="document_url" :src="document_url" width="800px" height="800px" />
                                <embed
                                    v-if="!document_url"
                                    :src="BASE_URL()+'/storage/'+ authorizationFile.file_path"
                                    width="800px"
                                    height="800px"
                                />
                            </div>
                        </div>
                        <!-- submit btn -->
                        <div class="row col-12 mt-4" v-if="!pageIsLoading">
                            <v-btn color="primary" tile class="float-left" @click="updateAuthorizationFile()">submit</v-btn>
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
            </div>
        </div>
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <NavbarDispatcher></NavbarDispatcher>
            <div class="container px-4">

                <div>

                    <!-- page loading start -->
                    <div class="text-center">
                        <v-dialog v-model="pageIsLoading" hide-overlay persistent width="500">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Please wait !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                    <!-- page loading end -->
                    <!-- back button -->
                    <div class="">
                        <v-btn color="grey" text tile class="" block :to="authorizationFilesRoute">
                            <v-icon class="mr-2">keyboard_return</v-icon>Authorization Files
                        </v-btn>
                    </div>
                    <hr />

                    <div>
                        <form id="form" v-on:submit.prevent>
                            <v-app-bar dark color="#1F7087" dense class="mt-12 mb-10 align-center">
                                <v-toolbar-title>Document Information</v-toolbar-title>
                            </v-app-bar>
                            <!-- first name  -->
                            <div class="form-group">
                                <v-text-field
                                    label="First name"
                                    name="first_name"
                                    type="text"
                                    v-model="authorizationFile.first_name"
                                ></v-text-field>
                            </div>
                            <!-- last name  -->
                            <div class="form-group">
                                <v-text-field
                                    label="Last name"
                                    name="last_name"
                                    type="text"
                                    v-model="authorizationFile.last_name"
                                ></v-text-field>
                            </div>
                            <!-- Account Number  -->
                            <div class="form-group">
                                <v-text-field
                                    label="Account Number"
                                    name="account_number"
                                    type="text"
                                    v-model="authorizationFile.account_number"
                                ></v-text-field>
                            </div>
                            <!-- Account Type  -->
                            <v-select
                                label="Account Type"
                                name="account_type"
                                ref="edit_envelopes_ref"
                                :items="accountTypesNames"
                                v-model="authorizationFile.account_type"
                            ></v-select>
                            <!-- PO Number  -->
                            <div class="form-group">
                                <v-text-field
                                    label="PO Number"
                                    name="po_number"
                                    type="text"
                                    v-model="authorizationFile.po_number"
                                ></v-text-field>
                            </div>
                            <!-- Status  -->

                            <v-select
                                label="Status"
                                name="status"
                                ref="edit_envelopes_ref"
                                :items="statuses"
                                v-model="authorizationFile.status"
                            ></v-select>

                            <!-- Expiry Date -->
                            <v-menu
                                v-model="expiry_date_modal"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{  }">
                                    <v-text-field
                                        readonly
                                        autocomplete="off"
                                        v-model="authorizationFile.expiry_date"
                                        label="Expiry Date (yyyy-mm-dd)"
                                        prepend-icon="event"
                                        name="expiry_date"
                                        @click="expiry_date_modal=true;"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="authorizationFile.expiry_date"
                                    @input="expiry_date_modal = false;"
                                ></v-date-picker>
                            </v-menu>
                            <!-- Comment  -->
                            <div class="form-group">
                                <v-textarea
                                    class="my-5"
                                    outlined
                                    shaped
                                    label="Modification Comment"
                                    name="comment"
                                    type="textarea"
                                    v-model="authorizationFile.comment"
                                ></v-textarea>
                            </div>
                            <!-- input file -->
                            <v-row >
                                <v-row class="row justify-center">
                                    <div>
                                        <label for="image">
                                            <input
                                                type="file"
                                                @change="onFileChange"
                                                accept="image/png, image/jpeg, image/bmp, application/pdf"
                                                id="image"
                                                class="inputfile"
                                                name="file_path"
                                            />
                                            <div
                                                v-if="document_url && fileExtention=='pdf'"
                                                class="upload_btn upload_info border border-info"
                                            >
                                                <v-icon color="blue darken-2">mdi-upload-outline</v-icon>
                                                {{filename}}
                                            </div>

                                            <div v-else class="upload_btn upload_info border border-info ">
                                                <v-icon color="blue darken-2">mdi-upload-outline</v-icon>UPLOAD AUTHORIZATION FILE
                                            </div>
                                        </label>
                                    </div>
                                </v-row>
                            </v-row>
                            <!-- Document image  -->
                            <div v-if=" fileExtention!='pdf'">
                                <v-app-bar dark color="#1F7087" dense class="mt-12 mb-10 align-center">
                                    <v-toolbar-title>Document File</v-toolbar-title>
                                </v-app-bar>

                                <div id="preview" class="col-12 ma-auto align-center">
                                    <img
                                        v-if="!document_url"
                                        :src="BASE_URL()+'/storage/'+ authorizationFile.file_path"
                                        class="shadow-lg p-3 mb-5 bg-white rounded"
                                    />
                                    <img
                                        v-if="document_url"
                                        :src="document_url"
                                        class="shadow-lg p-3 mb-5 bg-white rounded"
                                    />
                                </div>
                            </div>
                            <!-- Document pdf  -->
                            <div v-if=" fileExtention=='pdf'">
                                <v-app-bar dark color="#1F7087" dense class="mt-12 mb-10 align-center ">
                                    <v-toolbar-title>Document File</v-toolbar-title>
                                </v-app-bar>
                                <div id="preview" class="col-12 ma-auto align-center">
                                    <embed v-if="document_url" :src="document_url" width="800px" height="800px" />
                                    <embed
                                        v-if="!document_url"
                                        :src="BASE_URL()+'/storage/'+ authorizationFile.file_path"
                                        width="800px"
                                        height="800px"
                                    />
                                </div>
                            </div>
                            <!-- submit btn -->
                            <div  v-if="!pageIsLoading" class="my-4">
                                <v-btn color="primary" block tile class="mb-2" @click="updateAuthorizationFile()">submit</v-btn>
                                <v-btn
                                    color="grey lighten-1"
                                    dark
                                    block
                                    tile
                                    class=""
                                    @click="$router.go(-1)"
                                >cancel</v-btn>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


</template>
<script>
import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
import Drawer from "@/js/components/drawers/Dispatcher.vue";

export default {
  components: {
    NavbarDispatcher,
    Drawer
  },
  created() {
    this.fetchItems();
  },
  computed() {

  },
  data() {
    return {
      inputErrors: [],
      accountTypes: null,
      accountTypesNames: [],
      authorizationFilesRoute: {
        name: "DispatcherDashboard_authorizationFiles"
      },
      expiry_date_modal: false,
      authorizationFile: {},
      document_url: null,
      statuses: ["New", "Renewed"],
      pageIsLoading: false,
      fileExtention: "",
      filename: ""
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    onFileChange(e) {
      const file = e.target.files[0];
      this.filename = file.name;
      this.fileExtention = file.name.split(".").pop();
      this.document_url = URL.createObjectURL(file);
    },
    fetchItems() {
      this.id = this.$route.params.id;
      let page_url =
        this.BASE_URL() + "/api/dispatcher/authorizationFile/" + this.id;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(page_url)
        .then(res => {
          this.authorizationFile = res.data.data;
          this.fetchAccountTypes();
          this.fileExtention = this.authorizationFile.file_path
            .split(".")
            .pop();
          this.isLoading = false;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    fetchAccountTypes() {
      let url = this.BASE_URL() + "/api/dispatcher/accountTypes";
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .get(url)
        .then(res => {
          this.accountTypes = res.data.data;
          if (!this.accountTypes) {
            this.$swal(
              "No Account types Found",
              "please create some account types first",
              "warning"
            );
          }
          this.accountTypes.forEach(accountType => {
            this.accountTypesNames.push(accountType.name);
          });
        })
        .catch(error => {
          this.$swal(
            "Error in fetching account types",
            error.response.data.errors,
            "warning"
          );
        });
    },
    updateAuthorizationFile() {
      this.pageIsLoading = true;
      var form = document.getElementById("form");
      var formData = new FormData(form);
      formData.append("account_type", this.authorizationFile.account_type);
      formData.append("status", this.authorizationFile.status);
      formData.append("_method", "put");
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_dispatcher;
      axios
        .post(
          this.BASE_URL() + "/api/dispatcher/authorizationFile/" + this.id,
          formData
        )
        .then(response => {
          this.pageIsLoading = false;
          this.$router.push({
            name: "DispatcherDashboard_authorizationFiles_show",
            params: { id: this.id }
          });
        })
        .catch(error => {
          this.pageIsLoading = false;
          if (
            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            let output = "<br><br>";
            for (let property in error.response.data.errors) {
              output +=
                '<p align="left">' +
                "*" +
                error.response.data.errors[property] +
                "</p>";
            }

            this.$swal(error.response.data.message, output, "warning");
          } else {
            this.$swal("Try again", "Uknown Error ", "warning");
          }
        });
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
  justify-content: center;
  align-items: center;
}

#preview img {
  max-width: 900px;
  max-height: 100%;
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
