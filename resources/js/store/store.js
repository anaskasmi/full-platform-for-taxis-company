import Vue from 'vue';
import Vuex from 'vuex';
import {isContext} from 'vm';
import {resolve} from 'path';

Vue.use(Vuex);

export const store = new Vuex.Store({

    state: {


        // BASE_URL: 'http://app.test',
        BASE_URL : 'https://mytaxioffice.com',
        //tokens
        token_driver: localStorage.getItem('access_token_driver') || null,
        token_admin: localStorage.getItem('access_token_admin') || null,
        token_director: localStorage.getItem('access_token_director') || null,
        token_dispatcher: localStorage.getItem('access_token_dispatcher') || null,
        //modals
        showAdminLogin: false,
        showDriverLogin: false,
        showDirectorLogin: false,
        showDispatcherLogin: false,
        showNavbarIndex: false,
        showNavbarAdmin: false,
        showNavbarDriver: false,
        showNavbarDirector: false,
        showNavbarDispatcher: false,
        //Drawers
        showDrawerDriver: false,

        showDrawerAdmin: false,
        showDrawerDirector: false,
        showDrawerDispatcher: false,
        //daily log sheet components
        showDay: false,
        showCurrentMonth: false,

        showPastMonth: false,
        showCurrentYear: false,
        showPastYear: false,

        //add new driver
        showNewDriverModal: false,
        //admin dashboard
        adminDashboard: {
            button: {
                text: 'Button',
                show: true,
            },
            title: {
                text: 'this is a title',
                show: true,
            }
        },

        //posSlips Information
        posSlipsInfo_batch: '',
        posSlipsInfo_month: '',
        posSlipsInfo_year: '',

        //vehicle authentication
        vehicleAuthId: '',
        vehicleAuthPassword: ''

    },
    getters: {
        loggedIn_driver(state) {
            return state.token_driver !== null;

        },
        loggedIn_admin(state) {
            return state.token_admin !== null;

        },
        loggedIn_director(state) {
            return state.token_director !== null;

        },
        loggedIn_dispatcher(state) {
            return state.token_dispatcher !== null;

        },
        //vehicle authentication
        getVehicleAuthId(state) {
            return localStorage.getItem('vehicleAuthId');


        },
        getVehicleAuthPassword(state) {
            return localStorage.getItem('vehicleAuthPassword');

        },
    },
    mutations: {

        //modals
        showAdminLogin_on: (state) => {
            state.showAdminLogin = true;
        },
        showAdminLogin_off: (state) => {
            state.showAdminLogin = false;
        },


        showDriverLogin_on: (state) => {
            state.showDriverLogin = true;
        },
        showDriverLogin_off: (state) => {
            state.showDriverLogin = false;
        },


        showDirectorLogin_on: (state) => {
            state.showDirectorLogin = true;
        },
        showDirectorLogin_off: (state) => {
            state.showDirectorLogin = false;
        },


        showDispatcherLogin_on: (state) => {
            state.showDispatcherLogin = true;
        },
        showDispatcherLogin_off: (state) => {
            state.showDispatcherLogin = false;
        },

        // navbars
        showNavbarIndex_on: (state) => {
            state.showNavbarIndex = true;
        },
        showNavbarIndex_off: (state) => {
            state.showNavbarIndex = false;
        },
        showNavbarAdmin_on: (state) => {
            state.showNavbarAdmin = true;
        },
        showNavbarAdmin_off: (state) => {
            state.showNavbarAdmin = false;
        },

        showNavbarDriver_on: (state) => {
            state.showNavbarDriver = true;
        },
        showNavbarDriver_off: (state) => {
            state.showNavbarDriver = false;
        },

        showNavbarDirector_on: (state) => {
            state.showNavbarDirector = true;
        },
        showNavbarDirector_off: (state) => {
            state.showNavbarDirector = false;
        },

        showNavbarDispatcher_on: (state) => {
            state.showNavbarDispatcher = true;
        },
        showNavbarDispatcher_off: (state) => {
            state.showNavbarDispatcher = false;
        },
        //add new driver model
        showNewDriverModal_on: (state) => {
            state.showNewDriverModal = true;
        },
        showNewDriverModal_off: (state) => {
            state.showNewDriverModal = false;
        },
        //login mutations
        retrieveToken_driver: (state, token) => {
            state.token_driver = token;
        },
        destroyToken_driver: (state) => {
            state.token_driver = null;
        },


        retrieveToken_admin: (state, token) => {
            state.token_admin = token;
        },
        destroyToken_admin: (state) => {
            state.token_admin = null;
        },


        retrieveToken_director: (state, token) => {
            state.token_director = token;
        },
        destroyToken_director: (state) => {
            state.token_director = null;
        },


        retrieveToken_dispatcher: (state, token) => {
            state.token_dispatcher = token;
        },
        destroyToken_dispatcher: (state) => {
            state.token_dispatcher = null;
        },

        showDay(state) {
            state.showDay = true;
            state.showCurrentMonth = false;
            state.showPastMonth = false;
            state.showCurrentYear = false;
            state.showPastYear = false;
        },

        showCurrentMonth(state) {
            state.showDay = false;
            state.showCurrentMonth = true;
            state.showPastMonth = false;
            state.showCurrentYear = false;
            state.showPastYear = false;
        },

        showPastMonth(state) {
            state.showDay = false;
            state.showCurrentMonth = false;
            state.showPastMonth = true;
            state.showCurrentYear = false;
            state.showPastYear = false;
        },

        showCurrentYear(state) {
            state.showDay = false;
            state.showCurrentMonth = false;
            state.showPastMonth = false;
            state.showCurrentYear = true;
            state.showPastYear = false;
        },

        showPastYear(state) {
            state.showDay = false;
            state.showCurrentMonth = false;
            state.showPastMonth = false;
            state.showCurrentYear = false;
            state.showPastYear = true;
        },
        //vehicle authentication
        setVehicleAuthId: (state, value) => {
            state.vehicleAuthId = value;
            localStorage.setItem('vehicleAuthId', value);
        },
        setVehicleAuthPassword: (state, value) => {
            state.vehicleAuthPassword = value;
            localStorage.setItem('vehicleAuthPassword', value);
        },

    },
    actions: {

        destroyToken_driver(context) {

            if (context.getters.loggedIn_driver) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token_driver;
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/driver/auth/logout').then(
                        res => {
                            localStorage.removeItem('access_token_driver');
                            context.commit('destroyToken_driver');
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            localStorage.removeItem('access_token_driver');
                            context.commit('destroyToken_driver');
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            }

        },
        destroyToken_admin(context) {

            if (context.getters.loggedIn_admin) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token_admin;
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/admin/auth/logout').then(
                        res => {
                            localStorage.removeItem('access_token_admin');
                            context.commit('destroyToken_admin');
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            localStorage.removeItem('access_token_admin');
                            context.commit('destroyToken_admin');
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            }

        },
        destroyToken_director(context) {

            if (context.getters.loggedIn_director) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token_director;
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/director/auth/logout').then(
                        res => {
                            localStorage.removeItem('access_token_director');
                            context.commit('destroyToken_director');
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            localStorage.removeItem('access_token_director');
                            context.commit('destroyToken_director');
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            }

        },
        destroyToken_dispatcher(context) {

            if (context.getters.loggedIn_dispatcher) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token_dispatcher;
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/dispatcher/auth/logout').then(
                        res => {
                            localStorage.removeItem('access_token_dispatcher');
                            context.commit('destroyToken_dispatcher');
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            localStorage.removeItem('access_token_dispatcher');
                            context.commit('destroyToken_dispatcher');
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            }

        },
        retrieveToken(context, credentials) {

            if (credentials.type == 'driver') {
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/driver/auth/login',
                        {
                            'PermitNumber': credentials.PermitNumber,
                            'password': credentials.password,
                        }).then(
                        res => {
                            const token = res.data.access_token;
                            localStorage.setItem('access_token_driver', token);
                            context.commit('retrieveToken_driver', token);
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });

            } else if (credentials.type == 'admin') {
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/admin/auth/login',
                        {
                            'email': credentials.email,
                            'password': credentials.password,
                        }).then(
                        res => {
                            const token = res.data.access_token;
                            localStorage.setItem('access_token_admin', token);
                            context.commit('retrieveToken_admin', token);
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            } else if (credentials.type == 'director') {
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/director/auth/login',
                        {
                            'email': credentials.email,
                            'password': credentials.password,
                        }).then(
                        res => {
                            const token = res.data.access_token;
                            localStorage.setItem('access_token_director', token);
                            context.commit('retrieveToken_director', token);
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            } else if (credentials.type == 'dispatcher') {
                return new Promise((resolve, reject) => {
                    axios.post(context.state.BASE_URL + '/api/dispatcher/auth/login',
                        {
                            'email': credentials.email,
                            'password': credentials.password,
                        }).then(
                        res => {
                            const token = res.data.access_token;
                            localStorage.setItem('access_token_dispatcher', token);
                            context.commit('retrieveToken_dispatcher', token);
                            resolve(res);
                        }
                    ).catch(
                        err => {
                            console.log('err : ');
                            console.log(err);
                            reject(err);
                        }
                    )
                });
            }
        },


    }
});
