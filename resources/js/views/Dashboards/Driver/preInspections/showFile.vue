<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div class="wrapper_box">
                <NavbarDriver></NavbarDriver>
                <Drawer></Drawer>

                <!-- header -->
                <div class="col-12">
                    <v-btn color="grey" text tile class="float-left" @click="$router.go(-1)">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Go Back
                    </v-btn>
                </div>
                <div class="mt-8 col-12">
                    <div class="title_header text-uppercase col-6 mb-10">Show - Pre Inspection Slip</div>
                </div>
                <br/>
                <!-- progress -->
                <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                <!-- table -->
                <div class="table-responsive" v-if="!isLoading">
                    <table class="table table-hover" style="table-layout:fixed">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="w-25">Title</th>
                            <th scope="col">Value</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr class="table-info">
                            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                Driver
                            </td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">Driver Name</td>
                            <td class="text-uppercase font-weight-bold">{{slip.driverName}}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">Badge Id</td>
                            <td class="text-uppercase font-weight-bold">{{slip.ownerBadgeId}}</td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">driver Remarks</td>
                            <td class="text-uppercase font-italic text-primary">{{slip.driverRemarks}}</td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">this slip was created at</td>
                            <td class="text-uppercase font-weight-bold">{{slip.created_at}}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">this slip's last update was at</td>
                            <td class="text-uppercase font-weight-bold">{{slip.updated_at}}</td>
                        </tr>


                        <tr>
                            <td class="text-uppercase font-weight-bold">Affected Corrected</td>
                            <td v-if="slip.aboveAffectedCorrected=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.aboveAffectedCorrected}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">
                                {{slip.aboveAffectedCorrected}}
                            </td>
                        </tr>


                        <tr class="table-info">
                            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                Shift
                            </td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">date</td>
                            <td class="text-uppercase font-weight-bold">{{slip.date}}</td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">shift Type</td>
                            <td class="text-uppercase font-weight-bold">{{slip.shiftType}}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">vehicle Name</td>
                            <td class="text-uppercase font-weight-bold">{{slip.vehicle.vehicleName}}</td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">licensed Plate</td>
                            <td class="text-uppercase font-weight-bold">{{slip.licensedPlate}}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">odometer</td>
                            <td class="text-uppercase font-weight-bold">{{slip.odometer}}</td>
                        </tr>


                        <tr class="table-info">
                            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                Check List
                            </td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">glass And Mirrors</td>
                            <td v-if="slip.glassAndMirrors=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.glassAndMirrors}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.glassAndMirrors}}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">heater Defroster</td>
                            <td v-if="slip.heaterDefroster=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.heaterDefroster}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.heaterDefroster}}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">horn</td>
                            <td v-if="slip.horn=='yes'" class="text-uppercase font-weight-bold table-success">
                                {{slip.horn}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.horn}}</td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">hydraulic Brake System</td>
                            <td v-if="slip.hydraulicBrakeSystem=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.hydraulicBrakeSystem}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">
                                {{slip.hydraulicBrakeSystem}}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">interior Exterior</td>
                            <td v-if="slip.interiorExterior=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.interiorExterior}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.interiorExterior}}
                            </td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">lampsAndIndicators</td>
                            <td v-if="slip.lampsAndIndicators=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.lampsAndIndicators}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">
                                {{slip.lampsAndIndicators}}
                            </td>
                        </tr>


                        <tr>
                            <td class="text-uppercase font-weight-bold">steering Tires</td>
                            <td v-if="slip.steeringTires=='yes'" class="text-uppercase font-weight-bold table-success">
                                {{slip.steeringTires}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.steeringTires}}</td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">turn Jake</td>
                            <td v-if="slip.turnJake=='yes'" class="text-uppercase font-weight-bold table-success">
                                {{slip.turnJake}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.turnJake}}</td>
                        </tr>

                        <tr>
                            <td class="text-uppercase font-weight-bold">wheel Chair Lift And Emergency Equipment</td>
                            <td v-if="slip.wheelChairLiftAndEmergencyEquipment=='yes'"
                                class="text-uppercase font-weight-bold table-success">
                                {{slip.wheelChairLiftAndEmergencyEquipment}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">
                                {{slip.wheelChairLiftAndEmergencyEquipment}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">wheels And Hubs And Fastners</td>
                            <td v-if="slip.wheelsAndHubsAndFastners=='yes'"
                                class="text-uppercase font-weight-bold table-success">{{slip.wheelsAndHubsAndFastners}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning">
                                {{slip.wheelsAndHubsAndFastners}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-uppercase font-weight-bold">wind Shield Wipers And Washers</td>
                            <td v-if="slip.windShieldWipersAndWashers=='yes'"
                                class="text-uppercase font-weight-bold table-success">
                                {{slip.windShieldWipersAndWashers}}
                            </td>
                            <td v-else class="text-uppercase font-weight-bold table-warning ">
                                {{slip.windShieldWipersAndWashers}}
                            </td>
                        </tr>
                        <br>
                        <tr class="table-light mt-4">
                            <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                Safe to drive ?
                            </td>
                        </tr>
                        <tr style="font-size: 1.4em">
                            <td colspan="2" v-if="slip.safeToDrive=='yes'"
                                class="text-uppercase font-weight-bold table-success text-center">{{slip.safeToDrive}}
                            </td>
                            <td colspan="2" v-else class="text-uppercase font-weight-bold  table-danger text-center">
                                {{slip.safeToDrive}}
                            </td>
                        </tr>
                        </tbody>
                    </table>

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
                <div>

                    <!-- header -->
                    <v-btn color="grey" text tile class="float-left" block @click="$router.go(-1)">
                        <v-icon class="mr-2">keyboard_return</v-icon>
                        Go Back
                    </v-btn>
                    <div
                        class=" text-uppercase text-center"
                        style="font-size: 1.6em;color: rgb(124, 124, 124);"
                    >
                        Show - Pre Inspection Slip
                    </div>
                    <hr/>
                    <!-- progress -->
                    <v-progress-linear v-if="isLoading" indeterminate color="cyan"></v-progress-linear>
                    <!-- table -->
                    <div class="table-responsive" v-if="!isLoading">
                        <table class="table table-hover" style="table-layout:fixed">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col" >Title</th>
                                <th scope="col">Value</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr class="table-info">
                                <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                    Driver
                                </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">Driver Name</td>
                                <td class="text-uppercase font-weight-bold">{{slip.driverName}}</td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">Badge Id</td>
                                <td class="text-uppercase font-weight-bold">{{slip.ownerBadgeId}}</td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">driver Remarks</td>
                                <td class="text-uppercase font-italic text-primary">{{slip.driverRemarks}}</td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">this slip was created at</td>
                                <td class="text-uppercase font-weight-bold">{{slip.created_at}}</td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">this slip's last update was at</td>
                                <td class="text-uppercase font-weight-bold">{{slip.updated_at}}</td>
                            </tr>


                            <tr>
                                <td class="text-uppercase font-weight-bold">Affected Corrected</td>
                                <td v-if="slip.aboveAffectedCorrected=='yes'"
                                    class="text-uppercase font-weight-bold table-success">
                                    {{slip.aboveAffectedCorrected}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.aboveAffectedCorrected}}
                                </td>
                            </tr>


                            <tr class="table-info">
                                <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                    Shift
                                </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">date</td>
                                <td class="text-uppercase font-weight-bold">{{slip.date}}</td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">shift Type</td>
                                <td class="text-uppercase font-weight-bold">{{slip.shiftType}}</td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">vehicle Name</td>
                                <td class="text-uppercase font-weight-bold">{{slip.vehicle.vehicleName}}</td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">licensed Plate</td>
                                <td class="text-uppercase font-weight-bold">{{slip.licensedPlate}}</td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">odometer</td>
                                <td class="text-uppercase font-weight-bold">{{slip.odometer}}</td>
                            </tr>


                            <tr class="table-info">
                                <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                    Check List
                                </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">glass And Mirrors</td>
                                <td v-if="slip.glassAndMirrors=='yes'"
                                    class="text-uppercase font-weight-bold table-success">{{slip.glassAndMirrors}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.glassAndMirrors}}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">heater Defroster</td>
                                <td v-if="slip.heaterDefroster=='yes'"
                                    class="text-uppercase font-weight-bold table-success">{{slip.heaterDefroster}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.heaterDefroster}}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">horn</td>
                                <td v-if="slip.horn=='yes'" class="text-uppercase font-weight-bold table-success">
                                    {{slip.horn}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.horn}}</td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">hydraulic Brake System</td>
                                <td v-if="slip.hydraulicBrakeSystem=='yes'"
                                    class="text-uppercase font-weight-bold table-success">{{slip.hydraulicBrakeSystem}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.hydraulicBrakeSystem}}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">interior Exterior</td>
                                <td v-if="slip.interiorExterior=='yes'"
                                    class="text-uppercase font-weight-bold table-success">{{slip.interiorExterior}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.interiorExterior}}
                                </td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">lampsAndIndicators</td>
                                <td v-if="slip.lampsAndIndicators=='yes'"
                                    class="text-uppercase font-weight-bold table-success">{{slip.lampsAndIndicators}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.lampsAndIndicators}}
                                </td>
                            </tr>


                            <tr>
                                <td class="text-uppercase font-weight-bold">steering Tires</td>
                                <td v-if="slip.steeringTires=='yes'"
                                    class="text-uppercase font-weight-bold table-success">
                                    {{slip.steeringTires}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.steeringTires}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">turn Jake</td>
                                <td v-if="slip.turnJake=='yes'" class="text-uppercase font-weight-bold table-success">
                                    {{slip.turnJake}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">{{slip.turnJake}}</td>
                            </tr>

                            <tr>
                                <td class="text-uppercase font-weight-bold">wheel Chair Lift And Emergency Equipment
                                </td>
                                <td v-if="slip.wheelChairLiftAndEmergencyEquipment=='yes'"
                                    class="text-uppercase font-weight-bold table-success">
                                    {{slip.wheelChairLiftAndEmergencyEquipment}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.wheelChairLiftAndEmergencyEquipment}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">wheels And Hubs And Fastners</td>
                                <td v-if="slip.wheelsAndHubsAndFastners=='yes'"
                                    class="text-uppercase font-weight-bold table-success">
                                    {{slip.wheelsAndHubsAndFastners}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning">
                                    {{slip.wheelsAndHubsAndFastners}}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-uppercase font-weight-bold">wind Shield Wipers And Washers</td>
                                <td v-if="slip.windShieldWipersAndWashers=='yes'"
                                    class="text-uppercase font-weight-bold table-success">
                                    {{slip.windShieldWipersAndWashers}}
                                </td>
                                <td v-else class="text-uppercase font-weight-bold table-warning ">
                                    {{slip.windShieldWipersAndWashers}}
                                </td>
                            </tr>
                            <br>
                            <tr class="table-light mt-4">
                                <td colspan="2" class="text-center text-uppercase font-weight-bold">
                                    Safe to drive ?
                                </td>
                            </tr>
                            <tr style="font-size: 1.4em">
                                <td colspan="2" v-if="slip.safeToDrive=='yes'"
                                    class="text-uppercase font-weight-bold table-success text-center">
                                    {{slip.safeToDrive}}
                                </td>
                                <td colspan="2" v-else
                                    class="text-uppercase font-weight-bold  table-danger text-center">
                                    {{slip.safeToDrive}}
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
    import NavbarDriver from "@/js/components/navbars/Driver.vue";
    import Drawer from "@/js/components/drawers/Driver.vue";
    import vehicleIdentification from "@/js/views/Dashboards/Driver/preInspections/vehicleIdentification.vue";
    import filesByVehicle from "@/js/views/Dashboards/Driver/preInspections/filesByVehicle.vue";
    import filesByDriver from "@/js/views/Dashboards/Driver/preInspections/filesByDriver.vue";
    import newFile from "@/js/views/Dashboards/Driver/preInspections/newFile.vue";


    export default {
        created() {
            console.log(this.id)
            this.fetchSlip();
        },
        components: {
            NavbarDriver,
            Drawer,
            vehicleIdentification,
            filesByVehicle,
            filesByDriver,
            newFile,
        }, props: {
            id: null,
        },
        data() {
            return {
                slip: {},
                thisOpened: false,
                isLoading: false,
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
            },

            fetchSlip() {
                this.isLoading = true;
                let id = this.$route.params.id;
                let url = this.BASE_URL() + "/api/driver/preInspectionSlip/" + id;

                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.slip = res.data;
                        this.isLoading = false;
                    })
                    .catch(err => {
                        this.$swal.fire("", err.response.data, "warning");
                        this.isLoading = false;

                    });
            }

        },
        computed: {}
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

    .sentence {
        margin-top: 2em;
        font-family: "Quicksand", sans-serif;
        justify-self: center;
        font-size: 2.5em;
    }

    .fitCell{
        max-width:100%;white-space:nowrap;
    }


</style>
