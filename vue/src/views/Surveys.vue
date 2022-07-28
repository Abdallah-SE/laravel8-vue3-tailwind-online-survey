<template>
    <PageComponent>
        <template v-slot:header>
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">
                   Surveys  
                </h1>
                <router-link
                
                :to = "{ name: 'SurveyCreate' }"
                class = "bg-emerald-400 py-2 px-3 text-white rounded-md hover:bg-emerald-700"
                >
                Add Survey
                <svg 
                    xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 -mt-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                </router-link>          
            </div>
        </template>
            
            <div v-if="surveys.loading" class="flex justify-center">Loading...</div>
            
            <div v-else-if="surveys.data.length" >
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                    <SurveyListItem 
                        v-for="(survey, i) in surveys.data"
                        :key="survey.id"
                        :survey="survey"
                        class="opacity-0 animate-fade-in-down"
                        :style="{animationDelay: `${i * .3}s`}"
                        @delete="deleteSurvey(survey)"
                    />  
               </div>
               <!--Start implement pagination in FEND-->
               <div class="flex justify-center mt-5">
                   <nav
                      class="relative z-0 inline-flex justify-center rounded-md shadow-sm"
                      aria-label="Pagination" 
                   >
                       <a
                           v-for="(link, i) of surveys.links"
                           :key="i"
                           :disabled="!link.url"
                           v-html="link.label"
                           href="#"
                           @click="getForPage($event, link)"
                           aria-current="page"
                           class="relative inline-flex px-5 py-3 border text-sm text- font-medium whitespace-nowrap"
                           :class="[
                           link.active ? 'z-10-indigo-50 border-indigo-500 text-indigo-700' 
                                       : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50',
                           i === 0 ? 'rounded-l-md' : '',
                           i === surveys.links.length -1 ? 'rounded-r-md' : ''
                           
                           ]"
                       >
                           
                       </a>
                   </nav>
               </div>
              
               <!--END implement pagination in FEND-->
           </div>
           <div v-else  class="flex items-center justify-center">No surveys yet</div>
   </PageComponent>
</template>

<script setup>
import SurveyListItem from "./SurveyListItem.vue";
import PageComponent from "../components/PageComponent.vue";
 
import store from "../store";
import { computed } from "vue";

const surveys = computed( () => store.state.surveys );


store.dispatch("getSurveys");

function deleteSurvey(survey){
    if(confirm("Are you sure you want to delete this survey, deleting can't undone!")){
        store.dispatch("deleteSurvey", survey.id)
            .then( (res) => {
                store.dispatch("getSurveys");
                return res;
            })
            .catch( (error) => {
                console.log(error.response.data);
            });
    }
}

function getForPage (ev, link) {
    ev.preventDefault();
    if(link.active || !link.url){
        return;
    }
    store.dispatch("getSurveys", { url : link.url } );
}
</script>
<style scoped>

</style>