<template>
    <!--contain all html page-->
    <div class="ma-0 pa-0">
        <div v-if="$mq=='desktop'" class="ma-0 pa-0">
            <div>


                <!-- form -->
                <form id="form" @submit.prevent="storePreInspectionSlip">
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >Shift type
                    </v-alert>
                    <br>
                    <div class="text-center my-5">

                        <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                            <input class="form-check-input" type="radio" id="Radios1" value="day"
                                   v-model="slipData.shiftType">
                            <label class="form-check-label" for="Radios1">
                                Day Shift
                            </label>
                        </div>
                        <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                            <input class="form-check-input" type="radio" id="Radios2" value="night"
                                   v-model="slipData.shiftType">
                            <label class="form-check-label" for="Radios2">
                                Night Shift
                            </label>
                        </div>

                    </div>
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >Shift Info
                    </v-alert>
                    <!-- Row 2 date & car no   -->
                    <div class="form-group row col-12 px-0 mx-0">

                        <!-- date -->
                        <v-col cols="6" class="pl-0 float-left">
                            <v-dialog
                                ref="dateDate"
                                v-model="modal_date"
                                :return-value.sync="slipData.date"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        outlined

                                        v-model="slipData.date"

                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="slipData.date" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_date = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.dateDate.save(slipData.date)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>

                        <!-- car no  -->
                        <v-col cols="6" class="pr-0 mr-0 float-right">

                            <v-text-field outlined label="Licensed plat No" v-model="slipData.licensedPlate" type="text"
                            ></v-text-field>
                        </v-col>
                    </div>


                    <!-- Row 3 Licensed plate & badge ID   -->
                    <div class="form-group row col-12 px-0 mx-0">
                        <!--Licensed plate-->

                        <v-select-search
                            class="w-100 align-middle"
                            label="name"
                            placeholder="Car No"
                            v-model="slipData.VehicleId"
                            :options="vehicles"
                            :reduce="vehicle => vehicle.id"
                        ></v-select-search>
                    </div>

                    <!-- Odometer at all starting shift  -->
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >Odometer at all starting shift
                    </v-alert>
                    <br>
                    <div class="text-center my-5">

                        <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                            <input class="form-check-input" type="radio" id="KmID" value="km"
                                   v-model="slipData.odometer">
                            <label class="form-check-label" for="KmID">
                                KM
                            </label>
                        </div>
                        <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                            <input class="form-check-input" type="radio" id="MillesID" value="mille"
                                   v-model="slipData.odometer">
                            <label class="form-check-label" for="MillesID">
                                Milles
                            </label>
                        </div>

                    </div>

                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12"
                        align="center"
                    >Check List
                    </v-alert>
                    <br>


                    <!----------------------------------->
                    <!--           Check List          -->
                    <!----------------------------------->

                    <div class="table-responsive mx-auto" style="width: 40%">
                        <table class="table table-hover table-striped table-hover">
                            <tbody>
                            <!--Interior exterior-->
                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">Interior/ Exterior
                                </td>
                                <td class="float-right">

                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="Interior_exterior_true_id"
                                               value="yes"
                                               v-model="slipData.interiorExterior">
                                        <label class="form-check-label" for="Interior_exterior_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="Interior_exterior_false_id"
                                               value="No"
                                               v-model="slipData.interiorExterior">
                                        <label class="form-check-label" for="Interior_exterior_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--lAMPS AND INDICATORS-->
                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    lamps & Indicators
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="lamp_indicator_true_id"
                                               value="yes"
                                               v-model="slipData.lampsAndIndicators">
                                        <label class="form-check-label" for="lamp_indicator_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="lamp_indicator_false_id"
                                               value="No"
                                               v-model="slipData.lampsAndIndicators">
                                        <label class="form-check-label" for="lamp_indicator_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--STEERING TIRES-->
                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    STEERING TIRES
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="steering_tires_true_id"
                                               value="yes"
                                               v-model="slipData.steeringTires">
                                        <label class="form-check-label" for="steering_tires_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="steering_tires_false_id"
                                               value="No"
                                               v-model="slipData.steeringTires">
                                        <label class="form-check-label" for="steering_tires_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--WHEELS HUBS FASTNERS-->
                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    WHEELS / HUBS / FASTNERS
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="wheel_true_id"
                                               value="yes"
                                               v-model="slipData.wheelsAndHubsAndFastners">
                                        <label class="form-check-label" for="wheel_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="wheel_false_id"
                                               value="No"
                                               v-model="slipData.wheelsAndHubsAndFastners">
                                        <label class="form-check-label" for="wheel_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--GLASS AND MIRRORS-->
                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    Glass & mirrors
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="glass_true_id"
                                               value="yes"
                                               v-model="slipData.glassAndMirrors">
                                        <label class="form-check-label" for="glass_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="glass_false_id"
                                               value="No"
                                               v-model="slipData.glassAndMirrors">
                                        <label class="form-check-label" for="glass_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--HYDRAULIC brake system -->

                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    Hydraulic brake system
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="brake_true_id"
                                               value="yes"
                                               v-model="slipData.hydraulicBrakeSystem">
                                        <label class="form-check-label" for="brake_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="brake_false_id"
                                               value="No"
                                               v-model="slipData.hydraulicBrakeSystem">
                                        <label class="form-check-label" for="brake_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--Heater Defroster -->

                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    Heater Defroster
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="heater_true_id"
                                               value="yes"
                                               v-model="slipData.heaterDefroster">
                                        <label class="form-check-label" for="heater_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="heater_false_id"
                                               value="No"
                                               v-model="slipData.heaterDefroster">
                                        <label class="form-check-label" for="heater_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>

                            <!--WindShield wipers & washers -->

                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    Windshield Wipers & Washers
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="windShield_true_id"
                                               value="yes"
                                               v-model="slipData.windShieldWipersAndWashers">
                                        <label class="form-check-label" for="windShield_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="windShield_false_id"
                                               value="No"
                                               v-model="slipData.windShieldWipersAndWashers">
                                        <label class="form-check-label" for="windShield_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--horn -->

                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    Horn
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="horn_true_id"
                                               value="yes"
                                               v-model="slipData.horn">
                                        <label class="form-check-label" for="horn_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="horn_false_id"
                                               value="No"
                                               v-model="slipData.horn">
                                        <label class="form-check-label" for="horn_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--wheel chair lift/emergency equipment  -->

                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                    Wheel chair lift / Emergency equipment
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="chairLift_true_id"
                                               value="yes"
                                               v-model="slipData.wheelChairLiftAndEmergencyEquipment">
                                        <label class="form-check-label" for="chairLift_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio"
                                               id="chairLift_false_id"
                                               value="No"
                                               v-model="slipData.wheelChairLiftAndEmergencyEquipment">
                                        <label class="form-check-label" for="chairLift_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--trunk jack -->

                            <tr>
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">Trunk / Jack
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="trunk_true_id"
                                               value="yes"
                                               v-model="slipData.turnJake">
                                        <label class="form-check-label" for="trunk_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="trunk_false_id"
                                               value="No"
                                               v-model="slipData.turnJake">
                                        <label class="form-check-label" for="trunk_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <!--                            safe to drive-->
                            <tr class="table-info">
                                <td class="align-middle font-weight-bold " style="font-size: 1rem">Safe to drive
                                </td>
                                <td class="float-right">


                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="safe_to_drive_true_id"
                                               value="yes"
                                               v-model="slipData.safeToDrive">
                                        <label class="form-check-label" for="safe_to_drive_true_id">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline "
                                         style="font-size: 1.3rem">
                                        <input class="form-check-input" type="radio" id="safe_to_drive_false_id"
                                               value="No"
                                               v-model="slipData.safeToDrive">
                                        <label class="form-check-label" for="safe_to_drive_false_id">
                                            No
                                        </label>

                                    </div>
                                </td>
                            </tr>
                            <tr class="table-info">

                                <td colspan="2" class="font-italic text-center">
                                    Note : If the car is "Unsafe to Drive", Please let the dispatcher know immediately
                                    and hand in this pre-trip sheet to the dispatcher.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="font-weight-bold text-center text-uppercase text-danger"
                                    style="font-size:1.6em">
                                    Dont drive the vehicle until it is fixed

                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <v-alert
                        border="left"
                        tile
                        dense
                        color="#1F7087"
                        dark
                        elevation="2"
                        class="mt-12 "
                        align="center"

                    >Driver's Remarks
                    </v-alert>
                    <br>
                    <!--driver remarks-->
                    <v-textarea
                        rounded
                        class="w-100"
                        outlined
                        label="Driver's Remarks..."
                        auto-grow
                        v-model="slipData.driverRemarks"
                    ></v-textarea>
                    <v-text-field
                        outlined
                        label="Driver Name"
                        v-model="slipData.driverName"
                        value="slipData.driverName"
                    ></v-text-field>
                    <v-container fluid class="card bg-light mt-12">
                        <div class=" font-weight-bold" style="font-size: 1.6em">
                            <input class=" "
                                   v-model="slipData.aboveAffectedCorrected"
                                   type="radio" name="exampleRadios"
                                   id="aboveAffectedCorrected" value="yes">
                            <label class="form-check-label text-uppercase text-primary " for="aboveAffectedCorrected">
                                above Affected Corrected
                            </label>
                        </div>
                        <div class=" font-weight-bold" style="font-size: 1.6em">
                            <input class="" type="radio"
                                   v-model="slipData.aboveAffectedCorrected"
                                   name="exampleRadios" id="aboveAffectedNeedsToBeCorrected" value="No" checked>
                            <label class="form-check-label text-uppercase text-secondary"
                                   for="aboveAffectedNeedsToBeCorrected">
                                above Affected Needs To Be Corrected for safe operation of vehicle
                            </label>
                        </div>

                    </v-container>
                    <div class="card w-75 pa-8 ma-6 mx-auto shadow" v-if="errors">
                        <div class="card-header font-weight-bold text-uppercase text-center text-secondary mb-4"
                             style="font-size: 1.6em">
                            <div>
                                Correct the following errors
                            </div>
                        </div>
                        <div v-for="error in errors">
                            <v-alert v-for="message in error" type="warning" outlined class=" mb-1 font-weight-bold">
                                {{
                                message
                                }}
                            </v-alert>
                        </div>
                    </div>

                    <!-- submit btn -->
                    <div class="row col-12 mt-4">
                        <v-btn color="success" tile type="submit" block>submit</v-btn>
                    </div>
                </form>
            </div>
        </div>


        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->
        <!----------------------------Mobile Version---------------------------->
        <!---------------------------------------------------------------------->
        <!---------------------------------------------------------------------->

        <div class="pa-0 ma-0 " v-if="$mq=='mobile'">
            <!-- nav -->
            <div class="">
                <div>


                    <!-- form -->
                    <form id="form" @submit.prevent="storePreInspectionSlip">
                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >Shift type
                        </v-alert>
                        <br>
                        <div class="text-center my-5">

                            <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                                <input class="form-check-input" type="radio" id="Radios1" value="day"
                                       v-model="slipData.shiftType">
                                <label class="form-check-label" for="Radios1">
                                    Day Shift
                                </label>
                            </div>
                            <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                                <input class="form-check-input" type="radio" id="Radios2" value="night"
                                       v-model="slipData.shiftType">
                                <label class="form-check-label" for="Radios2">
                                    Night Shift
                                </label>
                            </div>

                        </div>
                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >Shift Info
                        </v-alert>
                        <!-- Row 2 date & car no   -->
                        <div class="form-group row col-12 px-0 mx-0">

                            <!-- date -->
                            <v-col cols="6" class="pl-0 float-left">
                                <v-dialog
                                    ref="dateDate"
                                    v-model="modal_date"
                                    :return-value.sync="slipData.date"
                                    persistent
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            outlined

                                            v-model="slipData.date"

                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="slipData.date" scrollable>
                                        <div class="flex-grow-1"></div>
                                        <v-btn text color="primary" @click="modal_date = false">Cancel</v-btn>
                                        <v-btn text color="primary" @click="$refs.dateDate.save(slipData.date)">OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-col>

                            <!-- car no  -->
                            <v-col cols="6" class="pr-0 mr-0 float-right">

                                <v-text-field outlined label="Licensed plat No" v-model="slipData.licensedPlate"
                                              type="text"
                                ></v-text-field>
                            </v-col>
                        </div>


                        <!-- Row 3 Licensed plate & badge ID   -->
                        <div class="form-group row col-12 px-0 mx-0">
                            <!--Licensed plate-->

                            <v-select-search
                                class="w-100 align-middle"
                                label="name"
                                placeholder="Car No"
                                v-model="slipData.VehicleId"
                                :options="vehicles"
                                :reduce="vehicle => vehicle.id"
                            ></v-select-search>
                        </div>

                        <!-- Odometer at all starting shift  -->
                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >Odometer at all starting shift
                        </v-alert>
                        <br>
                        <div class="text-center my-5">

                            <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                                <input class="form-check-input" type="radio" id="KmID" value="km"
                                       v-model="slipData.odometer">
                                <label class="form-check-label" for="KmID">
                                    KM
                                </label>
                            </div>
                            <div class="form-check form-check-inline font-weight-bold" style="font-size: 1.3rem">
                                <input class="form-check-input" type="radio" id="MillesID" value="mille"
                                       v-model="slipData.odometer">
                                <label class="form-check-label" for="MillesID">
                                    Milles
                                </label>
                            </div>

                        </div>

                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12"
                            align="center"
                        >Check List
                        </v-alert>
                        <br>


                        <!----------------------------------->
                        <!--           Check List          -->
                        <!----------------------------------->

                        <div class="table-responsive mx-auto">
                            <table class="table table-hover table-striped table-hover">
                                <tbody>
                                <!--Interior exterior-->
                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">Interior/
                                        Exterior
                                    </td>
                                    <td class="float-right">

                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="Interior_exterior_true_id"
                                                   value="yes"
                                                   v-model="slipData.interiorExterior">
                                            <label class="form-check-label" for="Interior_exterior_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="Interior_exterior_false_id"
                                                   value="No"
                                                   v-model="slipData.interiorExterior">
                                            <label class="form-check-label" for="Interior_exterior_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--lAMPS AND INDICATORS-->
                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        lamps & Indicators
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="lamp_indicator_true_id"
                                                   value="yes"
                                                   v-model="slipData.lampsAndIndicators">
                                            <label class="form-check-label" for="lamp_indicator_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="lamp_indicator_false_id"
                                                   value="No"
                                                   v-model="slipData.lampsAndIndicators">
                                            <label class="form-check-label" for="lamp_indicator_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--STEERING TIRES-->
                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        STEERING TIRES
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="steering_tires_true_id"
                                                   value="yes"
                                                   v-model="slipData.steeringTires">
                                            <label class="form-check-label" for="steering_tires_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="steering_tires_false_id"
                                                   value="No"
                                                   v-model="slipData.steeringTires">
                                            <label class="form-check-label" for="steering_tires_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--WHEELS HUBS FASTNERS-->
                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        WHEELS / HUBS / FASTNERS
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="wheel_true_id"
                                                   value="yes"
                                                   v-model="slipData.wheelsAndHubsAndFastners">
                                            <label class="form-check-label" for="wheel_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="wheel_false_id"
                                                   value="No"
                                                   v-model="slipData.wheelsAndHubsAndFastners">
                                            <label class="form-check-label" for="wheel_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--GLASS AND MIRRORS-->
                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        Glass & mirrors
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="glass_true_id"
                                                   value="yes"
                                                   v-model="slipData.glassAndMirrors">
                                            <label class="form-check-label" for="glass_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="glass_false_id"
                                                   value="No"
                                                   v-model="slipData.glassAndMirrors">
                                            <label class="form-check-label" for="glass_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--HYDRAULIC brake system -->

                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        Hydraulic brake system
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="brake_true_id"
                                                   value="yes"
                                                   v-model="slipData.hydraulicBrakeSystem">
                                            <label class="form-check-label" for="brake_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="brake_false_id"
                                                   value="No"
                                                   v-model="slipData.hydraulicBrakeSystem">
                                            <label class="form-check-label" for="brake_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--Heater Defroster -->

                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        Heater Defroster
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="heater_true_id"
                                                   value="yes"
                                                   v-model="slipData.heaterDefroster">
                                            <label class="form-check-label" for="heater_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="heater_false_id"
                                                   value="No"
                                                   v-model="slipData.heaterDefroster">
                                            <label class="form-check-label" for="heater_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>

                                <!--WindShield wipers & washers -->

                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        Windshield Wipers & Washers
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="windShield_true_id"
                                                   value="yes"
                                                   v-model="slipData.windShieldWipersAndWashers">
                                            <label class="form-check-label" for="windShield_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="windShield_false_id"
                                                   value="No"
                                                   v-model="slipData.windShieldWipersAndWashers">
                                            <label class="form-check-label" for="windShield_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--horn -->

                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        Horn
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="horn_true_id"
                                                   value="yes"
                                                   v-model="slipData.horn">
                                            <label class="form-check-label" for="horn_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="horn_false_id"
                                                   value="No"
                                                   v-model="slipData.horn">
                                            <label class="form-check-label" for="horn_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--wheel chair lift/emergency equipment  -->

                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">
                                        Wheel chair lift / Emergency equipment
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="chairLift_true_id"
                                                   value="yes"
                                                   v-model="slipData.wheelChairLiftAndEmergencyEquipment">
                                            <label class="form-check-label" for="chairLift_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio"
                                                   id="chairLift_false_id"
                                                   value="No"
                                                   v-model="slipData.wheelChairLiftAndEmergencyEquipment">
                                            <label class="form-check-label" for="chairLift_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--trunk jack -->

                                <tr>
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">Trunk / Jack
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="trunk_true_id"
                                                   value="yes"
                                                   v-model="slipData.turnJake">
                                            <label class="form-check-label" for="trunk_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="trunk_false_id"
                                                   value="No"
                                                   v-model="slipData.turnJake">
                                            <label class="form-check-label" for="trunk_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <!--                            safe to drive-->
                                <tr class="table-info">
                                    <td class="align-middle font-weight-bold " style="font-size: 1rem">Safe to drive
                                    </td>
                                    <td class="float-right">


                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="safe_to_drive_true_id"
                                                   value="yes"
                                                   v-model="slipData.safeToDrive">
                                            <label class="form-check-label" for="safe_to_drive_true_id">
                                                YES
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline "
                                             style="font-size: 1.3rem">
                                            <input class="form-check-input" type="radio" id="safe_to_drive_false_id"
                                                   value="No"
                                                   v-model="slipData.safeToDrive">
                                            <label class="form-check-label" for="safe_to_drive_false_id">
                                                No
                                            </label>

                                        </div>
                                    </td>
                                </tr>
                                <tr class="table-info">

                                    <td colspan="2" class="font-italic text-center">
                                        Note : If the car is "Unsafe to Drive", Please let the dispatcher know
                                        immediately
                                        and hand in this pre-trip sheet to the dispatcher.
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="font-weight-bold text-center text-uppercase text-danger"
                                        style="font-size:1.6em">
                                        Dont drive the vehicle until it is fixed

                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <v-alert
                            border="left"
                            tile
                            dense
                            color="#1F7087"
                            dark
                            elevation="2"
                            class="mt-12 "
                            align="center"

                        >Driver's Remarks
                        </v-alert>
                        <br>
                        <!--driver remarks-->
                        <v-textarea
                            rounded
                            class="w-100"
                            outlined
                            label="Driver's Remarks..."
                            auto-grow
                            v-model="slipData.driverRemarks"
                        ></v-textarea>
                        <v-text-field
                            outlined
                            label="Driver Name"
                            v-model="slipData.driverName"
                            value="slipData.driverName"
                        ></v-text-field>
                        <v-container fluid class="card bg-light mt-12">
                            <div class="form-check form-check-inline my-5 font-weight-bold " >
                                    <input class="align-middle form-check-input"
                                           style=""
                                           v-model="slipData.aboveAffectedCorrected"
                                           type="radio" name="exampleRadios"
                                           id="aboveAffectedCorrected" value="yes">
                                    <label class="form-check-label text-uppercase text-primary ml-3 "
                                           for="aboveAffectedCorrected">
                                        above Affected Corrected
                                    </label>
                            </div>
                            <div class=" form-check form-check-inline my-5 font-weight-bold"    >
                                <input class="form-check-input" type="radio"
                                       v-model="slipData.aboveAffectedCorrected"
                                       name="exampleRadios" id="aboveAffectedNeedsToBeCorrected" value="No" checked>
                                <label class="form-check-label text-uppercase text-secondary ml-3"
                                       for="aboveAffectedNeedsToBeCorrected">
                                    above Affected Needs To Be Corrected for safe operation of vehicle
                                </label>
                            </div>

                        </v-container>
                        <div class="card  pa-4 ma-3 mx-auto shadow" v-if="errors">
                            <div class="card-header font-weight-bold text-uppercase text-center text-secondary mb-4"
                                 style="font-size: 1em">
                                <div>
                                    Correct the following errors
                                </div>
                            </div>
                            <div v-for="error in errors">
                                <v-alert v-for="message in error" type="warning" outlined
                                         class=" mb-3 font-weight-bold">
                                    {{
                                    message
                                    }}
                                </v-alert>
                            </div>
                        </div>

                        <!-- submit btn -->
                        <div class="row col-12 mt-4">
                            <v-btn color="success" tile type="submit" block>submit</v-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</template>
<script>

    export default {
        created() {
            this.fetchVehicles();
            this.fetchCurrentDriver();

        },
        data() {
            return {
                vehicles: [],
                errors: null,
                isLoading: false,
                modal_date: false,
                currentDriverName: "",
                isStoring: false,
                slipData: {
                    date: this.$moment().format("YYYY-MM-DD"),
                    driverName: "",
                },
            };
        },
        methods: {
            BASE_URL() {
                return this.$store.state.BASE_URL;
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
                this.isLoading = true;

                let url = this.BASE_URL() + "/api/driver/vehiclesWithNames";
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;
                axios
                    .get(url)
                    .then(res => {
                        this.vehicles = res.data;
                        this.vehicles.sort(this.compare);
                        this.isLoading = false;
                        if (!this.vehicles) {
                            this.$swal("No vehicles found!", "", "warning");
                        }
                    })
                    .catch(error => {
                        console.log(error)
                        this.$swal("Try again", error.response.data.errors, "warning");
                    });
            },
            fetchCurrentDriver() {
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;

                axios
                    .get(this.BASE_URL() + "/api/driver/auth/user")
                    .then(res => {

                        this.slipData.driverName = res.data.FirstName + " " + res.data.LastName;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            storePreInspectionSlip() {
                this.isStoring = true;
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + this.$store.state.token_driver;

                axios
                    .post(this.BASE_URL() + "/api/driver/storePreInspection", this.slipData)
                    .then(res => {
                        this.isStoring = false;

                        this.$swal('created successfully', '', 'success');
                        this.$router.push({
                            name: "DriverDashboard_preInspections_showSlip",
                            params: {id: res.data.id}
                        });
                    })
                    .catch(error => {
                        console.log(error);
                        this.isStoring = false;

                        try {
                            this.errors = error.response.data.errors;
                            this.$swal("Try again", error.response.data.message, "error");

                        } catch (err) {
                            this.$swal("Something Went wrong", "try again", "warning");
                        }


                    });
            }
        }

    }
    ;
</script>


<style scoped>

    a:hover {
        text-decoration: none;
    }
</style>
