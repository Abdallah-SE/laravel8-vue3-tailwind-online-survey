<template>
    <fieldset class="mb-4">
        <div>
            <legend class="text-base font-medium text-gray-900">
                {{ index + 1 }} - {{ question.question }}
            </legend>
            <p class="text-gray-300 text-sm">{{ question.description }}</p>
        </div>
        <div class="mt-3">
            <div v-if="question.type === 'select'">
                <select 
                    :value="modelValue"
                    @change="emits('update:modelValue', $event.target.value)"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300
                bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500
                focus:border-indigo-500 sm:text-sm">
                <option value="">Please Select</option>
                <option v-for="option in question.data.options" 
                    :value="option.text"
                    :key="option.uuid"
                    >
                    {{ option.text }}
                </option>
                </select>
            </div>
            
            <div v-else-if="question.type === 'radio'">
                <div
                    v-for="(option, ind) of question.data.options"
                    :key="option.uuid"
                    class="flex items-center"
                >
                <input
                    :id="option.uuid"
                    type="radio"
                    :name= "'question' + question.id"
                    :value="option.text"
                    @change="emits('update:modelValue', $emit.target.value)"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                />
                <label :for="option.uuid" class="ml-3 block text-sm font-medium text-gray-700">
                    {{ option.text }}
                </label>
                </div>
            </div>
            <div v-else-if="question.type === 'checkbox'">
                <div
                    v-for="(option, ind) in question.data.options"
                    :key="option.uuid"
                    class="flex items-center"
                >
                <input
                   :id="option.uuid"
                   v-model="model[option.text]"
                   @change="onCheckboxChange"
                   type="checkbox"
                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                />
                <label 
                    :for="option.uuid"
                    class="ml-3 block text-sm font-medium text-gray-700"> 
                    {{ option.text }}
                </label>
                </div>
            </div>
            <div v-else-if="question.type === 'text'">
                <input
                    type="text"
                    :value="modelValue"
                    @input="emits('update:modelValue', $event.target.value)"
                    class="mt-1 focus:ring-indigo-500 focus: border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-500 rounded-md" 
                />
            </div>
            <div v-else-if="question.type === 'textarea'">
                <textarea
                    type="textarea"
                    :value="modelValue"
                    @input="emits('update:modelValue', $event.target.value)"
                    class="mt-1 focus:ring-indigo-500 focus: border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-500 rounded-md" 
                 ></textarea>
            </div>
        </div>
        <hr class="mb-4" />
    </fieldset>
</template>

<script setup>
import { ref } from "vue";
const { question, index , modelValue } = defineProps({
        question: Object,
        index: Number,
        modelValue: [String, Array],
    });

const emits = defineEmits(["update:modelValue"]);

let model;
if(question.type === "checkbox"){
    model = ref({});
    
}

function onCheckboxChange ($event) {
    const selectOptions = [];
    for(let text in model.value){
        if(model.value[text]){
            selectOptions.push(text);
        }
    }
    emits("update:modelValue", selectOptions);
}
</script>
<style scoped>

</style>