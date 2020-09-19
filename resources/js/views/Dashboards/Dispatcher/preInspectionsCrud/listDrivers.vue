<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div >

                <!--Start Content-->
                <div>

                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!--End Progress-->
                    <!-- table -->
                    <div class="table-responsive shadow-sm pa-4" v-if="!isLoading">
                        <table class="table table-hover table-striped" style="table-layout:fixed">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" @click="sort('driver')" style="cursor:ns-resize	">Driver</th>
                                <th scope="col">Name</th>
                                <th scope="col" @click="sort('vehicle')" style="cursor:ns-resize	">Badge ID</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(rotation,i) in sortedRows" :key="i">
                                <td>
                                    <img
                                        v-if="rotation.driverImageUrl"
                                        :src="BASE_URL()+'/storage/'+rotation.driverImageUrl"
                                        :alt="rotation.driver"
                                        class="w-25"
                                        style="cursor:pointer; "
                                        @click="showDriver(rotation.badge_id)"
                                    />
                                    <img
                                        v-else
                                        :src="BASE_URL()+'/storage/uploads/IMAGES/man.png'"
                                        :alt="rotation.driver"
                                        class="w-25"
                                        style="cursor:pointer;"
                                        @click="showDriver(rotation.badge_id)"
                                    />
                                </td>

                                <td
                                    colspan="2"
                                    class="align-middle"
                                    @click="showDriver(rotation.badge_id)"
                                    style="cursor:pointer; color:#1e3799;"
                                >
                                    {{rotation.driver}}
                                </td>
                                <td class="text-uppercase align-middle" style="color:#01a3a4"></td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-6">
                        <v-pagination :total-visible="5" v-model="current_page" :length="last_page"
                                      @input="next"></v-pagination>
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
        beforeMount() {
            this.fetchItems();

        },
        components: {
            NavbarDispatcher,
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
                    });
            },
        },
        data() {
            return {
                current_page: 1,
                last_page: 1,
                pageSize: 0,
                cities: [],
                vehicles: [],
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
                disableAddButton: false,
                disableEditButton: false,
                rotationsManagerRoute: {name: "DispatcherDashboard_rotationsManager"},
                menu: false,
                currentSortDir: "",
                currentRotationCategory: null,
                //search inputs
                showSearchOption: false,
                searchByDate: null,
                searchByJobId: null,
                searchByBadgeId: null,
                searchByVehicleNumber: null,
                searchQuery: null,
                searchQueryArray: new Map(),
                rotationsTotalNumber: 0,
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
            next(page) {
                this.fetchRotations(page);
            },
            sort: function (s) {
                //if s == current sort, reverse
                if (s === this.currentSort) {
                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                }
                this.currentSort = s;
            },
            compare(a, b) {
                if (parseInt(a.number, 10) < parseInt(b.number, 10)) {
                    return -1;
                }
                if (parseInt(a.number, 10) > parseInt(b.number, 10)) {
                    return 1;
                }
                return 0;
            },
            fetchVehicles() {
                let url = this.BASE_URL() + "/api/dispatcher/vehicles";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;

                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.sort(this.compare);
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
                let url = this.BASE_URL() + "/api/dispatcher/cities";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
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
                let url = this.BASE_URL() + "/api/dispatcher/rotationsCategories";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        this.rotationsCategories = res.data.data;
                    })
                    .catch(error => {
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchRotations(page) {

                this.isLoading = true;
                let vm = this;
                let url = this.BASE_URL() + "/api/dispatcher/rotations?";
                if (this.searchQuery) {
                    url = url + this.searchQuery;
                }
                if (page) {
                    url = url + "&page=" + page;
                }


                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .get(url)
                    .then(res => {
                        res = res.data;
                        this.rotations = res.data;
                        if (this.rotations.length == 0) {
                            // this.searchQuery = null;
                            this.$swal("No Rotations Found!", "", "warning");
                            this.isLoading = false;
                            this.rotationsTotalNumber = 0;
                            return
                        }
                        let meta = {};
                        meta.current_page = res.current_page
                        meta.last_page = res.last_page;
                        meta.pageSize = res.per_page;
                        this.rotationsTotalNumber = res.total;
                        vm.makePagination(meta)
                        this.isLoading = false;
                    })
                    .catch(error => {
                        this.isLoading = false;
                        this.searchQuery = null;
                        console.log("error: " + error);
                        this.$swal("Something went wrong! ", "try again ", "warning");
                    });
            },

            fetchItems() {
                this.fetchVehicles();
                this.fetchCities();
                this.fetchRotationsCategories();
                this.fetchRotations();
            },
            search(searchValue, type) {
                if (searchValue == null) {
                    searchValue = "";
                    this.searchQueryArray.delete(type);
                    this.searchQuery = null;
                    for (let [key, value] of this.searchQueryArray) {
                        this.searchQuery = (this.searchQuery != null ? this.searchQuery : "") + "&" + key + "=" + value;
                    }
                } else {
                    this.searchQueryArray.delete(type);
                    this.searchQueryArray.set(type, searchValue);
                    this.searchQuery = null;
                    for (let [key, value] of this.searchQueryArray) {
                        this.searchQuery = (this.searchQuery != null ? this.searchQuery : "") + "&" + key + "=" + value;
                    }
                }
                this.fetchRotations();
                return;

            },
            clearSearchFields() {
                this.searchByDate = null;
                this.searchByJobId = null;
                this.searchByBadgeId = null;
                this.searchByVehicleNumber = null;
                this.searchQueryArray.delete("searchByDate");
                this.searchQueryArray.delete("searchByJobId");
                this.searchQueryArray.delete("searchByBadgeId");
                this.searchQueryArray.delete("searchByVehicleNumber");
                this.searchQuery = null;
                for (let [key, value] of this.searchQueryArray) {
                    this.searchQuery = (this.searchQuery != null ? this.searchQuery : "") + "&" + key + "=" + value;
                }
                this.fetchRotations();
            },
            openAddDialog() {
                this.addDialogIsOpen = true;
            },
            closeAddDialog() {
                this.addDialogIsOpen = false;
                Object.keys(this.addForm).forEach(key => (this.addForm[key] = null));
            },
            openEditDialog(rotation) {
                this.editForm = Object.assign({}, rotation);
                this.editForm.city_id = parseInt(this.editForm.city_id, 10);
                this.editDialogIsOpen = true;
            },
            closeEditDialog() {
                this.editDialogIsOpen = false;
            },
            addRotation() {
                this.disableAddButton = true;
                let url = this.BASE_URL() + "/api/dispatcher/rotation";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                axios
                    .post(url, this.addForm)
                    .then(res => {
                        this.$swal({
                            title: "Success",
                            text: "Rotation added successfully!",
                            type: "success",
                            timer: 2000
                        });
                        this.fetchItems();
                        this.closeAddDialog();
                        this.disableAddButton = false;

                    })
                    .catch(error => {
                        this.pageIsLoading = false;
                        let output = "<br><br>";
                        for (let property in error.response.data.errors) {
                            output +=
                                '<p align="left">' +
                                "*" +
                                error.response.data.errors[property] +
                                "</p>";
                        }

                        this.$swal(error.response.data.message, output, "warning");
                        this.disableAddButton = false;

                    });
            },
            editRotation() {
                this.disableEditButton = true;
                let url =
                    this.BASE_URL() + "/api/dispatcher/rotation/" + this.editForm.id;

                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_dispatcher;
                this.editForm._method = "put";
                axios
                    .post(url, this.editForm)
                    .then(res => {
                        this.$swal({
                            title: "Success",
                            text: "Rotation Updated successfully!",
                            type: "success",
                            timer: 2000
                        });
                        this.fetchItems();
                        this.closeEditDialog();
                        this.buttonLoading = false;
                        this.disableEditButton = false;

                    })
                    .catch(error => {
                        let output = "<br><br>";
                        for (let property in error.response.data.errors) {
                            output +=
                                '<p align="left">' +
                                "*" +
                                error.response.data.errors[property] +
                                "</p>";
                        }

                        this.$swal(error.response.data.message, output, "warning");
                        this.disableEditButton = false;

                    });
            },
            deleteRotation(rotation) {
                this.$swal
                    .fire({
                        text: "Are You Sure You Want To Delete This Rotation?",
                        type: "question",
                        animation: true,
                        focusConfirm: false,
                        padding: "2rem",
                        showCancelButton: true,
                        confirmButtonText: "Yes"
                    })
                    .then(res => {
                        if (res.value) {
                            let url =
                                this.BASE_URL() + "/api/dispatcher/rotation/" + rotation.id;
                            axios.defaults.headers.common["Authorization"] =
                                "Bearer " + this.$store.state.token_dispatcher;
                            axios
                                .delete(url)
                                .then(res => {
                                    this.fetchItems();
                                    this.$swal({
                                        title: "Success",
                                        text: "Rotation Deleted successfully!",
                                        type: "success",
                                        timer: 2000
                                    });
                                })
                                .catch(error => {
                                    this.$swal("Try again", error.response.data.errors, "warning");
                                });
                        }
                    });
            },
            showDriver: function (badge_id) {
                this.$router.push({
                    name: "DispatcherDashboard_drivers_show",
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

    .sentence {
        margin-top: 2em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }

</style>
