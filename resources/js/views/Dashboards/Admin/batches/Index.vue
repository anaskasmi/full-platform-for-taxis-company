<template>
  <div class="wrapper_box">
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>

    <!-- adding new batch dialog  -->
    <v-row justify="center">
      <v-dialog v-model="addNewBatchDialog" persistent max-width="600px">
        <v-stepper v-model="e1">
          <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1" editable>Year and Month</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2" editable>Batch Number</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step step="3" editable>Status</v-stepper-step>
          </v-stepper-header>

          <v-stepper-items>
            <v-stepper-content step="1">
              <div class="mb-12 text-center">
                <v-date-picker tile outlined v-model="pickedDate" type="month" width="500px"></v-date-picker>
              </div>

              <v-btn
                text
                tile
                class="col-6 float-left"
                @click="addNewBatchDialog = false; e1 = 1;"
              >Cancel</v-btn>
              <v-btn
                color="primary"
                tile
                class="col-6 float-right"
                outlined
                @click="e1 = 2"
              >Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
              <div class="mb-12" outlined tile height="200px">
                <v-select
                  label="Batch Number"
                  class="mt-12 mx-10"
                  v-model="pickedBatch"
                  :items="batchesList"
                  dense
                  solo
                ></v-select>
              </div>

              <v-btn
                text
                tile
                class="col-6 float-left"
                @click="addNewBatchDialog = false;  e1 = 1;"
              >Cancel</v-btn>
              <v-btn
                outlined
                tile
                class="col-6 float-right"
                color="primary"
                @click="e1 = 3"
              >Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content step="3">
              <div class="mb-12" outlined tile height="200px">
                <v-select
                  label="Batch Status"
                  class="mt-12 mx-10"
                  v-model="pickedStatus"
                  :items="statusList"
                  dense
                  solo
                ></v-select>
              </div>
              <v-btn
                text
                tile
                class="col-6 float-left"
                @click="addNewBatchDialog = false; e1 = 1;"
              >Cancel</v-btn>
              <v-btn color="primary" tile class="col-6 float-right" @click="createNewBatch()">Create</v-btn>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-dialog>
    </v-row>
    <!-- end of adding new batch dialog -->

    <!-- Editing batch dialog  -->
    <v-row justify="center">
      <v-dialog v-model="editBatchDialog" persistent max-width="600px">
        <v-stepper v-model="e1">
          <v-stepper-header>
            <v-stepper-step :complete="e1 > 1" step="1" editable>Year and Month</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step :complete="e1 > 2" step="2" editable>Batch Number</v-stepper-step>

            <v-divider></v-divider>

            <v-stepper-step step="3" editable>Status</v-stepper-step>
          </v-stepper-header>

          <v-stepper-items>
            <v-stepper-content step="1">
              <div class="mb-12 text-center">
                <v-date-picker tile outlined v-model="pickedDate" type="month" width="500px"></v-date-picker>
              </div>

              <v-btn
                text
                tile
                class="col-6 float-left"
                @click="editBatchDialog = false; e1 = 1;"
              >Cancel</v-btn>
              <v-btn
                color="primary"
                tile
                class="col-6 float-right"
                outlined
                @click="e1 = 2"
              >Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content step="2">
              <div class="mb-12" outlined tile height="200px">
                <v-select
                  label="Batch Number"
                  class="mt-12 mx-10"
                  v-model="pickedBatch"
                  :items="batchesList"
                  dense
                  solo
                ></v-select>
              </div>

              <v-btn
                text
                tile
                class="col-6 float-left"
                @click="editBatchDialog = false; e1 = 1;"
              >Cancel</v-btn>
              <v-btn
                outlined
                tile
                class="col-6 float-right"
                color="primary"
                @click="e1 = 3"
              >Continue</v-btn>
            </v-stepper-content>

            <v-stepper-content step="3">
              <div class="mb-12" outlined tile height="200px">
                <v-select
                  label="Batch Status"
                  class="mt-12 mx-10"
                  v-model="pickedStatus"
                  :items="statusList"
                  dense
                  solo
                ></v-select>
              </div>
              <v-btn
                text
                tile
                class="col-6 float-left"
                @click="editBatchDialog = false; e1 = 1;"
              >Cancel</v-btn>
              <v-btn color="primary" tile class="col-6 float-right" @click="editBatch()">Update</v-btn>
            </v-stepper-content>
          </v-stepper-items>
        </v-stepper>
      </v-dialog>
    </v-row>
    <!-- end of editing batch dialog -->

    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Batch Processing</div>

      <!-- if all batches are closed -->
      <v-btn
        link
        tile
        class="ma-2 button_header"
        outlined
        color="success "
        v-if="!batchUnderProcessing"
        @click="addNewBatchDialog = true;"
      >
        <v-icon left>mdi-pencil</v-icon>
        <div>Create New Batch</div>
      </v-btn>

      <!-- if there is a batch under processing -->
      <v-btn
        link
        tile
        outlined
        color="success"
        v-if="batchUnderProcessing"
        @click="goToBatchUnderPrecessing()"
        large
      >Continue Processing</v-btn>
    </div>
    <hr />
    <!-- search start -->
    <div class="row">
      <div class="col-md-12 md-form active-cyan-2 mb-3" style="margin-bottom: -15px !important;">
        <v-select
          v-model="selectedYear"
          :items="years"
          item-text="text"
          item-value="value"
          label="Year"
          @change="getBatchesByYear($event)"
        ></v-select>
      </div>
    </div>
    <!-- search end -->

    <v-row justify="center">
      <v-expansion-panels inset v-model="panel">
        <v-expansion-panel v-for=" month in months" :key="month.number" >
          <v-expansion-panel-header>{{month.name}}</v-expansion-panel-header>
          <v-expansion-panel-content>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-dark"></thead>
                <tbody>
                  <tr v-for="(batch,Index) in batches" v-bind:key="Index" class>
                    <th v-if="batch.month == month.number">B{{batch.number}}</th>

                    <td
                      v-if="batch.month == month.number && batch.status=='CREATED'"
                      style="color:green;"
                    >{{batch.status}}</td>
                    <td
                      v-if="batch.month == month.number && batch.status=='UNDER-PROCESSING'"
                      style="color:orange;"
                    >{{batch.status}}</td>
                    <td
                      v-if="batch.month == month.number && batch.status=='CLOSED'"
                      style="color:grey;"
                    >{{batch.status}}</td>

                    <td v-if="batch.month == month.number" class="text-right">
                      <!-- <v-icon color="success" class="mx-1" @click="showEditBatchDialog(batch)">edit</v-icon> -->
                      <v-icon color="red lighten-1" class="mx-1" @click="deleteBatch(batch)">delete</v-icon>
                      <v-icon color="primary" class="mx-1" @click="visitBatch(batch)">description</v-icon>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-row>
    <!-- table -->
    <!--  -->
    <v-alert
      v-if="noBatchfound"
      border="left"
      tile
      type="warning"
      dense
      dark
      elevation="2"
      class="mb-12"
      align="center"
    >Sorry, No Batch Found !</v-alert>
  </div>

  <!-- </div> -->
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  beforeMount() {
    this.noBatchfound = false;
    this.selectedYear = new Date().getFullYear().toString();
    this.getBatchesByYear(this.selectedYear);
  },
  components: {
    NavbarAdmin,
    Drawer
  },
  computed: {
    batchUnderProcessing() {
      let result = false;
      this.batches.forEach(batch => {
        if (batch.status == "UNDER-PROCESSING") {
          this.batchUnderPrecessingId = batch.id;
          this.panel = Number(batch.month)-1;
          result = true;
        }
      });
      return result;
    }
  },
  data() {
    return {
      statusList: [
        {
          text: "UNDER-PROCESSING",
          value: "UNDER-PROCESSING"
        },
        {
          text: "CLOSED",
          value: "CLOSED"
        }
      ],
      addNewBatchDialog: false,
      editBatchDialog: false,
      pickedDate: new Date().toISOString().substr(0, 7),
      pickedBatch: "",
      pickedStatus: "",
      editedBatchId: "",
      panel:"",
      batchUnderPrecessingId: "",
      batchesList: [
        {
          text: "Batch 1",
          value: "1"
        },
        {
          text: "Batch 2",
          value: "2"
        },
        {
          text: "Batch 3",
          value: "3"
        }
      ],
      e1: 0,
      years: [
        { text: "2019", value: "2019" },
        { text: "2020", value: "2020" },
        { text: "2021", value: "2021" },
        { text: "2020", value: "2020" },
        { text: "2021", value: "2021" },
        { text: "2022", value: "2022" },
        { text: "2023", value: "2023" },
        { text: "2024", value: "2024" },
        { text: "2025", value: "2025" },
        { text: "2026", value: "2026" },
        { text: "2027", value: "2027" },
        { text: "2028", value: "2026" },
        { text: "2029", value: "2029" }
      ],
      months: [
        { name: "January", number: "01" },
        { name: "February", number: "02" },
        { name: "March", number: "03" },
        { name: "April", number: "04" },
        { name: "May", number: "05" },
        { name: "June", number: "06" },
        { name: "July", number: "07" },
        { name: "August", number: "08" },
        { name: "September", number: "09" },
        { name: "October", number: "10" },
        { name: "November", number: "11" },
        { name: "December", number: "12" }
      ],
      selectedYear: "2019",
      batches: []
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    getBatchesByYear(year) {
      //fetch batches by year
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/batches/" + year)
        .then(res => {
          this.batches = res.data.data;
          this.selectedYear = year;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    goToBatchUnderPrecessing() {
      this.$router.push({
        name: "AdminDashboard_batches_envelopes",
        params: { batch_id: this.batchUnderPrecessingId }
      });
    },
    deleteBatch(batch) {
        this.$swal
          .fire({
            title: "Are you sure?",
            text: "Batch : " + batch.batch_id + " will be deleted !",
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
                .delete(this.BASE_URL() + "/api/admin/batch/" + batch.id)
                .then(data => {
                  this.getBatchesByYear(this.selectedYear);
                })
                .catch(err => console.log(err));
              this.$swal.fire("Deleted!", "Batch has been deleted.", "success");
            }
        });
    },

    createNewBatch() {
      if (this.pickedBatch != "" && this.pickedStatus != "") {
        let batchToCreateInfo = {
          batch_id:
            "B" +
            this.pickedBatch +
            this.$moment(this.pickedDate).format("MM") +
            this.$moment(this.pickedDate).format("YYYY"),
          number: this.pickedBatch,
          month: this.$moment(this.pickedDate).format("MM"),
          year: this.$moment(this.pickedDate).format("YYYY"),
          status: this.pickedStatus
        };
        console.log(batchToCreateInfo);
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .post(this.BASE_URL() + "/api/admin/batch", batchToCreateInfo)
          .then(res => {
            this.$swal("Success! ", "Batch created successfully", "success");
            this.getBatchesByYear(this.$moment(this.pickedDate).format("YYYY"));
            this.addNewBatchDialog = false;
          })
          .catch(error => {
            this.$swal("Error", error.response.data, "warning");
          });
      } else {
        this.$swal(
          "Invalid data",
          "Please try to fill in all the needed information!",
          "warning"
        );
      }
    },
    showBatch: function(batch) {},
    showEditBatchDialog(batch) {
      this.pickedDate = batch.year + "-" + batch.month;
      this.pickedBatch = batch.number.toString();
      this.pickedStatus = batch.status;
      this.editedBatchId = batch.id;
      this.editBatchDialog = true;
    },
    editBatch() {
      if (
        this.pickedBatch != "" &&
        this.pickedStatus != "" &&
        this.editedBatchId != ""
      ) {
        let batchToUpdateInfo = {
          batch_id:
            "B" +
            this.pickedBatch +
            this.$moment(this.pickedDate).format("MM") +
            this.$moment(this.pickedDate).format("YYYY"),
          number: this.pickedBatch,
          month: this.$moment(this.pickedDate).format("MM"),
          year: this.$moment(this.pickedDate).format("YYYY"),
          status: this.pickedStatus,
          _method: "patch"
        };
        console.log(batchToUpdateInfo);
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .post(
            this.BASE_URL() + "/api/admin/batch/" + this.editedBatchId,
            batchToUpdateInfo
          )
          .then(res => {
            this.$swal("Success! ", "Batch Updated successfully", "success");
            this.getBatchesByYear(this.$moment(this.pickedDate).format("YYYY"));
            this.editBatchDialog = false;
          })
          .catch(error => {
            this.$swal("Error", error.response.data, "warning");
          });
      } else {
        this.$swal(
          "Invalid data",
          "Please try to fill in all the needed information!",
          "warning"
        );
      }
    },
    visitBatch(batch) {
      this.$router.push({
        name: "AdminDashboard_batches_envelopes",
        params: { batch_id: batch.id }
      });
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
</style>