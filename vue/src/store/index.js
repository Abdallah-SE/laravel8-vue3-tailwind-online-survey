import { createStore } from "vuex";

import axiosClient from "../axios";




const store = new createStore({
    state : {
        user : {
            data: {},
            token: sessionStorage.getItem('TOKEN')
        },
        surveys : {
            loading: false,
            data: [],
            links: []
        },
        questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
        currentSurvey: {
            loading: false,
            data: {}
        },
        notification: {
            show: false,
            message: null,
            type: null
        },
        dashboard: {
            loading: false,
            data: {}
        },
    },
    getters : {},
    actions: {
        register( {commit} , user){
            return axiosClient.post('/register', user)
                    .then( ( {data} ) => {
                        commit('setUser', data);
                        return data;
                    });
        },
        login ({commit}, user){
            return axiosClient.post('/login', user)
                    .then( ( {data} ) => {
                        commit('setUser', data);
                        return data;
                    });
        },
        logout ( {commit} ) {
            return axiosClient.post("/logout")
                    .then( response => {
                        commit("logout");
                        location.reload();
                        //return response;
                    }).catch(error => {
                            console.log(error.response.data);
                            return error.response;
                        });
        },
        saveSurvey({commit}, survey){
            delete survey.image_url;
            let response;
            if(survey.id){
                response = axiosClient.put(`/survey/${survey.id}`, survey)
                        .then( (res) => {
                            commit("setCurrentSurvey", res.data);
                            return res;
                        });
            } else {
                response  = axiosClient.post("/survey", survey)
                        .then( (res) => {
                            console.log("Success before commit");
                            commit("setCurrentSurvey", res.data);
                            return res;
                        }).catch(error => {
                            console.log(error.response.data);
                        });
            }
            return response;
        },
        getSurvey ( {commit}, id) {
            commit("setCurrentSurveyLoading", true);
            return axiosClient.get(`/survey/${id}`)
                    .then((res) => {
                        commit("setCurrentSurveyLoading", false);
                        commit("setCurrentSurvey", res.data);
                        return res;
                    }).catch( (error) => {
                        commit("setCurrentSurveyLoading", false);
                        console.log(error.response.data);
                        throw error;
                    });
        },
        deleteSurvey ({}, id) {
            return axiosClient.delete(`/survey/${id}`)
                    .then( (res) => {
                        console.log(res);
                        return res;
                    })
                    .catch( (error) => {
                         console.log(error.response.data);
                    });
        },
        getSurveys ({commit}, {url=null}={}) {
            url = url || '/survey';
            commit("setSurveysLoading", true);
            return axiosClient.get(url)
                    .then( (res) => {
                        commit("setSurveys", res.data);
                        commit("setSurveysLoading", false);
                        return res;
                    })
                    .catch( (error) => {
                        console.log(error.response.data);
                    });
        },
        getSurveyBySlug ({commit}, slug) {
            commit("setCurrentSurveyLoading", true);
            return axiosClient.get(`/survey-by-slug/${slug}`)
                    .then( (res) => {
                        commit("setCurrentSurvey", res.data);
                        commit("setCurrentSurveyLoading", false);
                        return res;
                    })
                    .catch( (error) => {
                        commit("setCurrentSurveyLoading", false);
                        console.log(error.response.data);
                        throw error;
                    });
        },
        saveSurveyAnswer ({commit}, {surveyId, answers}) {
            return axiosClient.post(`/survey/${surveyId}/answer`, {answers});
        },
        getDashboardData ({commit}) {
            commit("setDashboardLoading", true);
            return axiosClient.get("/dashboard")
                    .then((res) => {
                        commit("setDashboardLoading", false);
                        commit("setDashboardData", res.data);
                        return res;
                    })
                    .catch((error) => {
                        commit("setDashboardLoading", false);
                        console.log(error.response.data);
                        throw error;
                    });
        },
    },
    mutations: {
        setDashboardLoading: (state, loading) => {
            state.dashboard.loading = loading;
        },
        setDashboardData: (state, data) => {
            state.dashboard.data = data;
        },
        logout: (state) => {
            state.user.token = null;
            state.user.data = {};
            sessionStorage.removeItem("TOKEN");
            
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem('TOKEN', userData.token);
        },
        
        setCurrentSurveyLoading: (state, loading) => {
            state.currentSurvey.loading = loading;
        },
        setSurveysLoading: (state, loading) => {
            state.surveys.loading = loading;
        },
        
        setCurrentSurvey: (state, survey) => {
            state.currentSurvey.data = survey.data;
        },
        setSurveys: (state, surveys) => {
            state.surveys.links = surveys.meta.links;
            state.surveys.data = surveys.data;
        },
        notify: (state, {message, type}) => {
            store.state.notification.show = true;
            store.state.notification.type = type;
            store.state.notification.message = message;
            
            setTimeout ( () => {
                store.state.notification.show = false;
            }, 3000);
        }
    },
    modules: {}
});

export default store;