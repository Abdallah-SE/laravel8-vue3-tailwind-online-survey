<template>
    <div>
      <div class="w-90 h-100 flex items-center justify-center">
        <IconSVG class="text-red-500" fill="" viewBox="10 30 140 290"
            stroke="currentColor" height="150px" width="1000px"/>
      </div>
      
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        <router-link :to="{ name: 'Register' }"  class="font-medium text-indigo-600 hover:text-indigo-500"> 
           Not have an account
        </router-link >
      </p>
    </div>
    <form class="mt-8 space-y-6" @submit="login">
    <AlertComponent v-if="errorMSG">
      {{ errorMSG }}
      <span
        @click="errorMSG = ''"
        class="w-8 h-8 flex items-center justify-center rounded-full transition-colors cursor-pointer hover:bg-[rgba(0,0,0,0.2)]"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </span>
    </AlertComponent>
      <div class="rounded-md shadow-sm -space-y-px">
        <div>
          <label for="email-address" class="sr-only">Email address</label>
          <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          v-model="user.email" placeholder="Email address">
        </div>
        <div>
          <label for="password" class="sr-only">Password</label>
          <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
          v-model="user.password" placeholder="Password">
        </div>
      </div>
      <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember-me"
              name="remember-me"
              type="checkbox"
              v-model="user.remember"
              class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
            />
            <label for="remember-me" class="ml-2 block text-sm text-gray-900">
              Remember me
            </label>
          </div>
        </div>
      

      <div>
        <button 
            :class="{'cursor-not-allowed': loading, 'hover:bg-indigo-500': loading}"
            :disabled="loading" type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <!-- Heroicon name: solid/lock-closed -->
            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
            <svg 
                v-if="loading"
                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          Sign in
        </button>
      </div>
    </form>

</template>

<script setup>
    import AlertComponent from '../components/AlertComponent.vue';
    
    import store from '../store';
    import { useRouter } from "vue-router";
    
    import {ref} from "vue";   
 
    import IconSVG from '../assets/logosvg.svg?inline';

    let loading = ref(false);
     
    let errorMSG = ref('');
    const router = useRouter();
    const user = {
        email : '',
        password: '',
        remember: false
    }
    function login(event) {
        event.preventDefault();
        loading.value = true;
        store
        .dispatch("login", user)
        .then( ()=>{
            loading.value = false;
            console.log("Login Successfully");
            router.push({ name: "Dashboard"})
        }).catch( (error) => {  
           console.log("Login Error!");
           loading.value = false;
           console.log(error.response.data.error);
           errorMSG.value = error.response.data.error;
        })
    }
</script>








