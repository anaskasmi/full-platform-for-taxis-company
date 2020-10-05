<template>
        <div>
            <!-- progress -->
            <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>

            <div v-if="!isLoading" class="table-responsive">
                <v-alert type="warning" outlined v-if="slips.length === 0" class="text-center font-weight-bold" >
                    No Slips Found !
                </v-alert>
                <table v-else class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Driver Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Vehicle</th>
                        <th scope="col">shiftType</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(slip,i) in slips" :key="i">
                        <td class="font-weight-bold text-uppercase align-middle fitCell" style="font-size: 1.2em;">{{slip.driverName}}</td>
                        <td class="font-weight-bold text-uppercase align-middle fitCell" style="font-size: 1.2em;">{{slip.date}}</td>
                        <td class="font-weight-bold text-uppercase align-middle fitCell" style="font-size: 1.2em;">{{slip.vehicle.vehicleName}}
                        </td>
                        <td class="font-weight-bold text-uppercase align-middle fitCell" style="font-size: 1.2em;">{{slip.shiftType}}</td>


                        <td class="text-right">
                            <v-icon
                                color="success"
                                class="ma-2"
                                @click="downloadSlip(slip.id)"
                            >
                                mdi-download-outline
                            </v-icon>

                            <v-icon
                                color="red lighten-1"
                                class="ma-2"
                                @click="deleteSlip(slip.id)"
                            >delete
                            </v-icon>
                            <v-icon color="info" class="ma-2" @click="openSlip(slip.id)">description
                            </v-icon>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-6" v-if="slips.length != 0">
                <v-pagination :total-visible="5" v-model="current_page" :length="last_page"
                              @input="next"></v-pagination>
            </div>
        </div>
</template>
<script>

    export default {
        created() {
            this.fetchSlips();
        },

        data() {
            return {
                slips: {},
                isLoading: false,
                current_page: 1,
                last_page: 1,
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            next(page) {
                this.fetchSlips(page);
            },
            makePagination(meta) {
                this.last_page = meta.last_page;
                this.current_page = meta.current_page;
                this.pageSize = meta.pageSize;
            },


            openSlip(id) {
                this.$router.push({
                    name: "DispatcherDashboard_preInspections_showSlip",
                    params: {id: id}
                });
            },
            downloadSlip(id){
                let url = this.BASE_URL() + `/api/dispatcher/preInspection/${id}/download`;
                let win = window.open(url, '_blank');
                win.focus();
            },
            fetchSlips(page) {
                this.isLoading = true;
                let url = this.BASE_URL() + "/api/dispatcher/preInspectionsSlips";
                let vm = this;

                if (page) {
                    url = url + "?page=" + page;
                }
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        res = res.data;
                        this.slips = res.data;
                        let meta = {};
                        meta.current_page = res.current_page
                        meta.last_page = res.last_page;
                        meta.pageSize = res.per_page;
                        vm.makePagination(meta)
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.$swal.fire("", err.response.data, "warning");
                        this.isLoading = false;

                    });
            },

            deleteSlip(id) {
                let url = this.BASE_URL() + "/api/dispatcher/preInspectionSlip/" + id;
                this.$swal
                    .fire({
                        text: "Are You Sure You Want To Delete this PreInspection slip?",
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
                                "Bearer " + this.$store.state.token_dispatcher;
                            axios
                                .delete(url)
                                .then(response => {
                                    console.log(response.data)
                                    this.$swal.fire("Deleted", response.data.message, "success");
                                })
                                .catch(error => {
                                    this.$swal.fire("Something went wrong", error.response.data.message, "error");
                                });
                            this.fetchSlips();
                        }
                    });

            }

        },
        computed: {}
    };
</script>


<style scoped>
    a:hover {
        text-decoration: none;
    }
    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }
</style>
