<template>
    <div 
        class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-200 h-[500px]" 
    >
        <img :src="survey.image_url" :alt="survey.title" class="w-full h-45 object-cover" />
        <h3 class="mt-4 text-lg font-bold" > {{ survey.title }} </h3>
        <div v-html="survey.description" class="overflow-hidden flex-1" ></div>
        <div class="flex justify-between items-center mt-5">
            <router-link
                :to = "{ name: 'SurveyUpdate' , params: { id: survey.id }}"
                class="flex py-2 px-3 border border-transparent text-sm rounded-md text-white bg-indigo-700 hover:bg-indigo-900 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
 
            </router-link>
            <div class="flex items-center">
                <a 
                   :href="`/view/surveys/${survey.slug}`" 
                   target="_blank"
                   class="h-8 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-indigo-500 focus: ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                    <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                </svg>
                </a>
                <button 
                  @click="emit('delete', survey)"
                  v-if="survey.id"
                  type="button"
                  class="h-7 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-red-300 focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                </button>
            </div>
      </div>
    </div>
</template>

<script setup>

const {survey} = defineProps({
    survey: Object
})

const emit = defineEmits(["delete"]);



</script>
