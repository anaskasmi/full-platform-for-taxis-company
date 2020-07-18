<template>
  <div class="text-center align-center mt-12 tile">
    <div class="sentence mb-10">Summary</div>
    <table class="col-6 table table-bordered table-hover mx-auto">
      <tbody>
        <tr class="table-primary text-uppercase font-weight-bold">
          <th colspan="2">Envelope</th>
        </tr>
        <tr>
          <th class="text-uppercase">verified total</th>
          <th>${{ parseFloat(summary.original_total_claimed).toFixed(2)}}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Unverified total</th>
          <th>${{parseFloat(summary.unverified_envelope_total).toFixed(2)}}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Difference</th>
          <th
            v-if=" parseFloat(summary.original_total_claimed).toFixed(2) -parseFloat(summary.unverified_envelope_total).toFixed(2) ==0"
            class="table-success"
          >${{parseFloat(summary.original_total_claimed ).toFixed(2) - parseFloat(summary.unverified_envelope_total ).toFixed(2) }}</th>
          <th
            v-else
            class="table-warning"
          >${{parseFloat(summary.original_total_claimed ).toFixed(2) - parseFloat(summary.unverified_envelope_total ).toFixed(2) }}</th>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr class="table-primary text-uppercase text-center font-weight-bold">
          <th colspan="2">Account Slips</th>
        </tr>
        <tr>
          <th class="text-uppercase">number of slips</th>
          <th>{{summary.mdt_count }}</th>
        </tr>
        <tr>
          <th class="text-uppercase">verified</th>
          <th>${{parseFloat(summary.mdt_verified ).toFixed(2) }}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Unverified</th>
          <th>${{parseFloat(summary.mdt_claimed ).toFixed(2) }}</th>
        </tr>

        <tr>
          <th class="text-uppercase">Difference</th>
          <th
            v-if="parseFloat(summary.mdt_verified -summary.mdt_claimed).toFixed(2) ==0"
            class="table-success"
          >${{ parseFloat(summary.mdt_verified -summary.mdt_claimed).toFixed(2) }}</th>
          <th
            v-else
            class="table-warning"
          >${{ parseFloat(summary.mdt_verified -summary.mdt_claimed).toFixed(2) }}</th>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr class="table-primary text-uppercase text-center font-weight-bold">
          <th colspan="2">Taxi Savers</th>
        </tr>
        <tr>
          <th class="text-uppercase">number of slips</th>
          <th>{{ summary.taxi_savers_count }}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Verified</th>
          <th>${{parseFloat(summary.taxi_savers_verified ).toFixed(2) }}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Unverified</th>
          <th>${{ parseFloat( summary.taxi_savers_claimed ).toFixed(2)}}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Difference</th>
          <th
            v-if=" parseFloat( summary.taxi_savers_verified-summary.taxi_savers_claimed ).toFixed(2) ==0"
            class="table-success"
          >${{parseFloat( summary.taxi_savers_verified-summary.taxi_savers_claimed ).toFixed(2)}}</th>
          <th
            v-else
            class="table-warning"
          >${{ parseFloat( summary.taxi_savers_verified-summary.taxi_savers_claimed ).toFixed(2) }}</th>
        </tr>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr class="table-primary text-uppercase text-center font-weight-bold">
          <th colspan="2">Pos SLIPS</th>
        </tr>
        <tr>
          <th class="text-uppercase">number of slips</th>
          <th>{{summary.pos_slips_count }}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Verified</th>
          <th>${{ parseFloat( summary.pos_slips_verified ).toFixed(2)}}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Unverified</th>
          <th>${{ parseFloat( summary.pos_slips_claimed ).toFixed(2)}}</th>
        </tr>
        <tr>
          <th class="text-uppercase">Difference</th>
          <th
            v-if=" parseFloat(  summary.pos_slips_verified -summary.pos_slips_claimed).toFixed(2)  ==0"
            class="table-success"
          >${{ parseFloat(  summary.pos_slips_verified -summary.pos_slips_claimed).toFixed(2)  }}</th>
          <th
            v-else
            class="table-warning"
          >${{parseFloat(  summary.pos_slips_verified -summary.pos_slips_claimed).toFixed(2)  }}</th>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  created() {
    this.getSummaryById(this.$route.params.envelope_id);
  },
  data() {
    return {
      summary: {}
    };
  },

  methods: {
    BASE_URL() {
      return this.$store.state.BASE_URL;
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