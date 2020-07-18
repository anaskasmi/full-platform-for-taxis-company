<template>
  <div class="wrapper_box">
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>

    <!-- start of header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Envelopes</div>
    </div>
    <hr />
    <!-- start info about the batch  -->
    <!-- sentence -->
    <div class="sentence text-center">
      Batch :{{batch.batch_id}}
      <hr class="text-center" />
    </div>

    <!-- end of header -->
    <table class="table table-bordered col-4 mx-auto">
      <tbody>
        <tr>
          <th>Number</th>
          <td>B{{batch.number}}</td>
        </tr>
        <tr>
          <th>Date</th>
          <td>{{batch.month}} / {{batch.year}}</td>
        </tr>
        <tr>
          <th>Status</th>
          <td v-if="batch.status=='UNDER-PROCESSING'" style="color:green;">{{batch.status}}</td>
          <td v-if="batch.status=='CLOSED'" style="color:grey;">{{batch.status}}</td>
        </tr>
        <tr>
          <th colspan="2" class="text-center">
            <v-btn class="info" @click="loadNewTrips()">Load the new Trips to this batch</v-btn>
          </th>
        </tr>
      </tbody>
    </table>
    <!-- end info about the batch  -->
    <!-- start button of processing -->
    <div class="text-center mt-8">
      <v-btn
        v-if="isClosed"
        color="success"
        style="color:white;"
        @click="startProcessing()"
      >Start Processing this Batch</v-btn>
      <v-btn
        v-if="!isClosed"
        color="grey"
        style="color:white;"
        @click="endProcessing()"
      >End Processing This Batch</v-btn>
    </div>
    <!-- end button of processing -->

    <hr />
    <div>
      <v-dialog v-model="editDialog" max-width="500px">
        <v-card>
          <v-card-title>
            <span class="headline">Edit row</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <!-- Envelope ID INPUT -->
                <v-text-field
                  v-model="edit_envelope_id"
                  type="number"
                  label="Envelope ID"
                  ref="firstInput"
                  class="mx-1"
                  required
                  outlined
                ></v-text-field>
                <!-- Operator ID INPUT -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_operator_id"
                  label="Operator ID"
                  required
                  outlined
                ></v-text-field>
                <!-- Driver ID INPUT -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_driver_id"
                  label="Driver ID"
                  required
                  outlined
                ></v-text-field>
                <!-- MDT Claimed INPUT -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_mdt_claimed"
                  label="MDT Claimed"
                  type="number"
                  required
                  outlined
                ></v-text-field>
                <!-- TAXI SAVERS CLAIMED INPUT -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_taxi_savers_claimed"
                  label="Taxi Savers Claimed"
                  type="number"
                  required
                  outlined
                ></v-text-field>
                <!-- GIFT CERTICAT CLAIMED -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_pos_claimed"
                  label="POS claimed"
                  type="number"
                  required
                  outlined
                ></v-text-field>
                <!-- GIFT CERTICAT CLAIMED -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_gift_certificates_claimed"
                  label="Gift Certifcates Claimed"
                  type="number"
                  required
                  outlined
                ></v-text-field>
                <!-- unverified Total CLAIMED -->
                <v-text-field
                  class="mx-1"
                  v-model="edit_unverified_envelope_total"
                  label="Unverified Envelope Total"
                  type="number"
                  v-on:keyup.enter="addNewRow"
                  required
                  outlined
                ></v-text-field>
                <!-- ORIGINAL Total CLAIMED -->
                <v-btn
                  v-if="edit_original_total_claimed ==''"
                  color="#1976d2"
                  outlined
                  tile
                  block
                >Original Total Claimed Here</v-btn>
                <div v-else class="row col-12 align-center justify-content-around">
                  <b style="color:#1976d2;" class="ml-1">Original total claimed</b>
                  <v-btn
                    color="#1976d2"
                    class="ml-10"
                    outlined
                    tile
                  >$ {{edit_original_total_claimed}}</v-btn>
                </div>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
            <v-btn color="blue darken-1" text @click="editRow()">Update</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <div class="row col-12 justify-content-around">
        <!-- edit for data entery -->
        <div class="col-3 mb-12 mt-6 float-left">
          <p class="font-weight-bold mb-6" color="blue">New row</p>
          <v-form>
            <!-- Envelope ID INPUT -->
            <v-text-field
              ref="envelope_id_ref"
              v-model="form_envelopeform_envelope_id_id"
              v-on:keyup.enter="$refs.operator_id_ref.focus()"
              type="number"
              label="Envelope ID"
              required
              outlined
            ></v-text-field>

            <!-- Operator ID INPUT -->
            <v-text-field
              ref="operator_id_ref"
              v-on:keyup.enter="$refs.driver_id_ref.focus()"
              v-model="form_operator_id"
              label="Operator ID"
              required
              outlined
            ></v-text-field>

            <!-- Driver ID INPUT -->
            <v-text-field
              ref="driver_id_ref"
              v-on:keyup.enter="$refs.mdt_claimed_ref.focus()"
              v-model="form_driver_id"
              label="Driver ID"
              required
              outlined
            ></v-text-field>

            <!-- MDT Claimed INPUT -->
            <v-text-field
              ref="mdt_claimed_ref"
              v-on:keyup.enter="$refs.taxi_savers_claimed_ref.focus()"
              v-model="form_mdt_claimed"
              label="MDT Claimed"
              type="number"
              required
              outlined
            ></v-text-field>

            <!-- TAXI SAVERS CLAIMED INPUT -->
            <v-text-field
              ref="taxi_savers_claimed_ref"
              v-on:keyup.enter="$refs.pos_claimed_ref.focus()"
              v-model="form_taxi_savers_claimed"
              label="Taxi Savers Claimed"
              type="number"
              required
              outlined
            ></v-text-field>
            <!-- POS CLAIMED -->
            <v-text-field
              ref="pos_claimed_ref"
              v-on:keyup.enter="$refs.gift_certificates_ref.focus()"
              v-model="form_pos_claimed"
              label="POS claimed"
              type="number"
              required
              outlined
            ></v-text-field>
            <!-- GIFT CERTICAT CLAIMED -->
            <v-text-field
              ref="gift_certificates_ref"
              v-on:keyup.enter="$refs.unverified_envelope_total_ref.focus()"
              v-model="form_gift_certificates_claimed"
              label="Gift Certifcates Claimed"
              type="number"
              required
              outlined
            ></v-text-field>
            <!-- unverified Total CLAIMED -->
            <v-text-field
              ref="unverified_envelope_total_ref"
              v-model="form_unverified_envelope_total"
              label="Unverified Envelope Total"
              type="number"
              v-on:keyup.enter="addNewRow()"
              required
              outlined
            ></v-text-field>
            <!-- ORIGINAL Total CLAIMED -->
            <v-btn
              ref="original_total_claimed_ref"
              v-if="form_original_total_claimed ==''"
              color="#1976d2"
              outlined
              tile
              block
            >Original Total Claimed Here</v-btn>
            <div v-else class="row col-12 align-center justify-content-around">
              <b style="color:#1976d2;" class="ml-1">Original total claimed</b>
              <v-btn color="#1976d2" class="ml-10" outlined tile>$ {{form_original_total_claimed}}</v-btn>
            </div>
            <!-- ADD BUTTON -->
            <v-btn class="col-12 mt-5" color="info" tile outlined @click="addNewRow()">
              <v-icon>arrow_forward_ios</v-icon>
            </v-btn>
          </v-form>
        </div>
        <!-- <hr /> -->
        <!-- bootstrap table -->
        <div class="col-9 float-right mt-10">
          <table class="table table-bordered">
            <thead class="thead-dark">
              <tr>
                <th class="text-center" scope="col">STATUS</th>
                <th class="text-center" scope="col">Envelope ID</th>
                <th class="text-center" scope="col">Operator ID</th>
                <th class="text-center" scope="col">Driver ID</th>
                <th class="text-center" scope="col">MDT Claimed</th>
                <th class="text-center" scope="col">TAXI SAVERS CLAIMED</th>
                <th class="text-center" scope="col">POS CLAIMED</th>
                <th class="text-center" scope="col">GIFT CERTICAT CLAIMED</th>
                <th class="text-center" scope="col">Unverified Total CLAIMED</th>
                <th class="text-center" scope="col">ORIGINAL Total CLAIMED</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in rows" v-bind:key="row.id">
                <th
                  class="text-center"
                  scope="row"
                  v-if="row.status=='UNPROCESSED' || row.status==''"
                >{{row.status}}</th>
                <th
                  class="text-center bg-success"
                  scope="row"
                  v-if="row.status=='CLOSED'"
                >{{row.status}}</th>
                <th
                  class="text-center"
                  style="background-color:#ffbe76;"
                  scope="row"
                  v-if="row.status=='UNDER-PROCESSING'"
                >{{row.status}}</th>
                <th class="text-center" scope="row">{{row.envelope_id}}</th>
                <th class="text-center" scope="row">{{row.operator_id}}</th>
                <th class="text-center" scope="row">{{row.driver_id}}</th>
                <th class="text-center" scope="row">${{parseFloat(row.mdt_claimed).toFixed(2)}}</th>
                <th
                  class="text-center"
                  scope="row"
                >${{parseFloat(row.taxi_savers_claimed).toFixed(2) }}</th>
                <th class="text-center" scope="row">${{parseFloat(row.pos_claimed).toFixed(2) }}</th>

                <th
                  class="text-center"
                  scope="row"
                >${{ parseFloat(row.gift_certificates_claimed).toFixed(2) }}</th>

                <th
                  class="text-center"
                  scope="row"
                  v-if="parseFloat(row.unverified_envelope_total).toFixed(2)== parseFloat(row.original_total_claimed).toFixed(2)"
                >${{ parseFloat(row.unverified_envelope_total).toFixed(2)}}</th>

                <th
                  class="text-center"
                  style="color:red;"
                  scope="row"
                  v-else
                >${{ parseFloat(row.unverified_envelope_total).toFixed(2)}}</th>

                <th
                  class="text-center"
                  scope="row"
                  v-if=" parseFloat(row.unverified_envelope_total ).toFixed(2)== parseFloat(row.original_total_claimed).toFixed(2)"
                >${{ parseFloat(row.original_total_claimed).toFixed(2)}}</th>
                <th
                  class="text-center"
                  scope="row"
                  v-else
                >${{ parseFloat(row.original_total_claimed).toFixed(2)}}</th>

                <th class="text-right" style="max-width:100%;
white-space:nowrap;
">
                  <v-icon color="success" class="mx-1" @click="openEditDialog(row)">edit</v-icon>

                  <v-icon @click="deleteItem(row.id)" color="red lighten-1" class="mx-1">delete</v-icon>
                  <v-icon color="info" class="mx-1" @click="goToEnvelope(row)">description</v-icon>
                </th>
              </tr>
              <tr>
                <th
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                  colspan="4"
                >TOTALS</th>
                <th
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_mdt_claimed}}</th>
                <th
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_taxi_savers_claimed}}</th>
                <th
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_pos_claimed}}</th>
                <th
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_gift_certificat_claimed}}</th>
                <th
                  v-if="total_unverified_total_claimed ==total_original_total_claimed "
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_unverified_total_claimed}}</th>
                <th
                  v-else
                  class="text-uppercase text-center table-danger"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_unverified_total_claimed}}</th>
                <th
                  class="text-uppercase text-center table-primary"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >$ {{total_original_total_claimed}}</th>
                <th
                  class="table-primary text-center"
                  style="  font-weight: bold; max-width:100%; white-space:nowrap;"
                >{{rows.length}} Envelopes</th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="sentence text-center">
        You Can Download Excel Files Here !
        <hr class="text-center" />
      </div>

      <!-- generate button -->

      <div class="row col-12">
        <div class="col-3">
          <a
            style="color:white; width:100%"
            :href="BASE_URL() +
          '/api/admin/Envelopes/download/' +
          batch.batch_id"
            target="_blank"
          >
            <v-btn color="success" class="col-12 mt-2 mb-10" tile>
              <v-icon class="mx-4">mdi-cloud-download</v-icon>Envelopes
            </v-btn>
          </a>
        </div>
        <div class="col-3">
          <a
            style="color:white; width:100%"
            :href="BASE_URL() +
          '/api/admin/AccountSlips/download/' +
          batchId"
            target="_blank"
          >
            <v-btn color="success" class="col-12 mt-2 mb-10" tile>
              <v-icon class="mx-4">mdi-cloud-download</v-icon>Account SLIPs
            </v-btn>
          </a>
        </div>
        <div class="col-3">
          <a
            style="color:white; width:100%"
            :href="BASE_URL() +
          '/api/admin/taxiSavers/download/' +
          batchId"
            target="_blank"
          >
            <v-btn color="success" class="col-12 mt-2 mb-10" tile>
              <v-icon class="mx-4">mdi-cloud-download</v-icon>Taxi Savers
            </v-btn>
          </a>
        </div>
        <div class="col-3">
          <a
            style="color:white; width:100%"
            :href="BASE_URL() +
          '/api/admin/posSlips/download/' +
          batchId"
            target="_blank"
          >
            <v-btn color="success" class="col-12 mt-2 mb-10" tile>
              <v-icon class="mx-4">mdi-cloud-download</v-icon>POS SLIPs
            </v-btn>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";

export default {
  created() {
    this.fetchBatchData(this.$route.params.batch_id);
  },
  components: {
    NavbarAdmin,
    Drawer
  },
  data() {
    return {
      batchId: "",
      //form data
      form_envelopeform_envelope_id_id: "",
      form_operator_id: "",
      form_driver_id: "",
      form_mdt_claimed: "",
      form_taxi_savers_claimed: "",
      form_pos_claimed: "",
      form_gift_certificates_claimed: 0,
      form_unverified_envelope_total: "",
      //edit data
      edit_envelope_id: "",
      edit_operator_id: "",
      edit_driver_id: "",
      edit_mdt_claimed: "",
      edit_taxi_savers_claimed: "",
      edit_pos_claimed: "",
      edit_gift_certificates_claimed: "",
      edit_unverified_envelope_total: "",

      //id of envelope to edit
      editId: -1,
      //edition dialog
      editDialog: false,
      //current batch",
      batch: {},
      //rows or batches
      rows: [],
      batches: [
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
      numberOfAddedTrips:null,
    };
  },
  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    loadNewTrips(){
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/batch/getNewTrips/" + this.$route.params.batch_id)
        .then(res => {
          this.numberOfAddedTrips = res.data;
          this.$swal(this.numberOfAddedTrips+" Trip Added", "", "success");
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    fetchBatchData(id) {
      //fetch batches by year
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/batch/" + id)
        .then(res => {
          this.batch = res.data.data;
          this.batchId = res.data.data.batch_id;
          this.refreshTable();
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    refreshTable() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelopes/" + this.batch.batch_id)
        .then(res => {
          this.rows = res.data.data;
          this.form_envelopeform_envelope_id_id = this.envelope_counter;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    addNewRow() {
      var formData = {
        batch_id: this.batch.batch_id,
        batch_number: this.batch.number,
        month: this.batch.month,
        year: this.batch.year,
        envelope_id: this.form_envelopeform_envelope_id_id,
        operator_id: this.form_operator_id,
        driver_id: this.form_driver_id,
        mdt_claimed: this.form_mdt_claimed,
        taxi_savers_claimed: this.form_taxi_savers_claimed,
        pos_claimed: this.form_pos_claimed,
        gift_certificates_claimed: this.form_gift_certificates_claimed,
        unverified_envelope_total: this.form_unverified_envelope_total,
        original_total_claimed: this.form_original_total_claimed
      };
      console.log(formData);
      //add tow to the database
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .post(this.BASE_URL() + "/api/admin/Envelope", formData)
        .then(res => {
          this.$swal({
            title: "Row Added!",
            text: "Row Added Successfully!",
            type: "success",
            timer: 700
          });
          this.clearForm();
          this.refreshTable();
        })
        .catch(error => {
          console.log(error);
          // this.$swal("Try again", error.response.data.errors, "warning");
        });
      //reset form
      this.form_Amount = null;
      this.form_envelope_id = null;
      this.$nextTick(() => this.$refs.envelope_id_ref.focus());
    },
    openEditDialog(currentEnvelope) {
      this.editId = currentEnvelope.id;
      this.edit_envelope_id = currentEnvelope.envelope_id;
      this.edit_operator_id = currentEnvelope.operator_id;
      this.edit_driver_id = currentEnvelope.driver_id;
      this.edit_mdt_claimed = currentEnvelope.mdt_claimed;
      this.edit_taxi_savers_claimed = currentEnvelope.taxi_savers_claimed;
      this.edit_pos_claimed = currentEnvelope.pos_claimed;
      this.edit_gift_certificates_claimed =
        currentEnvelope.gift_certificates_claimed;
      this.edit_unverified_envelope_total =
        currentEnvelope.unverified_envelope_total;
      this.editDialog = true;
    },
    editRow() {
      if (this.valid_edit && this.editId != -1) {
        var editData = {
          batch_id: this.batch.batch_id,
          batch_number: this.batch.number,
          month: this.batch.month,
          year: this.batch.year,
          envelope_id: this.edit_envelope_id,
          operator_id: this.edit_operator_id,
          driver_id: this.edit_driver_id,
          mdt_claimed: this.edit_mdt_claimed,
          taxi_savers_claimed: this.edit_taxi_savers_claimed,
          pos_claimed: this.edit_pos_claimed,
          gift_certificates_claimed: this.edit_gift_certificates_claimed,
          unverified_envelope_total: this.edit_unverified_envelope_total,
          original_total_claimed: this.edit_original_total_claimed,
          _method: "patch"
        };
        //add tow to the database
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .post(
            this.BASE_URL() + "/api/admin/Envelope/" + this.editId,
            editData
          )
          .then(res => {
            this.$swal({
              title: "updated!",
              text: "Row updated Successfully!",
              type: "success",
              timer: 700
            });
            this.refreshTable();
            this.close();
          })
          .catch(error => {
            this.$swal("Try again", error.response.data.errors, "warning");
          });
      } else {
      }
    },
    goToEnvelope(row) {
      console.log();
      this.$router.push({
        name: "AdminDashboard_batches_envelopes_envelope",
        params: { envelope_id: row.id }
      });
    },
    deleteItem(id) {
      this.$swal
        .fire({
          text: "Are You Sure You Want To Delete This Row?",
          type: "question",
          animation: true,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonText: "Yes"
        })
        .then(res => {
          if (res.value) {
            axios.defaults.headers.common["Authorization"] =
              "Bearer " + this.$store.state.token_admin;
            axios
              .delete(this.BASE_URL() + "/api/admin/Envelope/" + id)
              .then(res => {
                this.$swal({
                  title: "Deleted!",
                  text: "Deleted Successfully!",
                  type: "success",
                  timer: 700
                });
                this.refreshTable();
              })
              .catch(error => {
                this.$swal("Try again", error.response.data.errors, "warning");
              });
          }
        });
    },
    clearForm() {
      this.form_operator_id = "";
      this.form_driver_id = "";
      this.form_mdt_claimed = "";
      this.form_taxi_savers_claimed = "";
      this.form_pos_claimed = "";
      this.form_gift_certificates_claimed = 0;
      this.form_unverified_envelope_total = "";
      this.form_envelope_id = this.envelope_counter;
    },
    close() {
      this.editDialog = false;
      this.editId = -1;
    },

    generateExcel() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(
          this.BASE_URL() +
            "/api/admin/posSlips/download/" +
            this.batch.batch_id
        )
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response.data.errors);
        });
    },
    startProcessing() {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "Batch : " + this.batch.batch_id + " will be processed again !",
          type: "question",
          animation: false,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#424242",
          confirmButtonText: "Yes, update it!"
        })
        .then(result => {
          if (result.value) {
            this.batch.status = "UNDER-PROCESSING";
            this.batch._method = "patch";
            axios
              .post(
                this.BASE_URL() + "/api/admin/batch/" + this.batch.id,
                this.batch
              )
              .then(data => {
                this.$swal({
                  title: "Opened!",
                  text: "This Batch has been Opened!",
                  type: "success",
                  timer: 700
                });
              })
              .catch(error => {
                this.getBatcheById(this.batch.id);

                this.$swal("Error", error.response.data.message, "warning");
              });
          }
        });
    },
    endProcessing() {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "Batch : " + this.batch.batch_id + " will be Closed !",
          type: "question",
          animation: false,
          focusConfirm: false,
          padding: "2rem",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#424242",
          confirmButtonText: "Yes, Close it!"
        })
        .then(result => {
          if (result.value) {
            this.batch.status = "CLOSED";
            this.batch._method = "patch";
            axios
              .post(
                this.BASE_URL() + "/api/admin/batch/" + this.batch.id,
                this.batch
              )
              .then(data => {
                this.$swal({
                  title: "Closed!",
                  text: "Batch has been Closed!",
                  type: "success",
                  timer: 700
                });
                this.$router.push({
                  name: "AdminDashboard_batches"
                });
              })
              .catch(error => {
                this.$swal("Error", error.message, "warning");
              });
          }
        });
    }
  },
  watch: {
    dialog(val) {
      val || this.close();
    }
  },
  computed: {
    total_mdt_claimed() {
      let total = 0;
      this.rows.forEach(row => {
        total = parseFloat(total) + parseFloat(row.mdt_claimed);
      });
      return total.toFixed(2);
    },
    total_taxi_savers_claimed() {
      let total = 0;
      this.rows.forEach(row => {
        total = parseFloat(total) + parseFloat(row.taxi_savers_claimed);
      });
      return total.toFixed(2);
    },
    total_pos_claimed() {
      let total = 0;
      this.rows.forEach(row => {
        total = parseFloat(total) + parseFloat(row.pos_claimed);
      });
      return total.toFixed(2);
    },
    total_gift_certificat_claimed() {
      let total = 0;
      this.rows.forEach(row => {
        total = parseFloat(total) + parseFloat(row.gift_certificates_claimed);
      });
      return total.toFixed(2);
    },
    total_unverified_total_claimed() {
      let total = 0;
      this.rows.forEach(row => {
        total = parseFloat(total) + parseFloat(row.unverified_envelope_total);
      });
      return total.toFixed(2);
    },
    total_original_total_claimed() {
      let total = 0;
      this.rows.forEach(row => {
        total = parseFloat(total) + parseFloat(row.original_total_claimed);
      });
      return total.toFixed(2);
    },
    //end of totals
    envelope_counter() {
      let max = 0;
      this.rows.forEach(row => {
        max =
          row.envelope_id > max ? (max = parseInt(row.envelope_id, 10)) : max;
      });
      return max + 1;
    },
    valid_form() {
      if (
        this.form_envelopeform_envelope_id_id &&
        this.form_operator_id &&
        this.form_driver_id &&
        this.form_mdt_claimed &&
        this.form_taxi_savers_claimed &&
        this.form_pos_claimed &&
        this.form_gift_certificates_claimed &&
        this.form_unverified_envelope_total
      ) {
        return true;
      } else {
        return false;
      }
    },
    valid_edit() {
      if (
        this.edit_operator_id != "" &&
        this.edit_driver_id != "" &&
        this.edit_mdt_claimed != "" &&
        this.edit_taxi_savers_claimed != "" &&
        this.edit_gift_certificates_claimed != "" &&
        this.edit_unverified_envelope_total != "" &&
        this.edit_operator_id != null &&
        this.edit_driver_id != null &&
        this.edit_mdt_claimed != null &&
        this.edit_taxi_savers_claimed != null &&
        this.edit_pos_claimed != null &&
        this.edit_gift_certificates_claimed != null &&
        this.edit_unverified_envelope_total != null
      ) {
        return true;
      } else {
        return false;
      }
    },
    form_original_total_claimed() {
      if (
        this.form_taxi_savers_claimed != "" ||
        this.form_gift_certificates_claimed != "" ||
        this.form_mdt_claimed != "" ||
        this.form_pos_claimed != ""
      ) {
        return (
          parseFloat(this.form_mdt_claimed === "" ? 0 : this.form_mdt_claimed) +
          parseFloat(
            this.form_taxi_savers_claimed === ""
              ? 0
              : this.form_taxi_savers_claimed
          ) +
          parseFloat(this.form_pos_claimed === "" ? 0 : this.form_pos_claimed) +
          parseFloat(
            this.form_gift_certificates_claimed === ""
              ? 0
              : this.form_gift_certificates_claimed
          )
        );
      } else {
        return "";
      }
    },
    edit_original_total_claimed() {
      if (
        this.edit_taxi_savers_claimed != "" ||
        this.edit_gift_certificates_claimed != "" ||
        this.edit_pos_claimed != "" ||
        this.edit_mdt_claimed != ""
      ) {
        return (
          parseFloat(this.edit_mdt_claimed === "" ? 0 : this.edit_mdt_claimed) +
          parseFloat(
            this.edit_taxi_savers_claimed === ""
              ? 0
              : this.edit_taxi_savers_claimed
          ) +
          parseFloat(
            this.edit_gift_certificates_claimed === ""
              ? 0
              : this.edit_gift_certificates_claimed
          ) +
          parseFloat(this.edit_pos_claimed === "" ? 0 : this.edit_pos_claimed)
        );
      } else {
        return "";
      }
    },
    isClosed() {
      if (this.batch.status == "CLOSED") {
        return true;
      } else {
        return false;
      }
    }
  }
};
</script>
<style scoped>
.wrapper_box {
  display: grid;
  grid-gap: 0.2em;
  grid-auto-rows: minmax(10px, auto);
}

a,
a:hover {
  text-decoration: none;
}
.wrapper_header {
  display: grid;
  grid-template-columns: 10fr 0fr;
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
.sentence {
  margin-top: 2em;
  font-family: "Quicksand", sans-serif;
  justify-self: center;
  font-size: 2.5em;
}
</style>