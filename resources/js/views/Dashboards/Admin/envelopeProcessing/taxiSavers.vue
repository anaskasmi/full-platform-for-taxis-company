<template>
  <div>
    <v-dialog v-model="editDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Edit row</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <div>
              <v-menu
                v-model="edit_datePicker"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="edit_date"
                    label="Date"
                    prepend-icon="event"
                    @input="edit_datePicker = false"
                    v-on="on"
                    v-on:keyup.enter="edit_datePicker = false;$refs.envelopes.focus();"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="edit_date" @input="edit_datePicker = false"></v-date-picker>
              </v-menu>
            </div>
            <!-- envelopes -->
            <v-select
              :items="envelopes"
              v-model="edit_Envelope"
              label="Envelope ID"
              item-text="envelope_id"
              item-value="envelope_id"
            ></v-select>
            <!-- ammount  -->
            <v-text-field
              v-model="edit_Amount"
              label="Amount"
              type="number"
              ref="firstInput"
              required
              outlined
            ></v-text-field>
            <!-- slip Id  -->
            <v-text-field v-model="edit_Slip_id" label="Slip ID" type="number" required outlined></v-text-field>
            <!-- end of edit inputs -->
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="editRow">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- summary start -->
    <div v-if="!loading" class="row col-4 mx-auto">
      <div class="mx-auto ma-4">
        <table class="table table-primary table-bordered">
          <thead></thead>
          <tbody>
            <tr class="align-center text-center">
              <th colspan="2" class="align-center mx-auto">{{summary.taxi_savers_count}} Taxi Savers</th>
            </tr>
            <tr>
              <th>Verified Totals</th>
              <th>${{parseFloat(summary.taxi_savers_verified ).toFixed(2) }}</th>
            </tr>
            <tr>
              <th>Claimed Totals</th>
              <th>${{parseFloat(summary.taxi_savers_claimed ).toFixed(2) }}</th>
            </tr>

            <tr
              v-if="parseFloat( summary.taxi_savers_verified - summary.taxi_savers_claimed).toFixed(2)  ==0"
              class="table-success"
            >
              <th>Difference</th>
              <th>$0</th>
            </tr>
            <tr v-else class="table-warning">
              <th>Difference</th>
              <th>${{ parseFloat( summary.taxi_savers_verified - summary.taxi_savers_claimed ).toFixed(2) }}</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- summary end -->
    <div class="row col-12 justify-content-around">
      <!-- form for data entery -->
      <div v-if="!isClosed" class="col-4 mb-12 mt-10 float-left">
        <p class="font-weight-bold mb-6" color="blue">New row</p>
        <v-form>
          <div>
            <v-menu
              v-model="datePicker"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  v-model="form_date"
                  label="Date"
                  prepend-icon="event"
                  @input="datePicker = false"
                  v-on="on"
                  v-on:keyup.enter="datePicker = false;$refs.envelopes.focus();"
                ></v-text-field>
              </template>
              <v-date-picker v-model="form_date" @input="datePicker = false"></v-date-picker>
            </v-menu>
          </div>
          <!-- envelopes -->
          <v-select
            ref="envelopes"
            :items="envelopes"
            v-model="currentEnvelopeID"
            label="Envelope ID"
            v-on:keyup.enter="$refs.form_Slip_id.focus();"
            item-text="envelope_id"
            item-value="envelope_id"
          ></v-select>

          <!-- slip Id  -->
          <v-text-field
            ref="slip_id"
            v-model="currentSlipID"
            label="Slip ID"
            type="number"
            v-on:keyup.enter="$refs.amount.focus()"
            required
            outlined
          ></v-text-field>
          <!-- ammount  -->
          <v-text-field
            ref="amount"
            v-model="form_Amount"
            label="Amount"
            type="number"
            v-on:keyup.enter="addNewRow"
            required
            outlined
          ></v-text-field>
          <!-- add button -->
          <v-btn
            class="col-12 mt-5"
            :disabled="!form_valid"
            color="info"
            tile
            outlined
            @click="addNewRow"
          >
            <v-icon>arrow_forward_ios</v-icon>
          </v-btn>
        </v-form>
      </div>
      <!-- <hr /> -->

      <!-- bootstrap table -->
      <div class="col-6 float-right mt-10">
        <table class="table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th class="text-center" scope="col">Date</th>
              <th class="text-center" scope="col">Envelope ID</th>
              <th class="text-center" scope="col">Slip ID</th>
              <th class="text-center" scope="col">Amount</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in taxiSavers" v-bind:key="row.id">
              <th class="text-center" scope="row">{{row.date}}</th>
              <th class="text-center" scope="row">{{row.envelope_id}}</th>
              <th class="text-center" scope="row">{{row.slip_id}}</th>
              <th class="text-center" scope="row">${{parseFloat(row.amount).toFixed(2)}}</th>
              <th class="text-right">
                <v-icon color="success" class="mx-1" @click="openEditDialog(row)">edit</v-icon>

                <v-icon @click="deleteItem(row.id)" color="red lighten-1" class="mx-1">delete</v-icon>
              </th>
            </tr>
            <tr>
              <th class="text-uppercase text-center table-primary" colspan="3">TOTALS</th>
              <th
                class="text-uppercase text-center table-primary"
                style="  font-weight: bold; max-width:100%; white-space:nowrap;"
              >$ {{total_amounts}}</th>

              <th
                colspan="1"
                class="text-uppercase text-center table-success"
              >{{taxiSavers.length}} Taxi Savers</th>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- generate button -->
  </div>
</template>
<script>
export default {
  created() {
    this.fetchBatchData(this.$route.params.batch_id);
    this.getSummaryById(this.$route.params.envelope_id);
  },
  data() {
    return {
      componentKey: 0,
      summary: {},
      editId: -1,
      editDialog: false,
      form_Amount: "",
      form_Envelope: "",
      form_Slip_id: null,
      form_date: new Date().toISOString().substr(0, 10),
      datePicker: "",
      edit_Amount: "",
      edit_Envelope: "",
      edit_Slip_id: "",
      edit_date: new Date().toISOString().substr(0, 10),
      edit_datePicker: "",
      edit_Amount: null,
      edit_Envelope: null,
      envelope: {},
      batchId: "",
      month: "",
      batch: "",
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
      taxiSavers: [],
      envelopes: []
    };
  },

  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
    },
    forceRerender() {
      this.componentKey += 1;
    },
    fetchBatchData(id) {
      //fetch batches by year
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/batch/" + id)
        .then(res => {
          this.batch = res.data.data;
          this.batchId =
            "B" + this.batch.number + this.batch.month + this.batch.year;
          this.fetchEnvelopes();
          this.getEnvelopeById(this.$route.params.envelope_id);
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    getSummaryById(id) {
      //fetch envelope data
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelope/summary/" + id)
        .then(res => {
          this.summary = res.data;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    getEnvelopeById(id) {
      //fetch envelope data
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelope/" + id)
        .then(res => {
          this.envelope = res.data.data;
          this.fetchTaxiSavers();
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    fetchEnvelopes() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/Envelopes/" + this.batch.batch_id)
        .then(res => {
          this.envelopes = res.data.data;
          this.fetchTaxiSavers();
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    fetchTaxiSavers() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(
          this.BASE_URL() +
            "/api/admin/TaxiSavers/" +
            this.batchId +
            "/" +
            this.envelope.envelope_id
        )
        .then(res => {
          this.taxiSavers = res.data.data;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    addNewRow() {
      if (this.form_valid) {
        var formData = {
          envelope_id: this.form_Envelope,
          amount: this.form_Amount,
          batch_id: this.batchId,
          slip_id: this.form_Slip_id,
          date: this.form_date
        };
        //add tow to the database
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .post(this.BASE_URL() + "/api/admin/TaxiSaver", formData)
          .then(res => {
            // this.$swal("", "Row Added Successfully!", "success");
            this.fetchTaxiSavers();
          })
          .catch(error => {
            this.$swal("Try again", error.response.data.message, "warning");
          });
        //reset form
        this.form_Amount = null;
        // this.form_Envelope = null;
        this.form_Slip_id = null;
        this.$nextTick(() => this.$refs.slip_id.focus());
      } else {
        this.$swal("", "Invalid Data !", "warning");
      }
    },
    openEditDialog(taxiSaver) {
      this.editDialog = true;
      this.editId = taxiSaver.id;

      this.edit_Envelope = taxiSaver.envelope_id;
      this.edit_Amount = taxiSaver.amount;
      this.edit_date = taxiSaver.date;
      this.edit_Slip_id = taxiSaver.slip_id;
    },
    editRow() {
      if (this.edit_valid && this.editId != -1) {
        var editData = {
          envelope_id: this.edit_Envelope,
          amount: this.edit_Amount,
          batch_id: this.batchId,
          slip_id: this.edit_Slip_id,
          date: this.edit_date,
          _method: "patch"
        };
        //add tow to the database
        axios.defaults.headers.common["Authorization"] =
          "Bearer " + this.$store.state.token_admin;
        axios
          .post(
            this.BASE_URL() + "/api/admin/TaxiSaver/" + this.editId,
            editData
          )
          .then(res => {
            this.close();

            this.$swal("", "Row updated Successfully!", "success");

            this.fetchTaxiSavers();
          })
          .catch(error => {
            this.$swal("Try again", error.response.data.errors, "warning");
          });
      }
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
              .delete(this.BASE_URL() + "/api/admin/TaxiSaver/" + id)
              .then(res => {
                this.$swal("Deleted Successfully", "", "success");
                this.fetchTaxiSavers();
              })
              .catch(error => {
                this.$swal("Try again", error.response.data.errors, "warning");
              });
          }
        });
      // confirm("Are you sure you want to delete this item?") &&
    },

    close() {
      this.editDialog = false;
      this.editId = -1;
    },

    generateExcel() {
      console.log("generateExcel");
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/taxiSavers/download/" + this.batchId)
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error.response.data.errors);
        });
    }
  },
  watch: {
    dialog(val) {
      val || this.close();
    }
  },

  computed: {
    isClosed() {
      if (
        this.envelope.status == "UNPROCESSED" ||
        this.envelope.status == "CLOSED" ||
        this.envelope.status == ""
      ) {
        this.status_label =
          "Envelope " + this.envelope.envelope_id + " is Closed";
        this.forceRerender();
        return true;
      } else if (this.envelope.status == "UNDER-PROCESSING") {
        this.status_label =
          "Envelope " + this.envelope.envelope_id + " Is Under Processing";
        this.forceRerender();

        return false;
      } else {
        return true;
      }
    },
    total_amounts() {
      let total = 0;
      this.taxiSavers.forEach(row => {
        total = parseFloat(total) + parseFloat(row.amount);
      });
      total = total;
      return total.toFixed(2);
    },
    currentSlipID: {
      get: function() {
        if (this.form_Slip_id == 0) {
          return this.form_Slip_id;
        } else {
          let max = 0;
          this.taxiSavers.forEach(row => {
            if (row.slip_id && parseInt(row.slip_id) > parseInt(max)) {
              max = parseInt(row.slip_id);
            }
          });
          this.form_Slip_id = parseInt(max) + 1;
          return this.form_Slip_id;
        }
      },
      // setter
      set: function(newValue) {
        this.form_Slip_id = newValue;
      }
    },
    currentEnvelopeID: {
      get: function() {
        if (this.form_Envelope) {
          return this.form_Envelope;
        } else {
          this.form_Envelope = this.envelope.envelope_id;
          return this.form_Envelope;
        }
      },
      // setter
      set: function(newValue) {
        this.trip.envelope_id = newValue;
      }
    },
    form_valid() {
      if (
        this.form_Envelope != "" &&
        this.form_Amount != "" &&
        this.form_Slip_id != "" &&
        this.form_Envelope != null &&
        this.form_Amount != null &&
        this.form_Slip_id != null
      ) {
        return true;
      } else {
        return false;
      }
    },
    edit_valid() {
      if (
        this.edit_Envelope != "" &&
        this.edit_Amount != "" &&
        this.edit_Slip_id != "" &&
        this.edit_Envelope != null &&
        this.edit_Amount != null &&
        this.edit_Slip_id != null
      ) {
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