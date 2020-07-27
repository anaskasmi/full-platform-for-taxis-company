<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">
            <div class="wrapper_box">
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>


                <!-- header -->
                <div class="col-12">
                    <v-btn color="grey" text tile class="float-left" :to="backRoute">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        {{currentRotationCategory}}
                    </v-btn>
                </div>
                <div class="wrapper_header mt-10">
                    <div class="title_header text-uppercase">{{currentVehicleName}} History</div>
                </div>

                <hr/>
                <!-- progress -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div class="table-responsive" v-if="!isLoading">
                    <table class="table table-hover" style="table-layout:fixed">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" @click="sort('driver')" style="cursor:ns-resize	">Driver</th>
                            <th scope="col"></th>
                            <th scope="col" @click="sort('vehicle')" style="cursor:ns-resize	">Vehicle</th>
                            <th scope="col" @click="sort('job_id')" style="cursor:ns-resize	">Job ID</th>
                            <th
                                scope="col"
                                @click="sort('rotationsCategory')"
                                style="cursor:ns-resize	"
                            >Rotation type
                            </th>
                            <th scope="col" @click="sort('city')" style="cursor:ns-resize	">City</th>
                            <th scope="col" @click="sort('type')" style="cursor:ns-resize	">Type</th>
                            <th scope="col" @click="sort('marks')" style="cursor:ns-resize	">Marks</th>
                            <th scope="col" @click="sort('date')" style="cursor:ns-resize	">Date</th>
                            <th scope="col" @click="sort('note')">Note</th>
                            <th scope="col"></th>

                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="(rotation,i) in sortedRows" :key="i">
                            <td>
                                <img
                                    v-if="rotation.driverImageUrl"
                                    :src="BASE_URL()+'/storage/'+rotation.driverImageUrl"
                                    :alt="rotation.driver"
                                    class="w-100"
                                />
                                <img
                                    v-else
                                    :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                    :alt="rotation.driver"
                                    class="w-100"
                                />
                            </td>

                            <td
                                colspan="2"
                                class="align-middle"
                                style="color:#1e3799;"
                            >

                                <template v-if="rotation.driver">
                                    <v-btn outlined v-on="on">{{rotation.driver}}</v-btn>
                                </template>


                                <v-tooltip bottom v-else>
                                    <template v-slot:activator="{ on }">
                                        <v-btn outlined v-on="on">{{rotation.badge_id}}</v-btn>
                                    </template>
                                    <span>This Driver doesnt exist try another badge ID!</span>
                                </v-tooltip>
                            </td>
                            <td class="text-uppercase align-middle" style="color:#01a3a4">{{rotation.vehicle}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.job_id}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.rotationsCategory}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.city}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.type}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.marks}}</td>
                            <td class="align-middle" style="color:#3c6382;">{{rotation.date}}</td>
                            <td colspan="2" class="font-italic align-middle" style="color:grey;">{{rotation.note}}</td>

                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <v-pagination v-model="currentPage" :length="nb_pages"></v-pagination>
                    </div>
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
            <NavbarDriver></NavbarDriver>
            <div class="container px-4">
                <div class="wrapper_box">


                    <!-- header -->
                    <div class="col-12">
                        <v-btn color="grey" text tile class="float-left" :to="backRoute">
                            <v-icon class="mr-2">keyboard_return</v-icon>
                            Back
                        </v-btn>
                    </div>


                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 2em;color: rgb(124, 124, 124);"
                    >
                        {{currentVehicleName}} History
                    </div>


                    <hr/>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table table-hover" >
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col"></th>
                                <th scope="col" @click="sort('driver')" class="fitCell align-middle">Driver</th>
                                <th scope="col"></th>
                                <th scope="col" @click="sort('vehicle')" class="fitCell align-middle">Vehicle</th>
                                <th scope="col" @click="sort('job_id')" class="fitCell align-middle">Job ID</th>
                                <th
                                    scope="col"
                                    @click="sort('rotationsCategory')"
                                    class="fitCell align-middle"
                                >Rotation type
                                </th>
                                <th scope="col" @click="sort('city')" class="fitCell align-middle">City</th>
                                <th scope="col" @click="sort('type')" class="fitCell align-middle">Type</th>
                                <th scope="col" @click="sort('marks')" class="fitCell align-middle">Marks</th>
                                <th scope="col" @click="sort('date')" class="fitCell align-middle">Date</th>
                                <th scope="col" @click="sort('note')">Note</th>
                                <th scope="col"></th>

                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(rotation,i) in sortedRows" :key="i">
                                <td>
                                    <img
                                        v-if="rotation.driverImageUrl"
                                        :src="BASE_URL()+'/storage/'+rotation.driverImageUrl"
                                        :alt="rotation.driver"
                                        style="width: 100px"
                                    />
                                    <img
                                        v-else
                                        :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                        :alt="rotation.driver"
                                        style="width: 100px"
                                    />
                                </td>

                                <td
                                    colspan="2"
                                    class="fitCell align-middle"
                                    style="color:#1e3799;"
                                >

                                    <template v-if="rotation.driver">
                                        <v-btn outlined v-on="on">{{rotation.driver}}</v-btn>
                                    </template>


                                    <v-tooltip bottom v-else>
                                        <template v-slot:activator="{ on }">
                                            <v-btn outlined v-on="on">{{rotation.badge_id}}</v-btn>
                                        </template>
                                        <span>This Driver doesnt exist try another badge ID!</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-uppercase align-middle fitCell" style="color:#01a3a4">{{rotation.vehicle}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.job_id}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.rotationsCategory}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.city}}</td>
                                <td class="align-middle fitCell"  style="color:#3c6382;">{{rotation.type}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.marks}}</td>
                                <td class="align-middle fitCell" style="color:#3c6382;">{{rotation.date}}</td>
                                <td colspan="2" class="font-italic align-middle" style="color:grey;">{{rotation.note}}
                                </td>

                            </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <v-pagination v-model="currentPage" :length="nb_pages"></v-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<script>
    import NavbarDriver from "@/js/components/navbars/Driver.vue";
    import Drawer from "@/js/components/drawers/Driver.vue";

    export default {
        beforeMount() {
            this.fetchItems();
        },
        components: {
            NavbarDriver,
            Drawer
        },
        computed: {
            sortedRows: function () {
                return this.rotations
                    .sort((a, b) => {
                        let modifier = 1;
                        if (this.currentSortDir === "desc") modifier = -1;
                        if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                        if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                        return 0;
                    })
                    .filter((row, index) => {
                        let start = (this.currentPage - 1) * this.pageSize;
                        let end = this.currentPage * this.pageSize;
                        if (index >= start && index < end) return true;
                    });
            },
            nb_pages() {
                return Math.round(this.rotations.length / this.pageSize);
            }
        },
        data() {
            return {
                cities: [],
                vehicles: [],
                currentVehicleName: "",
                currentRotationCategory: "",
                currentVehicleId: "",
                rotationsCategories: [],
                rotations: [],
                noCityfound: false,
                addDialogIsOpen: false,
                editDialogIsOpen: false,
                editedId: "",
                addForm: {},
                editForm: {},
                cityName_editForm: "",
                cityMarks_editForm: "",
                isLoading: true,
                backRoute: {
                    name: "DriverDashboard_rotations_vehicles",
                    params: {category_id: this.$route.params.category_id}
                },
                menu: false,
                currentSortDir: "",
                pageSize: 10,
                currentPage: 1
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },
            nextPage: function () {
                if (this.currentPage * this.pageSize < this.rotations.length)
                    this.currentPage++;
            },
            prevPage: function () {
                if (this.currentPage > 1) this.currentPage--;
            },
            sort: function (s) {
                //if s == current sort, reverse
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                }
                this.currentSort = s;
            },
            fetchVehicles() {
                let url = this.BASE_URL() + "/api/driver/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.forEach(vehicle => {
                            vehicle.name = vehicle.type + " " + vehicle.number;
                        });
                    })
                    .catch(error => {
                        this.$swal(
                            "Error in fetching Vehicles",
                            error.response.data.errors,
                            "warning"
                        );
                    });
            },
            fetchCities() {
                let url = this.BASE_URL() + "/api/driver/cities";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.cities = res.data.data;
                    })
                    .catch(error => {
                        this.$swal(
                            "Error in fetching Cities",
                            error.response.data.errors,
                            "warning"
                        );
                    });
            },
            fetchRotationsCategories() {
                let url = this.BASE_URL() + "/api/driver/rotationsCategories";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.rotationsCategories = res.data.data;
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchRotations() {
                let url =
                    this.BASE_URL() +
                    "/api/driver/rotationsByVehicleAndCategory/" +
                    this.$route.params.category_id + "/" + this.$route.params.vehicle_id;
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.rotations = res.data;
                        if (this.rotations.length == 0) {
                            this.$swal("No rotations found", "Sorry this car has 0 rotation in this category", "warning");
                            this.isLoading = false;
                            return;
                        }
                        this.currentVehicleName = this.rotations[0].vehicle;
                        this.currentRotationCategory = this.rotations[0].rotationsCategory;
                        this.currentVehicleId = this.rotations[0].vehicle_id;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.log(error);
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchItems() {
                this.fetchRotations();
            },
            showDriver: function (badge_id) {
                this.$router.push({
                    name: "DriverDashboard_drivers_show",
                    params: {id: badge_id}
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

</style>
