<template>
    <PageComponent title="Dashboard" >
        <div v-if=" (data.totalSurveys || data.latestSurveys)" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-gray-300">
               <div
                class="animate-fade-in-down bg-white shadow-md p-3 text-center flex flex-col order-1 lg:order-2"
                style="animation-delay: 0.1s"
            >
                <h3
                    class="text-2xl font-semibold text-black"
                >
                    Total Surveys
                </h3>
                <div
                    class="text-9xl font-semibold flex-1 flex items-center justify-center text-black"
                >
                    {{ data.totalSurveys }}
                </div>
            </div>
            <div
                class="animate-fade-in-down bg-white shadow-md p-3 text-center flex flex-col order-2  lg:order-4"
                style="animation-delay: 0.4s"
            >
                <h3
                    class="text-2xl font-semibold text-black"
                >
                    Total Answers
                </h3>
                <div
                    v-if="data.totalAnswers"
                    class="text-9xl font-semibold flex-1 flex items-center justify-center text-black"
                >
                    {{ data.totalAnswers }}
                </div>
                <div v-else>No Answers yet</div>
            </div>
            <div
                class="animate-fade-in-down row-span-3 order-3 bg-white shadow-md p-4  lg:order-1"
                style="animation-delay: 0.7s"
            >
            <h3
                    class="text-2xl font-semibold text-black text-center mb-9"
                >
                    Latest Survey
                </h3>
                <img
                    :src="data.latestSurveys.image_url"
                    class="w-[250px] mx-auto"
                    alt=""
                />
                <h3 class="font-bold  text-xl mb-3 text-center text-black mt-5">{{ data.latestSurveys.title }}</h3>
                <div class="flex justify-between text-sm mb-1">
                    <div class="text-black">Create Date: </div>
                    <div class="text-black">{{ data.latestSurveys.created_at }}</div>
                </div>
                <div class="flex justify-between text-sm mb-3">
                    <div class="text-black">Questions: </div>
                    <div class="text-black">{{ data.latestSurveys.questions }}</div>
                </div>
                <div class="flex justify-between text-sm mb-3">
                    <div class="text-black">Answers: </div>
                    <div class="text-black">{{ data.latestSurveys.answers }}</div>
                </div>
                <div class="flex justify-between">
                    <router-link
                        :to="{ name: 'SurveyUpdate', params: { id : data.latestSurveys.id } }"
                        class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                        />
                    </svg>
                        Edit Survey
                    </router-link>
                     
                    <button
                        
                        class="flex py-2 px-4 border border-transparent text-sm rounded-md text-indigo-500 hover:bg-indigo-700 hover:text-white transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg
                          xmlns="http://www.w3.org/2000/svg"
                          class="h-5 w-5 mr-2"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                          <path
                            fill-rule="evenodd"
                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                            clip-rule="evenodd"
                          />
                        </svg>
                        View Answers
                      </button>
                </div>
            </div>    
            <div
                class="animate-fade-in-down row-span-3 order-4 bg-white shadow-md p-3  lg:order-3"
                style="animation-delay: 0.9s"
            >
                <div
                    class="flex justify-between items-center mb-3 px-2 text-black"
                >
                     <h3 class="text-2xl font-semibold text-black">Latest Answers</h3>
                        <a href="javascript:viod(0)" class="text-sm text-blue-500 hover: decoration-blue-500">
                            View All
                        </a>
                </div>
                <a
                    href="#"
                    v-if="data.latestAnswers.length"
                    v-for="answer in data.latestAnswers"
                    :key="answer.id"
                    class="block p-2 hover:bg-gray-100/90"
                >   
                    <div class="font-semibold text-black">{{ answer.survey.title }}</div>
                    <small>
                        Answer Made at:
                        <i class="font-semibold text-black">{{ answer.end_date }}</i>
                    </small>
                </a>
                <a v-else class="text-black">You don't answer any suveys</a>
            </div>
  
        </div>
        <div v-else-if="loading" class="flex justify-center">Loading...</div>
        <div v-else class="flex justify-center">You don't submit surveys yet </div>

         
    </PageComponent>
</template>

<script setup>
import PageComponent from "../components/PageComponent.vue";
 
import { useStore } from "vuex";
import { computed } from "vue";

const store = useStore();

const data = computed( () => store.state.dashboard.data);
const loading = computed( () => store.state.dashboard.loading);

store.dispatch("getDashboardData");
</script>
<style scoped>
</style>