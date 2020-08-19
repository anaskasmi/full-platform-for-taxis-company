import Vue from 'Vue';
import VueRouter from 'vue-router';
import EmptyRouterView from '@/js/components/EmptyRouterView/EmptyRouterView'
import LoginPage from '@/js/views/LoginPage.vue'
import AdminDashboard from '@/js/views/Dashboards/Admin/Index.vue'
//Admin profile
import AdminDashboard_profile from '@/js/views/Dashboards/Admin/profile/profile.vue'
import AdminDashboard_changePassword from '@/js/views/Dashboards/Admin/changePassword/changePassword.vue'
//drivers crud
import AdminDashboard_drivers from '@/js/views/Dashboards/Admin/driversCrud/Index.vue'
import AdminDashboard_drivers_show from '@/js/views/Dashboards/Admin/driversCrud/show.vue'
import AdminDashboard_drivers_edit from '@/js/views/Dashboards/Admin/driversCrud/edit.vue'
import AdminDashboard_drivers_add from '@/js/views/Dashboards/Admin/driversCrud/add.vue'
//drivers crud
import AdminDashboard_mlds from '@/js/views/Dashboards/Admin/mldsCrud/Index.vue'
import AdminDashboard_mlds_show from '@/js/views/Dashboards/Admin/mldsCrud/show.vue'
import AdminDashboard_mlds_edit from '@/js/views/Dashboards/Admin/mldsCrud/edit.vue'
import AdminDashboard_mlds_add from '@/js/views/Dashboards/Admin/mldsCrud/add.vue'
//directors crud
import AdminDashboard_directors from '@/js/views/Dashboards/Admin/directorsCrud/Index.vue'
import AdminDashboard_directors_show from '@/js/views/Dashboards/Admin/directorsCrud/show.vue'
import AdminDashboard_directors_edit from '@/js/views/Dashboards/Admin/directorsCrud/edit.vue'
import AdminDashboard_directors_add from '@/js/views/Dashboards/Admin/directorsCrud/add.vue'
//dispatchers crud
import AdminDashboard_dispatchers from '@/js/views/Dashboards/Admin/dispatchersCrud/Index.vue'
import AdminDashboard_dispatchers_show from '@/js/views/Dashboards/Admin/dispatchersCrud/show.vue'
import AdminDashboard_dispatchers_edit from '@/js/views/Dashboards/Admin/dispatchersCrud/edit.vue'
import AdminDashboard_dispatchers_add from '@/js/views/Dashboards/Admin/dispatchersCrud/add.vue'
//trips crud
import AdminDashboard_trips from '@/js/views/Dashboards/Admin/tripsCrud/Index.vue'
import AdminDashboard_trips_show from '@/js/views/Dashboards/Admin/tripsCrud/show.vue'
import AdminDashboard_trips_edit from '@/js/views/Dashboards/Admin/tripsCrud/edit.vue'
import AdminDashboard_trips_add from '@/js/views/Dashboards/Admin/tripsCrud/add.vue'
//reports
import AdminDashboard_reports from '@/js/views/Dashboards/Admin/reports/Index.vue'
//daily log sheet
import AdminDashboard_reports_dailyLogSheet_list from '@/js/views/Dashboards/Admin/reports/dailyLogSheet/list.vue'
import AdminDashboard_reports_dailyLogSheet_index from '@/js/views/Dashboards/Admin/reports/dailyLogSheet/index.vue'
//weekly taxi shifts
import AdminDashboard_reports_weeklyTaxiShifts_index
    from '@/js/views/Dashboards/Admin/reports/weeklyTaxiShifts/index.vue'
//weeklydriverHours
import AdminDashboard_reports_weeklydriverHours_index
    from '@/js/views/Dashboards/Admin/reports/weeklydriverHours/index.vue'
//comments crud
import AdminDashboard_comments from '@/js/views/Dashboards/Admin/commentsCrud/Index.vue'
import AdminDashboard_comments_show from '@/js/views/Dashboards/Admin/commentsCrud/show.vue'
import AdminDashboard_comments_edit from '@/js/views/Dashboards/Admin/commentsCrud/edit.vue'
import AdminDashboard_comments_add from '@/js/views/Dashboards/Admin/commentsCrud/add.vue'
//archives crud
import AdminDashboard_archives from '@/js/views/Dashboards/Admin/archives/Index.vue'
import AdminDashboard_archives_show from '@/js/views/Dashboards/Admin/archives/show.vue'
//specialShifts crud
import AdminDashboard_specialShifts from '@/js/views/Dashboards/Admin/specialShiftsCrud/Index.vue'
import AdminDashboard_specialShifts_show from '@/js/views/Dashboards/Admin/specialShiftsCrud/show.vue'
import AdminDashboard_specialShifts_edit from '@/js/views/Dashboards/Admin/specialShiftsCrud/edit.vue'
import AdminDashboard_specialShifts_add from '@/js/views/Dashboards/Admin/specialShiftsCrud/add.vue'

//batches
import AdminDashboard_batches from '@/js/views/Dashboards/Admin/batches/Index.vue'

//envelopes
import AdminDashboard_batches_envelopes from '@/js/views/Dashboards/Admin/envelopes/Index.vue'

//envelope processing
import AdminDashboard_batches_envelopes_envelope from '@/js/views/Dashboards/Admin/envelopeProcessing/Index.vue'

////////////////////////////////////////////////// DRIVER ////////////////////////////////////////////////////////////
import DriverDashboard from '@/js/views/Dashboards/Driver/Index.vue'

import DriverDashboard_profile from '@/js/views/Dashboards/Driver/profile/profile.vue'
import DriverDashboard_changePassword from '@/js/views/Dashboards/Driver/changePassword/changePassword.vue'


import DriverDashboard_trips from '@/js/views/Dashboards/Driver/tripsCrud/Index.vue'
import DriverDashboard_trips_show from '@/js/views/Dashboards/Driver/tripsCrud/show.vue'
import DriverDashboard_trips_edit from '@/js/views/Dashboards/Driver/tripsCrud/edit.vue'
import DriverDashboard_trips_add from '@/js/views/Dashboards/Driver/tripsCrud/add.vue'


import DriverDashboard_TripsOfShift_show from '@/js/views/Dashboards/Driver/monthsDetail/tripsOfShift.vue'
import DriverDashboard_ShiftsOfMonth_show from '@/js/views/Dashboards/Driver/monthsDetail/shiftsOfMonth.vue'


import DriverDashboard_reports from '@/js/views/Dashboards/Driver/reports/Index.vue'
//daily log sheet
import DriverDashboard_reports_dailyLogSheet from '@/js/views/Dashboards/Driver/reports/dailyLogSheet/index.vue'

//weeklydriverHours
import DriverDashboard_reports_weeklydriverHours_index
    from '@/js/views/Dashboards/Driver/reports/weeklydriverHours/index.vue'
//weeklydriverHours
import DriverDashboard_rotations_categories from '@/js/views/Dashboards/Driver/rotations/Categories.vue'
import DriverDashboard_rotations_vehicles from '@/js/views/Dashboards/Driver/rotations/Vehicles.vue'
import DriverDashboard_rotations_vehicleHistory from '@/js/views/Dashboards/Driver/rotations/VehicleHistory.vue'

//preinspections
import DriverDashboard_preInspections_index from '@/js/views/Dashboards/Driver/preInspections/Index.vue'
import DriverDashboard_preInspections_showSlip from '@/js/views/Dashboards/Driver/preInspections/showFile.vue'


////////////////////////////////////////////////// Director ////////////////////////////////////////////////////////////
import DirectorDashboard from '@/js/views/Dashboards/Director/Index.vue'

import DirectorDashboard_profile from '@/js/views/Dashboards/Director/profile/profile.vue'
import DirectorDashboard_changePassword from '@/js/views/Dashboards/Director/changePassword/changePassword.vue'

//comments crud
import DirectorDashboard_comments from '@/js/views/Dashboards/Director/commentsCrud/Index.vue'
import DirectorDashboard_comments_show from '@/js/views/Dashboards/Director/commentsCrud/show.vue'
import DirectorDashboard_comments_edit from '@/js/views/Dashboards/Director/commentsCrud/edit.vue'
import DirectorDashboard_comments_add from '@/js/views/Dashboards/Director/commentsCrud/add.vue'

//weekly taxi shifts
import DirectorDashboard_reports_weeklyTaxiShifts_index
    from '@/js/views/Dashboards/Director/reports/weeklyTaxiShifts/index.vue'


////////////////////////////////////////////////// Dispatcher ////////////////////////////////////////////////////////////
import DispatcherDashboard from '@/js/views/Dashboards/Dispatcher/Index.vue'

import DispatcherDashboard_profile from '@/js/views/Dashboards/Dispatcher/profile/profile.vue'
import DispatcherDashboard_changePassword from '@/js/views/Dashboards/Dispatcher/changePassword/changePassword.vue'

//drivers crud
import DispatcherDashboard_drivers from '@/js/views/Dashboards/Dispatcher/driversCrud/Index.vue'
import DispatcherDashboard_drivers_show from '@/js/views/Dashboards/Dispatcher/driversCrud/show.vue'
import DispatcherDashboard_drivers_edit from '@/js/views/Dashboards/Dispatcher/driversCrud/edit.vue'
import DispatcherDashboard_drivers_add from '@/js/views/Dashboards/Dispatcher/driversCrud/add.vue'


//specialShifts crud
import DispatcherDashboard_specialShifts from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/Index.vue'
import DispatcherDashboard_specialShifts_show from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/show.vue'
import DispatcherDashboard_specialShifts_add from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/add.vue'

import DispatcherDashboard_mySpecialShifts from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/mySpecialShifts.vue'
import DispatcherDashboard_mySpecialShifts_show from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/show.vue'
import DispatcherDashboard_mySpecialShifts_edit from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/edit.vue'
import DispatcherDashboard_mySpecialShifts_add from '@/js/views/Dashboards/Dispatcher/specialShiftsCrud/add.vue'

//accountTypes crud
import DispatcherDashboard_accountTypes from '@/js/views/Dashboards/Dispatcher/accountTypesCrud/Index.vue'

//authorization Files crud
import DispatcherDashboard_authorizationFiles from '@/js/views/Dashboards/Dispatcher/authorizationFilesCrud/Index.vue'
import DispatcherDashboard_authorizationFiles_show
    from '@/js/views/Dashboards/Dispatcher/authorizationFilesCrud/show.vue'
import DispatcherDashboard_authorizationFiles_add from '@/js/views/Dashboards/Dispatcher/authorizationFilesCrud/add.vue'
import DispatcherDashboard_authorizationFiles_edit
    from '@/js/views/Dashboards/Dispatcher/authorizationFilesCrud/edit.vue'

// rotations Manager
import DispatcherDashboard_rotationsManager from '@/js/views/Dashboards/Dispatcher/rotationsManager/Index.vue'
import DispatcherDashboard_rotations_categories
    from '@/js/views/Dashboards/Dispatcher/rotationsManager/rotationCategory.vue'
import DispatcherDashboard_rotations_byCategories_byVehicle
    from '@/js/views/Dashboards/Dispatcher/rotationsManager/rotationsByVehicleAndCategory.vue'
//rotationsCategories crud
import DispatcherDashboard_rotationsCategories from '@/js/views/Dashboards/Dispatcher/rotationsCategories/Index.vue'
//rotations
import DispatcherDashboard_rotations from '@/js/views/Dashboards/Dispatcher/rotations/Index.vue'
//cities Files crud
import DispatcherDashboard_cities from '@/js/views/Dashboards/Dispatcher/cities/Index.vue'
//vehicles Files crud
import DispatcherDashboard_vehicles from '@/js/views/Dashboards/Dispatcher/vehicles/Index.vue'
import DispatcherDashboard_vehicleHistory from '@/js/views/Dashboards/Dispatcher/vehicles/vehicleHistory.vue'
// PreInspections
import DispatcherDashboard_preInspections_index from '@/js/views/Dashboards/Dispatcher/preInspectionsCrud/Index.vue'


import {store} from './store/store';


Vue.use(VueRouter);


const router = new VueRouter(
    {
        mode: 'history',
        routes: [
            {
                path: '/',
                name: 'LoginPage',
                component: LoginPage,

            },
            {
                path: '/adminDashboard',
                component: EmptyRouterView,
                meta: {
                    requiresAdmin: true,
                },
                children:
                    [
                        {
                            path: '',
                            name: 'AdminDashboard',
                            component: AdminDashboard,
                        },

                        {
                            path: 'drivers',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_drivers',
                                        component: AdminDashboard_drivers,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_drivers_show',
                                        component: AdminDashboard_drivers_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_drivers_add',
                                        component: AdminDashboard_drivers_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_drivers_edit',
                                        component: AdminDashboard_drivers_edit,
                                    }
                                ]
                        },

                        {
                            path: 'mlds',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_mlds',
                                        component: AdminDashboard_mlds,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_mlds_show',
                                        component: AdminDashboard_mlds_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_mlds_add',
                                        component: AdminDashboard_mlds_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_mlds_edit',
                                        component: AdminDashboard_mlds_edit,
                                    }
                                ]
                        },

                        {
                            path: 'directors',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_directors',
                                        component: AdminDashboard_directors,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_directors_show',
                                        component: AdminDashboard_directors_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_directors_add',
                                        component: AdminDashboard_directors_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_directors_edit',
                                        component: AdminDashboard_directors_edit,
                                    }
                                ]
                        },


                        {
                            path: 'dispatchers',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_dispatchers',
                                        component: AdminDashboard_dispatchers,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_dispatchers_show',
                                        component: AdminDashboard_dispatchers_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_dispatchers_add',
                                        component: AdminDashboard_dispatchers_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_dispatchers_edit',
                                        component: AdminDashboard_dispatchers_edit,
                                    }
                                ]
                        },


                        {
                            path: 'trips',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_trips',
                                        component: AdminDashboard_trips,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_trips_show',
                                        component: AdminDashboard_trips_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_trips_add',
                                        component: AdminDashboard_trips_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_trips_edit',
                                        component: AdminDashboard_trips_edit,
                                    }
                                ]
                        },


                        {
                            path: 'reports',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_reports',
                                        component: AdminDashboard_reports,
                                    },
                                    {
                                        path: 'dailyLogSheet/list',
                                        name: 'AdminDashboard_reports_dailyLogSheet_list',
                                        component: AdminDashboard_reports_dailyLogSheet_list,
                                    },
                                    {
                                        path: 'dailyLogSheet/index/:id',
                                        name: 'AdminDashboard_reports_dailyLogSheet_index',
                                        component: AdminDashboard_reports_dailyLogSheet_index,
                                    },

                                    {
                                        path: 'weeklyTaxiShifts',
                                        name: 'AdminDashboard_reports_weeklyTaxiShifts_index',
                                        component: AdminDashboard_reports_weeklyTaxiShifts_index,
                                    },

                                    {
                                        path: 'weeklydriverHours',
                                        name: 'AdminDashboard_reports_weeklydriverHours_index',
                                        component: AdminDashboard_reports_weeklydriverHours_index,
                                    },


                                ]
                        },


                        {
                            path: 'specialShifts',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_specialShifts',
                                        component: AdminDashboard_specialShifts,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_specialShifts_show',
                                        component: AdminDashboard_specialShifts_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_specialShifts_add',
                                        component: AdminDashboard_specialShifts_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_specialShifts_edit',
                                        component: AdminDashboard_specialShifts_edit,
                                    }
                                ]

                        },
                        {
                            path: 'batches',
                            component: EmptyRouterView,
                            children: [
                                {
                                    path: '',
                                    name: 'AdminDashboard_batches',
                                    component: AdminDashboard_batches,
                                },
                                {
                                    path: ':batch_id',
                                    component: EmptyRouterView,
                                    children: [
                                        {
                                            path: '',
                                            name: 'AdminDashboard_batches_envelopes',
                                            component: AdminDashboard_batches_envelopes,
                                        },
                                        {
                                            path: 'envelope/:envelope_id',
                                            name: 'AdminDashboard_batches_envelopes_envelope',
                                            component: AdminDashboard_batches_envelopes_envelope,
                                        },
                                    ]


                                },

                            ]
                        },
                        {
                            path: 'comments',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_comments',
                                        component: AdminDashboard_comments,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_comments_show',
                                        component: AdminDashboard_comments_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'AdminDashboard_comments_add',
                                        component: AdminDashboard_comments_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'AdminDashboard_comments_edit',
                                        component: AdminDashboard_comments_edit,
                                    }
                                ]
                        },


                        {
                            path: 'archives',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'AdminDashboard_archives',
                                        component: AdminDashboard_archives,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'AdminDashboard_archives_show',
                                        component: AdminDashboard_archives_show,
                                    },
                                ]
                        },
                        //admin profile
                        {
                            path: 'profile',
                            name: 'AdminDashboard_profile',
                            component: AdminDashboard_profile,
                        },
                        {
                            path: 'changePassword',
                            name: 'AdminDashboard_changePassword',
                            component: AdminDashboard_changePassword,
                        },

                    ]
            },
            /////////////////////////////////// Drivers  ///////////////////////////////////////
            {
                path: '/driverDashboard',
                component: EmptyRouterView,
                meta: {
                    requiresDriver: true,
                },
                children:
                    [
                        {
                            path: '',
                            name: 'DriverDashboard',
                            component: DriverDashboard,
                        },
                        {
                            path: 'trips',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DriverDashboard_trips',
                                        component: DriverDashboard_trips,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'DriverDashboard_trips_show',
                                        component: DriverDashboard_trips_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'DriverDashboard_trips_add',
                                        component: DriverDashboard_trips_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'DriverDashboard_trips_edit',
                                        component: DriverDashboard_trips_edit,
                                    }
                                ]
                        },

                        {
                            path: 'shiftsOfMonth',
                            component: EmptyRouterView,
                            children:
                                [

                                    {
                                        path: 'show/:month/:year',
                                        name: 'DriverDashboard_ShiftsOfMonth_show',
                                        component: DriverDashboard_ShiftsOfMonth_show,
                                    },
                                ]
                        },
                        {
                            path: 'tripsOfShift',
                            component: EmptyRouterView,
                            children:
                                [

                                    {
                                        path: 'show/:id',
                                        name: 'DriverDashboard_TripsOfShift_show',
                                        component: DriverDashboard_TripsOfShift_show,
                                    },
                                ]
                        },
                        {
                            path: 'reports',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DriverDashboard_reports',
                                        component: DriverDashboard_reports,
                                    },

                                    {
                                        path: 'dailyLogSheet',
                                        name: 'DriverDashboard_reports_dailyLogSheet',
                                        component: DriverDashboard_reports_dailyLogSheet,
                                    },
                                    {
                                        path: 'weeklydriverHours',
                                        name: 'DriverDashboard_reports_weeklydriverHours_index',
                                        component: DriverDashboard_reports_weeklydriverHours_index,
                                    },
                                ]
                        },
                        {
                            path: 'rotations',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DriverDashboard_rotations_categories',
                                        component: DriverDashboard_rotations_categories,
                                    },

                                    {
                                        path: 'Vehicles/:category_id',
                                        component: EmptyRouterView,
                                        children: [
                                            {
                                                path: '',
                                                name: 'DriverDashboard_rotations_vehicles',
                                                component: DriverDashboard_rotations_vehicles,
                                            },
                                            {
                                                path: 'VehicleHistory/:vehicle_id',
                                                component: EmptyRouterView,
                                                children: [
                                                    {
                                                        path: '',
                                                        name: 'DriverDashboard_rotations_vehicleHistory',
                                                        component: DriverDashboard_rotations_vehicleHistory,
                                                    },

                                                ]
                                            },

                                        ]
                                    }
                                ]
                        },
                        {
                            path: 'profile',
                            name: 'DriverDashboard_profile',
                            component: DriverDashboard_profile,
                        },
                        {
                            path: 'changePassword',
                            name: 'DriverDashboard_changePassword',
                            component: DriverDashboard_changePassword,
                        },


                    ]
            },
            {
                path: '/preInspections',
                component: EmptyRouterView,
                children:
                    [
                        {
                            path: '',
                            name: 'DriverDashboard_preInspections_index',
                            component: DriverDashboard_preInspections_index,
                        },
                        {
                            path: 'show/:id',
                            name: 'DriverDashboard_preInspections_showSlip',
                            component: DriverDashboard_preInspections_showSlip,
                        },
                    ]
            },


            /////////////////////////////////// Director  ///////////////////////////////////////
            {
                path: '/directorDashboard',
                component: EmptyRouterView,
                meta: {
                    requiresDirector: true,
                },
                children:
                    [
                        {
                            path: '',
                            name: 'DirectorDashboard',
                            component: DirectorDashboard,
                        },
                        {
                            path: 'profile',
                            name: 'DirectorDashboard_profile',
                            component: DirectorDashboard_profile,
                        },
                        {
                            path: 'changePassword',
                            name: 'DirectorDashboard_changePassword',
                            component: DirectorDashboard_changePassword,
                        },

                        {
                            path: 'comments',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DirectorDashboard_comments',
                                        component: DirectorDashboard_comments,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'DirectorDashboard_comments_show',
                                        component: DirectorDashboard_comments_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'DirectorDashboard_comments_add',
                                        component: DirectorDashboard_comments_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'DirectorDashboard_comments_edit',
                                        component: DirectorDashboard_comments_edit,
                                    }
                                ]
                        },


                        {
                            path: 'weeklyTaxiShifts',
                            name: 'DirectorDashboard_reports_weeklyTaxiShifts_index',
                            component: DirectorDashboard_reports_weeklyTaxiShifts_index,
                        },


                    ]
            },


            /////////////////////////////////// Dispatcher  ///////////////////////////////////////
            {
                path: '/dispatcherDashboard',
                component: EmptyRouterView,
                meta: {
                    requiresDispatcher: true,
                },
                children:
                    [
                        {
                            path: '',
                            name: 'DispatcherDashboard',
                            component: DispatcherDashboard,
                        },
                        {
                            path: 'profile',
                            name: 'DispatcherDashboard_profile',
                            component: DispatcherDashboard_profile,
                        },
                        {
                            path: 'changePassword',
                            name: 'DispatcherDashboard_changePassword',
                            component: DispatcherDashboard_changePassword,
                        },

                        {
                            path: 'drivers',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_drivers',
                                        component: DispatcherDashboard_drivers,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'DispatcherDashboard_drivers_show',
                                        component: DispatcherDashboard_drivers_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'DispatcherDashboard_drivers_add',
                                        component: DispatcherDashboard_drivers_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'DispatcherDashboard_drivers_edit',
                                        component: DispatcherDashboard_drivers_edit,
                                    }
                                ]
                        },
                        {
                            path: 'specialShifts',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_specialShifts',
                                        component: DispatcherDashboard_specialShifts,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'DispatcherDashboard_specialShifts_show',
                                        component: DispatcherDashboard_specialShifts_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'DispatcherDashboard_specialShifts_add',
                                        component: DispatcherDashboard_specialShifts_add,
                                    },

                                ]
                        },

                        {
                            path: 'mySpecialShifts',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_mySpecialShifts',
                                        component: DispatcherDashboard_mySpecialShifts,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'DispatcherDashboard_mySpecialShifts_show',
                                        component: DispatcherDashboard_mySpecialShifts_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'DispatcherDashboard_mySpecialShifts_add',
                                        component: DispatcherDashboard_mySpecialShifts_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'DispatcherDashboard_mySpecialShifts_edit',
                                        component: DispatcherDashboard_mySpecialShifts_edit,
                                    }
                                ]
                        },

                        {
                            path: 'authorizationFiles',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_authorizationFiles',
                                        component: DispatcherDashboard_authorizationFiles,
                                    },

                                    {
                                        path: 'show/:id',
                                        name: 'DispatcherDashboard_authorizationFiles_show',
                                        component: DispatcherDashboard_authorizationFiles_show,
                                    },

                                    {
                                        path: 'add/',
                                        name: 'DispatcherDashboard_authorizationFiles_add',
                                        component: DispatcherDashboard_authorizationFiles_add,
                                    },

                                    {
                                        path: 'edit/:id',
                                        name: 'DispatcherDashboard_authorizationFiles_edit',
                                        component: DispatcherDashboard_authorizationFiles_edit,
                                    }
                                ]
                        },
                        {
                            path: 'rotationsManager',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_rotationsManager',
                                        component: DispatcherDashboard_rotationsManager,
                                    },

                                ]
                        },
                        {
                            path: 'rotationCategory/:id',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_rotations_categories',
                                        component: DispatcherDashboard_rotations_categories,
                                    },

                                ]
                        },
                        {
                            path: 'rotationsByVehicleAndCategory/:category_id/:vehicle_id',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_rotations_byCategories_byVehicle',
                                        component: DispatcherDashboard_rotations_byCategories_byVehicle,
                                    },

                                ]
                        },
                        {
                            path: 'rotationsCategories',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_rotationsCategories',
                                        component: DispatcherDashboard_rotationsCategories,
                                    },

                                ]
                        },
                        {
                            path: 'rotations',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_rotations',
                                        component: DispatcherDashboard_rotations,
                                    },

                                ]
                        },
                        {
                            path: 'cities',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_cities',
                                        component: DispatcherDashboard_cities,
                                    },

                                ]
                        },
                        {
                            path: 'vehicles',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_vehicles',
                                        component: DispatcherDashboard_vehicles,
                                    },
                                    {
                                        path: 'History/:id',
                                        name: 'DispatcherDashboard_vehicleHistory',
                                        component: DispatcherDashboard_vehicleHistory,
                                    },

                                ]
                        },

                        {
                            path: 'accountTypes',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_accountTypes',
                                        component: DispatcherDashboard_accountTypes,
                                    },
                                ]
                        },
                        {
                            path: 'preInspections',
                            component: EmptyRouterView,
                            children:
                                [
                                    {
                                        path: '',
                                        name: 'DispatcherDashboard_preInspections_index',
                                        component: DispatcherDashboard_preInspections_index,
                                    },
                                ]
                        },


                    ]
            },

        ]
    });

router.beforeEach((to, from, next) => {


    //driver check auth
    if (to.matched.some(record => record.meta.requiresDriver)) {


        if (!store.getters.loggedIn_driver) {
            next({
                name: 'LoginPage',
            })
        } else {
            next()
        }
    }
    //admin check auth
    else if (to.matched.some(record => record.meta.requiresAdmin)) {


        if (!store.getters.loggedIn_admin) {
            next({
                name: 'LoginPage',
            })
        } else {
            next()
        }
    }

    //director chack auth
    else if (to.matched.some(record => record.meta.requiresDirector)) {


        if (!store.getters.loggedIn_director) {
            next({
                name: 'LoginPage',
            })
        } else {
            next()
        }
    }
    //   dispatcher check auth
    else if (to.matched.some(record => record.meta.requiresDispatcher)) {


        if (!store.getters.loggedIn_dispatcher) {
            next({
                name: 'LoginPage',
            })
        } else {
            next()
        }
    } else {
        next() //dont change
    }


})


export default router;
