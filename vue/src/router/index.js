
import { createRouter, createWebHistory } from "vue-router";

import Dashboard from "../views/Dashboard.vue"
import Surveys from "../views/Surveys.vue"
import SurveyView from "../views/SurveyView.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"
import SurveysPublicView from "../views/SurveysPublicView.vue"
import DefaultLayout from "../components/DefaultLayout.vue"
import AuthLayout from "../components/AuthLayout.vue"

import store from "../store";
const routes = [    
    {
      path: "/",
      redirect: "/dashboard",
      component: DefaultLayout,
      meta: { requiresAuth: true },
      children: [
          {path: '/dashboard', name: 'Dashboard', component: Dashboard},
          {path: '/surveys', name: 'Surveys', component: Surveys},
          {path: '/surveys/create', name: 'SurveyCreate', component: SurveyView},
          {path: '/surveys/:id', name: 'SurveyUpdate', component: SurveyView}
      ]
    },
    {
        path : "/view/surveys/:slug",
        name : "SurveysPublicView",
        component: SurveysPublicView,
    },
    {
        path: "/auth",
        redirect: "/login",
        name: "Auth",
        component: AuthLayout,
        meta: { isGuest: true },
        children : [
            {
                path: '/login',
                name: 'Login',
                component: Login
            },
            {
                path: '/register',
                name: 'Register',
                component: Register
            }
        ]
    }
    
];
const router = new createRouter({
    history : createWebHistory(),
    routes,
});


router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: "Login" });
  } else if (store.state.user.token && to.meta.isGuest) {
    next({ name: "Dashboard" });
  } else {
    next();
  }
});

export default router;
