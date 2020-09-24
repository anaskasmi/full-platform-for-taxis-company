<template>
    <div class="ma-0 pa-0">

        <div v-if="driverWasPicked">
            <v-btn block outlined color="blue" class="my-6" @click="backToDriverList()">
                Back
            </v-btn>
            <FilesByDriver :badgeId="pickedDriverId"/>
        </div>
        <div v-else>
            <div>
                <!--Start Content-->
                <div>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!--End Progress-->
                    <!-- table -->
                    <div class="table-responsive " v-if="!isLoading">
                        <div class="row ">
                            <div class="sentence mx-auto pb-4">Pick a Driver</div>
                        </div>
                        <!-- search start -->
                        <div class="row">
                            <div class="col-md-12 md-form active-cyan-2 mb-3">
                                <form @submit.prevent="search(searchValue)">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <button id="button-addon7" type="submit" class="btn btn-dark">
                                                <!-- <i class="fa fa-search"></i> -->
                                                <v-icon dark small>search</v-icon>
                                            </button>
                                        </div>
                                        <input
                                            type="search"
                                            name="searchValue"
                                            v-model="searchValue"
                                            placeholder="Search by name or badge ID"
                                            aria-describedby="button-addon7"
                                            class="form-control"
                                        />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- search end -->
                        <br>
                        <br>

                        <table class="table table-hover table-striped" style="table-layout:fixed">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="cursor:ns-resize	">Driver</th>
                                <th scope="col">Name</th>
                                <th scope="col" style="cursor:ns-resize	">Badge ID</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(driver,i) in drivers" :key="i">
                                <td>
                                    <img
                                        v-if="driver.image"
                                        :src="BASE_URL()+'/storage/'+driver.image"
                                        :alt="driver.driver"
                                        class="w-25"
                                        style="cursor:pointer; "
                                    />
                                    <img
                                        v-else
                                        :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                        :alt="driver.driver"
                                        class="w-25"
                                        style="cursor:pointer;"
                                    />
                                </td>

                                <td
                                    class="text-uppercase align-middle font-weight-medium"
                                    style="cursor:pointer;"
                                >
                                    {{driver.FirstName}} {{driver.LastName}}
                                </td>
                                <td class="text-uppercase align-middle font-weight-medium"> {{driver.PermitNumber}}</td>
                                <td class="align-middle" @click="showFilesOfDriver(driver.PermitNumber)">
                                    <v-btn large outlined rounded color="success" class="float-right">
                                        OPEN
                                    </v-btn>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


</template>
<script>
    import NavbarDispatcher from "@/js/components/navbars/Dispatcher.vue";
    import Drawer from "@/js/components/drawers/Dispatcher.vue";
    import FilesByDriver from "@/js/views/Dashboards/Dispatcher/preInspectionsCrud/filesByDriver.vue";

    export default {
        beforeMount() {
            this.fetchDrivers();

        },
        components: {
            NavbarDispatcher,
            Drawer,
            FilesByDriver
        },
        computed: {
            sortedRows: function () {
                return this.drivers
                    .sort((a, b) => {
                        let modifier = 1;
                        if (this.currentSortDir === "desc") modifier = -1;
                        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                        return 0;
                    });
            },
        },
        data() {
            return {
                drivers: {},
                isLoading: true,
                currentSortDir: "",
                //search inputs
                showSearchOption: false,
                searchByJobId: null,
                searchByBadgeId: null,
                //search
                searchValue: "",
                pickedDriverId: "",
                driverWasPicked: false,
            };
        },
        methods: {

            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            makePagination(meta) {
                this.last_page = meta.last_page;
                this.current_page = meta.current_page;
                this.pageSize = meta.pageSize;
            },

            sort: function (s) {
                //if s == current sort, reverse
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                }
                this.currentSort = s;
            },

            fetchDrivers() {

                this.isLoading = true;
                let url = this.BASE_URL() + "/api/dispatcher/drivers";


                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        res = res.data;
                        this.drivers = res.data;
                        if (this.drivers.length == 0) {
                            // this.searchQuery = null;
                            this.$swal("No Drivers Found!", "", "warning");
                            this.isLoading = false;
                            return
                        }

                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.log("error: " + error);
                        this.$swal("Something went wrong! ", "try again ", "warning");
                    });
            },
            search(searchValue) {

                if (searchValue == "") {
                    this.fetchDrivers();
                    return;
                }

                this.isLoading = true;
                let url = this.BASE_URL() + "/api/dispatcher/drivers/search";


                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(
                        this.BASE_URL() + "/api/dispatcher/drivers/search?searchValue=" +
                        searchValue
                    )
                    .then(res => {
                        res = res.data;
                        this.drivers = res.data;
                        if (this.drivers.length == 0) {
                            // this.searchQuery = null;
                            this.$swal("No Drivers Found!", "", "warning");
                            this.isLoading = false;
                            return
                        }

                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        console.log("error: " + error);
                        this.$swal("Something went wrong! ", "try again ", "warning");
                    });
            },
            showFilesOfDriver: function (badge_id) {
                this.driverWasPicked = true;
                this.pickedDriverId = badge_id;
            },
            backToDriverList: function () {
                this.driverWasPicked = false;
                this.pickedDriverId = "";
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
        grid-template-columns: 10fr 4fr;
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

    .fitCell {
        max-width: 100%;
        white-space: nowrap;
    }

    .sentence {
        margin-top: 2em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }

</style>
