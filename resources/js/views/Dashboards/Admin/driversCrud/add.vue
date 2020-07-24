<template>
    <div class="ma-0 pa-0">
        <div class="pa-0 ma-0 " v-if="$mq=='desktop'">

            <div>
                <NavbarAdmin></NavbarAdmin>
                <Drawer></Drawer>
                <!-- back button -->
                <div class="row col-12">
                    <v-btn color="grey" text tile  class="float-left" :to="driversRoute"> <v-icon class="mr-2">keyboard_return</v-icon>drivers </v-btn>
                </div>
                <hr>

                <div v-if="validationErrors.length">

                    <v-alert dense
                             outlined
                             type="warning"
                             border="left"
                             v-for="error in validationErrors"
                             v-bind:key="error">
                        {{error}}
                    </v-alert>
                    <hr>
                </div>

                <!-- form -->
                <form id="form" @submit.prevent="storeDriver">
                    <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                        <v-toolbar-title>Driver Information</v-toolbar-title>
                    </v-app-bar>
                    <div class="form-group">
                        <v-text-field :rules="rules" name="FirstName" v-model="driver.FirstName" label="First Name"></v-text-field>
                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules" name="LastName"  v-model="driver.LastName" label="Last Name"></v-text-field>
                    </div>

                    <div class="form-group">
                        <v-text-field :rules="rules" name="PermitNumber" v-model="driver.PermitNumber" label="permit Number"></v-text-field>
                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules"  name="LicenseNumber" v-model="driver.LicenseNumber" label="License Number"></v-text-field>

                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules" v-model="driver.HomePhone" name="HomePhone" label="Home Phone"></v-text-field>
                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules" v-model="driver.TAXI_HOST" name="TAXI_HOST" label="TAXI HOST"></v-text-field>

                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules" v-model="driver.ADDRESS" name="ADDRESS" label="ADDRESS"></v-text-field>
                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules"  v-model="driver.email" name="email" label="Email"></v-text-field>
                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules"  name="DRIVER_STATUS"  v-model="driver.DRIVER_STATUS" label="DRIVER STATUS"></v-text-field>
                    </div>


                    <div class="form-group">
                        <v-text-field :rules="rules"   name="LICENSE_CLASS"  v-model="driver.LICENSE_CLASS" label="LICENSE CLASS"></v-text-field>
                    </div>
                    <!-- dates -->
                    <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                        <v-toolbar-title>Driver Dates</v-toolbar-title>
                    </v-app-bar>
                    <!-- START DATE -->
                    <v-col cols="12" class="pa-0">
                        <v-dialog
                            ref="START_DATE"
                            v-model="modal_START_DATE"
                            :return-value.sync="START_DATE"
                            persistent
                            width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="START_DATE"
                                    label="START DATE"
                                    name="START_DATE"
                                    prepend-icon="event"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="START_DATE" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal_START_DATE = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="$refs.START_DATE.save(START_DATE)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>




                    <!-- END DATE -->
                    <v-col cols="12" class="pa-0">
                        <v-dialog
                            ref="END_DATE"
                            v-model="modal_END_DATE"
                            :return-value.sync="END_DATE"
                            persistent
                            width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="END_DATE"
                                    label="END DATE"
                                    name="END_DATE"
                                    prepend-icon="event"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="END_DATE" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal_END_DATE = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="$refs.END_DATE.save(END_DATE)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>


                    <!-- permit expiry -->
                    <v-col cols="12" class="pa-0">
                        <v-dialog
                            ref="PermitExpiry"
                            v-model="modal_PermitExpiry"
                            :return-value.sync="PermitExpiry"
                            persistent
                            width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="PermitExpiry"
                                    label="Permit Expiry"
                                    name="PermitExpiry"
                                    prepend-icon="event"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="PermitExpiry" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal_PermitExpiry = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="$refs.PermitExpiry.save(PermitExpiry)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>





                    <!-- License Expiry -->
                    <v-col cols="12" class="pa-0">
                        <v-dialog
                            ref="LicenseExpiry"
                            v-model="modal_LicenseExpiry"
                            :return-value.sync="LicenseExpiry"
                            persistent
                            width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="LicenseExpiry"
                                    label="License Expiry"
                                    name="LicenseExpiry"
                                    prepend-icon="event"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="LicenseExpiry" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal_LicenseExpiry = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="$refs.LicenseExpiry.save(LicenseExpiry)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>

                    <!-- Abstract Expiry -->
                    <v-col cols="12" class="pa-0">
                        <v-dialog
                            ref="AbstractExpiry"
                            v-model="modal_AbstractExpiry"
                            :return-value.sync="AbstractExpiry"
                            persistent
                            width="290px"
                        >
                            <template v-slot:activator="{ on }">
                                <v-text-field
                                    v-model="AbstractExpiry"
                                    label="Abstract Expiry"
                                    name="AbstractExpiry"
                                    prepend-icon="event"
                                    readonly
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker v-model="AbstractExpiry" scrollable>
                                <div class="flex-grow-1"></div>
                                <v-btn text color="primary" @click="modal_AbstractExpiry = false">Cancel</v-btn>
                                <v-btn text color="primary" @click="$refs.AbstractExpiry.save(AbstractExpiry)">OK</v-btn>
                            </v-date-picker>
                        </v-dialog>
                    </v-col>


                    <!-- files -->
                    <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                        <v-toolbar-title>Driver Files</v-toolbar-title>
                    </v-app-bar>
                    <div class="form-group">
                        <!-- <h6><label for="DRIVER_IMAGE">Driver image</label></h6> -->
                        <v-file-input
                            accept="image/png, image/jpeg, image/bmp"
                            placeholder="Driver image"
                            prepend-icon="mdi-camera"
                            name="image"
                        ></v-file-input>
                    </div>



                    <div class="form-group">
                        <!-- <h6><label for="LICENSE_PATH">License file</label></h6> -->
                        <v-file-input
                            accept="image/png, image/jpeg, image/bmp"
                            placeholder="License file"
                            prepend-icon="mdi-camera"
                            name="LICENSE_PATH"
                        ></v-file-input>
                    </div>


                    <div class="form-group">
                        <!-- <h6><label for="SP_FILE_PATH">SP file</label></h6> -->
                        <v-file-input
                            accept="image/png, image/jpeg, image/bmp"
                            placeholder="SP file"
                            prepend-icon="mdi-camera"
                            name="SP_FILE_PATH"
                        ></v-file-input>
                    </div>

                    <div class="form-group">
                        <!-- <h6><label for="PERMIT_PATH">PERMIT file</label></h6> -->
                        <v-file-input
                            accept="image/png, image/jpeg, image/bmp"
                            placeholder="PERMIT file"
                            prepend-icon="mdi-camera"
                            name="PERMIT_PATH"
                        ></v-file-input>
                    </div>

                    <div class="form-group">
                        <!-- <h6><label for="TAXIHOST_PATH">TAXIHOST file</label></h6> -->
                        <v-file-input
                            accept="image/png, image/jpeg, image/bmp"
                            placeholder="TAXIHOST file"
                            prepend-icon="mdi-camera"
                            name="TAXIHOST_PATH"
                        ></v-file-input>
                    </div>

                    <div class="form-group">
                        <!-- <h6><label for="ABSTRACT_PATH">ABSTRACT file</label></h6> -->
                        <v-file-input
                            accept="image/png, image/jpeg, image/bmp"
                            placeholder="ABSTRACT file"
                            prepend-icon="mdi-camera"
                            name="ABSTRACT_PATH"
                        ></v-file-input>
                    </div>


                    <hr>
                    <!-- assigne new password -->
                    <v-btn
                        class="pl-0"
                        color="info"
                        text
                        @click="show_password_assigne = !show_password_assigne"
                    >
                        <!-- icon -->
                        <v-icon>
                            arrow_drop_down
                        </v-icon>
                        <!-- text -->
                        Assigne Password
                    </v-btn>

                    <!-- hint -->
                    <v-chip
                        color="warning"
                        outlined
                        class="text-muted text-small ma-2 " style="font-size:0.8em;"
                    >
                        <v-icon small left>warning</v-icon>
                        If no password assigned, 'taxi-office' will be the password by default
                    </v-chip>
                    <!-- @if ($show_password_assigne) -->
                    <transition name="component-fade" mode="out-in">

                        <div v-if="show_password_assigne">
                            <v-text-field
                                v-model="driver.password"
                                :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                label="Password"
                                hint="At least 6 characters"
                                counter
                                @click:append="showPassword = !showPassword"
                            >
                            </v-text-field>


                            <v-text-field
                                :type="showPassword ? 'text' : 'password'"
                                name="password_confirmation"
                                label="Confirm Password"
                                v-model="driver.password_confirmation"
                            >
                            </v-text-field>


                        </div>
                    </transition>
                    <!-- @else

                    @endif -->
                    <!-- submit btn -->
                    <div class="row col-12 mt-4 ">
                        <v-btn color="success" tile class="float-left" type="submit">submit</v-btn>
                        <v-btn color="grey lighten-1"  dark tile  class="float-left mx-4" @click="$router.go(-1)">cancel</v-btn>
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
            <NavbarAdmin></NavbarAdmin>
            <div class="container px-4">

                <div>
                    <!-- back button -->
                    <div class="">
                        <v-btn color="grey" text tile block  class="my-4" :to="driversRoute"> <v-icon class="mr-2">keyboard_return</v-icon>drivers </v-btn>
                    </div>
                    <hr>

                    <div v-if="validationErrors.length">

                        <v-alert dense
                                 outlined
                                 type="warning"
                                 border="left"
                                 v-for="error in validationErrors"
                                 v-bind:key="error">
                            {{error}}
                        </v-alert>
                        <hr>
                    </div>

                    <!-- form -->
                    <form id="form" @submit.prevent="storeDriver">
                        <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                            <v-toolbar-title>Driver Information</v-toolbar-title>
                        </v-app-bar>
                        <div class="form-group">
                            <v-text-field :rules="rules" name="FirstName" v-model="driver.FirstName" label="First Name"></v-text-field>
                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules" name="LastName"  v-model="driver.LastName" label="Last Name"></v-text-field>
                        </div>

                        <div class="form-group">
                            <v-text-field :rules="rules" name="PermitNumber" v-model="driver.PermitNumber" label="permit Number"></v-text-field>
                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules"  name="LicenseNumber" v-model="driver.LicenseNumber" label="License Number"></v-text-field>

                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules" v-model="driver.HomePhone" name="HomePhone" label="Home Phone"></v-text-field>
                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules" v-model="driver.TAXI_HOST" name="TAXI_HOST" label="TAXI HOST"></v-text-field>

                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules" v-model="driver.ADDRESS" name="ADDRESS" label="ADDRESS"></v-text-field>
                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules"  v-model="driver.email" name="email" label="Email"></v-text-field>
                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules"  name="DRIVER_STATUS"  v-model="driver.DRIVER_STATUS" label="DRIVER STATUS"></v-text-field>
                        </div>


                        <div class="form-group">
                            <v-text-field :rules="rules"   name="LICENSE_CLASS"  v-model="driver.LICENSE_CLASS" label="LICENSE CLASS"></v-text-field>
                        </div>
                        <!-- dates -->
                        <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                            <v-toolbar-title>Driver Dates</v-toolbar-title>
                        </v-app-bar>
                        <!-- START DATE -->
                        <v-col cols="12" class="pa-0">
                            <v-dialog
                                ref="START_DATE"
                                v-model="modal_START_DATE"
                                :return-value.sync="START_DATE"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="START_DATE"
                                        label="START DATE"
                                        name="START_DATE"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="START_DATE" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_START_DATE = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.START_DATE.save(START_DATE)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>




                        <!-- END DATE -->
                        <v-col cols="12" class="pa-0">
                            <v-dialog
                                ref="END_DATE"
                                v-model="modal_END_DATE"
                                :return-value.sync="END_DATE"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="END_DATE"
                                        label="END DATE"
                                        name="END_DATE"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="END_DATE" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_END_DATE = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.END_DATE.save(END_DATE)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>


                        <!-- permit expiry -->
                        <v-col cols="12" class="pa-0">
                            <v-dialog
                                ref="PermitExpiry"
                                v-model="modal_PermitExpiry"
                                :return-value.sync="PermitExpiry"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="PermitExpiry"
                                        label="Permit Expiry"
                                        name="PermitExpiry"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="PermitExpiry" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_PermitExpiry = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.PermitExpiry.save(PermitExpiry)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>





                        <!-- License Expiry -->
                        <v-col cols="12" class="pa-0">
                            <v-dialog
                                ref="LicenseExpiry"
                                v-model="modal_LicenseExpiry"
                                :return-value.sync="LicenseExpiry"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="LicenseExpiry"
                                        label="License Expiry"
                                        name="LicenseExpiry"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="LicenseExpiry" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_LicenseExpiry = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.LicenseExpiry.save(LicenseExpiry)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>

                        <!-- Abstract Expiry -->
                        <v-col cols="12" class="pa-0">
                            <v-dialog
                                ref="AbstractExpiry"
                                v-model="modal_AbstractExpiry"
                                :return-value.sync="AbstractExpiry"
                                persistent
                                width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="AbstractExpiry"
                                        label="Abstract Expiry"
                                        name="AbstractExpiry"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="AbstractExpiry" scrollable>
                                    <div class="flex-grow-1"></div>
                                    <v-btn text color="primary" @click="modal_AbstractExpiry = false">Cancel</v-btn>
                                    <v-btn text color="primary" @click="$refs.AbstractExpiry.save(AbstractExpiry)">OK</v-btn>
                                </v-date-picker>
                            </v-dialog>
                        </v-col>


                        <!-- files -->
                        <v-app-bar dark color="#1F7087" dense  flat class="mt-12 mb-5">
                            <v-toolbar-title>Driver Files</v-toolbar-title>
                        </v-app-bar>
                        <div class="form-group">
                            <!-- <h6><label for="DRIVER_IMAGE">Driver image</label></h6> -->
                            <v-file-input
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="Driver image"
                                prepend-icon="mdi-camera"
                                name="image"
                            ></v-file-input>
                        </div>



                        <div class="form-group">
                            <!-- <h6><label for="LICENSE_PATH">License file</label></h6> -->
                            <v-file-input
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="License file"
                                prepend-icon="mdi-camera"
                                name="LICENSE_PATH"
                            ></v-file-input>
                        </div>


                        <div class="form-group">
                            <!-- <h6><label for="SP_FILE_PATH">SP file</label></h6> -->
                            <v-file-input
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="SP file"
                                prepend-icon="mdi-camera"
                                name="SP_FILE_PATH"
                            ></v-file-input>
                        </div>

                        <div class="form-group">
                            <!-- <h6><label for="PERMIT_PATH">PERMIT file</label></h6> -->
                            <v-file-input
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="PERMIT file"
                                prepend-icon="mdi-camera"
                                name="PERMIT_PATH"
                            ></v-file-input>
                        </div>

                        <div class="form-group">
                            <!-- <h6><label for="TAXIHOST_PATH">TAXIHOST file</label></h6> -->
                            <v-file-input
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="TAXIHOST file"
                                prepend-icon="mdi-camera"
                                name="TAXIHOST_PATH"
                            ></v-file-input>
                        </div>

                        <div class="form-group">
                            <!-- <h6><label for="ABSTRACT_PATH">ABSTRACT file</label></h6> -->
                            <v-file-input
                                accept="image/png, image/jpeg, image/bmp"
                                placeholder="ABSTRACT file"
                                prepend-icon="mdi-camera"
                                name="ABSTRACT_PATH"
                            ></v-file-input>
                        </div>


                        <hr>
                        <!-- assigne new password -->
                        <v-btn
                            class="pl-0"
                            color="info"
                            text
                            block
                            @click="show_password_assigne = !show_password_assigne"
                        >
                            <!-- icon -->
                            <v-icon>
                                arrow_drop_down
                            </v-icon>
                            <!-- text -->
                            Assigne Password
                        </v-btn>

                        <!-- hint -->

                        <v-alert

                            tile
                            dense
                            color="#2A3B4D"
                            elevation="2"
                            class="mt-12 font-weight-light"
                            align="center"
                        >
                            <v-icon  left class="text-white text--darken-4 mb-2 ">warning</v-icon>
                            <div class="text-white text-uppercase">
                                If no password assigned :
                                <span class="text-lowercase font-italic font-weight-bold text-info mx-1">taxi-office</span>
                                will be the password by default
                            </div>


                        </v-alert>


                        <!-- @if ($show_password_assigne) -->
                        <transition name="component-fade" mode="out-in">

                            <div v-if="show_password_assigne">
                                <v-text-field
                                    v-model="driver.password"
                                    :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    label="Password"
                                    hint="At least 6 characters"
                                    counter
                                    @click:append="showPassword = !showPassword"
                                >
                                </v-text-field>


                                <v-text-field
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password_confirmation"
                                    label="Confirm Password"
                                    v-model="driver.password_confirmation"
                                >
                                </v-text-field>


                            </div>
                        </transition>
                        <!-- @else

                        @endif -->
                        <!-- submit btn -->
                            <v-btn color="success" tile class="my-4" block type="submit">submit</v-btn>
                            <v-btn color="grey lighten-1"  dark tile block  class="" @click="$router.go(-1)">cancel</v-btn>
                    </form>

                </div>
            </div>
        </div>

    </div>




</template>
<script>
import NavbarAdmin from '@/js/components/navbars/Admin.vue';
import Drawer from '@/js/components/drawers/Admin.vue';

export default {
    components:{
        NavbarAdmin,
        Drawer,
    },
    data() {
        return {
            inputErrors:[],
            PermitExpiry: new Date().toISOString().substr(0, 10),
            modal_PermitExpiry: false,
            LicenseExpiry: new Date().toISOString().substr(0, 10),
            modal_LicenseExpiry: false,
            AbstractExpiry: new Date().toISOString().substr(0, 10),
            modal_AbstractExpiry: false,
            START_DATE: new Date().toISOString().substr(0, 10),
            modal_START_DATE: false,
            END_DATE: new Date().toISOString().substr(0, 10),
            modal_END_DATE: false,
            show_password_assigne : false,
            showPassword:false,
            driversRoute:{name : 'AdminDashboard_drivers'},
            driver:{
            FirstName:'',
            LastName:'',
            PermitNumber:'',
            LicenseNumber:'',
            PermitExpiry:'',
            LicenseExpiry:'',
            AbstractExpiry:'',
            HomePhone:'',
            TAXI_HOST:'',
            ADDRESS:'',
            email:'',
            START_DATE:'',
            END_DATE:'',
            DRIVER_STATUS:'',
            LICENSE_CLASS:'',
            password:'',
            password_confirmation:'',
            }


        }
    },
    methods: {
        BASE_URL()
      {
            return this.$store.state.BASE_URL;
      },
        storeDriver(){
            var form = document.getElementById('form');
            var formData = new FormData(form);
            axios.defaults.headers.common['Authorization'] = 'Bearer '+ this.$store.state.token_admin;
axios.post(this.BASE_URL()+'/api/admin/driver',formData)
            .then(response => {
                this.$router.push({name:'AdminDashboard_drivers_show',params:{id:response.data.data.PermitNumber}})

            })
            .catch(error => {
                this.inputErrors = error.response.data.errors;
                // window.scrollTo(0,0);
                 $('html, body').animate({scrollTop:0},'200');

            });

        },

    },
    computed:{
                validationErrors(){
                    let errors = Object.values(this.inputErrors);
                    errors = errors.flat();
                    return errors;
                }

    }
}
</script>


<style scoped>
    .component-fade-enter-active, .component-fade-leave-active {
    transition: opacity .4s ease;
    }
    .component-fade-enter, .component-fade-leave-to
    /* .component-fade-leave-active below version 2.1.8 */ {
    opacity: 0;
    }
    a:hover{
      text-decoration: none;
    }
</style>
