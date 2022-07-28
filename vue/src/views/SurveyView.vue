<template>
   <PageComponent>
        <template v-slot:header>
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ route.params.id ? model.title : "Create Survey"}}
                </h1>
                <button
                    v-if="route.params.id"
                    type="button"
                    @click="deleteSurvey()"
                    class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-700"
                >
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 -mt-1 -ml-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>Delete Survey
                </button>
            </div>
        </template>
         <div v-if="surveyLoading" class="flex justify-center">Loading...</div>
         <form v-else  @submit.prevent="saveSurvey">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <!-- Image -->
                <div>
                  <label class="block text-sm font-medium text-gray-700">
                    Image
                  </label>
                  <div class="mt-1 flex items-center">
                    <img
                      v-if="model.image_url"
                      :src="model.image_url"
                      :alt="model.title"
                      class="w-64 h-48 object-cover"
                    />
                    <span
                      v-else
                      class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-gray-100"
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-[80%] w-[80%] text-gray-300"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </span>
                    <button
                      type="button"
                      class="relative overflow-hidden ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                      <input
                        type="file"
                        @change="onImageChoose"
                        class="absolute left-0 top-0 right-0 bottom-0 opacity-0 cursor-pointer"
                      />
                      Change
                    </button>
                  </div>
                </div>
                <!--/ Image -->

                    <div class="col-span-6">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" autocomplete="survey_title"
                            v-model="model.title"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700"> Description </label>
                        <div class="mt-1">
                          <textarea

                              id="description" name="description" rows="3"
                              v-model="model.description"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                              placeholder="Write the description of the survey" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Brief description for the  survey. URLs are hyperlinked.</p>
                    </div>

                    <div>
                        <label for="expire_date" class="block text-sm font-medium text-gray-600">Expire Date</label>
                        <input
                            type="date"
                            name="expire_date"
                            id="expire_date"
                            v-model="model.expire_date"
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-700 w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        />
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-7">
                            <input
                                type="checkbox"
                                name="status"
                                id="status"
                                v-model="model.status"
                                class="focus:ring-indigo-500 h-5 w-5 text-indigo-600 border-gray-300 rounded"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="status" class="font-medium text-gray-900">
                                Active
                            </label>
                        </div>
                    </div>
                </div>
                <hr class="my-5">
                <!-- Start Questions -->
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h3 class="text-2x1 font-semibold flex items-center justify-between">
                        Questions
                        <button
                         type="button"
                         class="flex items-center text-sm py-1 px-3 rounded-sm text-white bg-gray-700 hover:bg-gray-400"
                         @click="addQuestion()"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Question
                        </button>
                    </h3>
                    <div class="text-center text-gray-700" v-if="!model.questions.length">
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg><label for="push-nothing" class="ml-3 inline-block text-sm font-medium text-gray-700">  No Questions yet </label>

                        </button>
                   </div><hr class="my-5">
                   <div v-for="(question, index) in model.questions" :key="question.id">
                       <QuestionEditor
                          :question="question"
                          :index="index"
                          @change="questionChange"
                          @addQuestion="addQuestion"
                          @deleteQuestion="deleteQuestion"
                       />
                   </div>
                </div>
                <!-- End Questions -->
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                </div>
            </div>
        </form>
   </PageComponent>
</template>

<script setup>
import PageComponent from "../components/PageComponent.vue";
import QuestionEditor from "../components/QuestionEditor.vue";

import { computed, ref, watch} from "vue";
import store from "../store";
import { useRoute, useRouter } from "vue-router";
import { v4 as uuidv4 } from "uuid";
const route = useRoute();
const router = useRouter();

const surveyLoading = computed(() => store.state.currentSurvey.loading);

const model = ref({
    title: "",
    status: false,
    description: null,
    image: null,
    image_url: null,
    expire_date: null,
    questions: {},
});

watch(
    () => store.state.currentSurvey.data = model, (newVal, oldVal) => {
        model.value = {
           ...JSON.parse(JSON.stringify(newVal)),
           status: newVal.status !== "draft",
        };
    }
);

if(route.params.id){
    store.dispatch("getSurvey", route.params.id);
}

function onImageChoose (event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = () => {
        model.value.image = reader.result;   ///To be saved in DB backend
        model.value.image_url = reader.result;  /// To be displayed in the FRONTENT
    };
    reader.readAsDataURL(file);
}



function addQuestion(index){
    const newQuestion = {
        id: uuidv4(),
        type: "text",
        question: "",
        description: null,
        data: {},
    };
    model.value.questions.splice(index, 0, newQuestion);
}

function deleteQuestion(question){
    model.value.questions = model.value.questions.filter(
       (q) => {q !== question}
    );
}

function questionChange(question) {
  // Important to explicitelly assign question.data.options, because otherwise it is a Proxy object
  // and it is lost in JSON.stringify()
  if (question.data.options) {
    question.data.options = [...question.data.options];
  }
  model.value.questions = model.value.questions.map((q) => {
    if (q.id === question.id) {
      return JSON.parse(JSON.stringify(question));
    }
    return q;
  });
}

function saveSurvey(){
    store.dispatch("saveSurvey", model.value)
    .then( ({data}) => {
        store.commit('notify', {
            type: 'success',
            message: 'The survey successfully implemented'
        });
        router.push({
           name: "SurveyView",
           params: {id: data.data.id},
        });
    });
}


function deleteSurvey() {
    if(confirm(`Are you sure you want to delete this survey permenantly!`)){
        store.dispatch("deleteSurvey", model.value.id)
            .then( () => {
                router.push({ name: "Surveys"});
            })
            .catch( (error) => {
                console.log(error.response.data);
            });
    }
}
</script>
