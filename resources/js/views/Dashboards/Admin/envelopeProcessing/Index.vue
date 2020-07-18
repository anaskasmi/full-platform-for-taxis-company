<template>
  <div class="wrapper_box">
    <NavbarAdmin></NavbarAdmin>
    <Drawer></Drawer>
    <!-- header -->
    <div class="wrapper_header mt-10">
      <div class="title_header text-uppercase">Batch Processing</div>
    </div>
    <hr />
    <div>
      <!-- sentence -->
      <div class="sentence text-center">
        Batch :{{batch.batch_id}}
        <hr class="text-center" />
      </div>

      <!-- info about batch -->
      <div class>
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
              <th>Batch Status</th>
              <td v-if="batch.status=='UNDER-PROCESSING'" style="color:green;">{{batch.status}}</td>
              <td v-if="batch.status=='CLOSED'" style="color:grey;">{{batch.status}}</td>
            </tr>
            <tr>
              <th>Envelope</th>
              <td>{{envelope.envelope_id}}</td>
            </tr>
            <tr>
              <th colspan="2" class="text-center">
                <v-btn class="info" @click="loadNewTrips()">Load the new Trips to this batch</v-btn>
              </th>
            </tr>
          </tbody>
        </table>

        <div class="row text-center mt-8 mx-auto">
          <!-- <v-btn
            v-if="isClosed"
            color="success"
            style="color:white;"
            @click="startProcessing()"
          >Start Processing this Envelope</v-btn>
          <v-btn
            v-if="!isClosed"
            color="grey"
            style="color:white;"
            @click="endProcessing()"
          >End Processing This Envelope</v-btn>-->
          <v-switch
            class="mx-auto"
            v-model="status_switch"
            @change="status_switch_listener"
            color="success"
            :label="status_label"
          ></v-switch>
        </div>
        <!-- end of innfo -->
        <!-- start of tabs -->
        <v-tabs fixed-tabs background-color="primary" class="mt-8" dark>
          <v-tab @click="showAccountSlips()">Account Slips</v-tab>
          <v-tab @click="showTaxiSavers()">Taxi Savers</v-tab>
          <v-tab @click="showPosSlips()">Pos Slips</v-tab>
          <v-tab @click="showSummary()">Summary</v-tab>
          <v-tab @click="showGeneratePdfs()">Generate PDFs</v-tab>
        </v-tabs>
        <!-- end of tabs -->
        <AccountSlips :key="componentKey3" v-if="isShowAccountSlips"></AccountSlips>
        <posSlips :key="componentKey2" v-if="isShowPosSlips"></posSlips>
        <taxiSavers :key="componentKey" v-if="isShowTaxiSavers"></taxiSavers>
        <summaryTab v-if="isShowSummary"></summaryTab>
        <generatePdfs v-if="isShowGeneratePdfs"></generatePdfs>
      </div>
    </div>
  </div>
</template>
<script>
import NavbarAdmin from "@/js/components/navbars/Admin.vue";
import Drawer from "@/js/components/drawers/Admin.vue";
import addNewEnvelopes from "@/js/views/Dashboards/Admin/envelopeProcessing/Index.vue";
import posSlips from "@/js/views/Dashboards/Admin/envelopeProcessing/posSlips.vue";
import taxiSavers from "@/js/views/Dashboards/Admin/envelopeProcessing/taxiSavers.vue";
import AccountSlips from "@/js/views/Dashboards/Admin/envelopeProcessing/accountSlips.vue";
import generatePdfs from "@/js/views/Dashboards/Admin/envelopeProcessing/generatePdfs.vue";
import summaryTab from "@/js/views/Dashboards/Admin/envelopeProcessing/summaryTab.vue";
export default {
  created() {
    this.getBatcheById(this.$route.params.batch_id);
    this.getEnvelopeById(this.$route.params.envelope_id);
    this.showAccountSlips();
  },
  components: {
    NavbarAdmin,
    Drawer,
    addNewEnvelopes,
    posSlips,
    taxiSavers,
    AccountSlips,
    generatePdfs,
    summaryTab,
  },
  data() {
    return {
      batch: "",
      componentKey: 0,
      componentKey2: 0,
      componentKey3: 0,

      status_label: "",
      status_switch: null,
      isShowPosSlips: false,
      isShowTaxiSavers: false,
      isShowAccountSlips: false,
      isShowSummary: false,
      isShowGeneratePdfs: false,
      envelope: {},
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
    forceRerender() {
      this.componentKey += 1;
      this.componentKey2 += 1;
      this.componentKey3 += 1;
    },
    getBatcheById(id) {
      //fetch batch data
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.state.token_admin;
      axios
        .get(this.BASE_URL() + "/api/admin/batch/" + id)
        .then(res => {
          this.batch = res.data.data;
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
          this.status_switch = !this.isClosed;
        })
        .catch(error => {
          this.$swal("Try again", error.response.data.errors, "warning");
        });
    },
    hideAllTabs() {
      this.isShowAddNewEnvelope = false;
      this.isShowPosSlips = false;
      this.isShowTaxiSavers = false;
      this.isShowAccountSlips = false;
      this.isShowSummary = false;
      this.isShowGeneratePdfs = false;
    },

    showPosSlips() {
      this.hideAllTabs();
      this.isShowPosSlips = true;
    },

    showTaxiSavers() {
      this.hideAllTabs();
      this.isShowTaxiSavers = true;
    },

    showAccountSlips() {
      this.hideAllTabs();
      this.isShowAccountSlips = true;
    },
    showSummary() {
      this.hideAllTabs();
      this.isShowSummary = true;
    },
    showGeneratePdfs() {
      this.hideAllTabs();
      this.isShowGeneratePdfs = true;
    },
    endProcessing() {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "envelope : " + this.envelope.envelope_id + " will be Closed !",
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
            this.envelope.status = "CLOSED";
            this.envelope._method = "patch";
            axios
              .post(
                this.BASE_URL() + "/api/admin/Envelope/" + this.envelope.id,
                this.envelope
              )
              .then(data => {
                this.$swal({
                  title: "Closed!",
                  text: "envelope has been Closed",
                  type: "success",
                  timer: 700
                });
                this.forceRerender();
                this.$router.push({
                  name: "AdminDashboard_batches_envelopes",
                  params: { batch_id: this.batch.id }
                });
              })
              .catch(error => {
                this.$swal("Error", error.message, "warning");
                this.forceRerender();
              });
            this.forceRerender();
          }
        });
    },
    startProcessing() {
      this.envelope.status = "UNDER-PROCESSING";
      this.envelope._method = "patch";
      axios
        .post(
          this.BASE_URL() + "/api/admin/Envelope/" + this.envelope.id,
          this.envelope
        )
        .then(data => {
          this.$swal({
            title: "Opened!",
            text: "This envelope has been Opened!",
            type: "success",
            timer: 700
          });
          this.getEnvelopeById(this.envelope.id);
          this.forceRerender();
        })
        .catch(error => {
          console.log(error);
          this.getEnvelopeById(this.envelope.id);
          this.$swal("Error", error.response.data.message, "warning");
          this.forceRerender();
        });
      this.forceRerender();
    },
    status_switch_listener() {
      if (this.status_switch) {
        this.startProcessing();
      } else {
        this.endProcessing();
      }
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
        return true;
      } else {
        this.status_label =
          "Envelope " + this.envelope.envelope_id + " Is Under Processing";
        return false;
      }
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
.sentence {
  margin-top: 2em;
  font-family: "Quicksand", sans-serif;
  justify-self: center;
  font-size: 2.5em;
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