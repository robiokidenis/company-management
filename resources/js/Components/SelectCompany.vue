<template>
    <select
      v-model="selectedCompany"
      ref="input"
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
    >
      <option value="" disabled>Select a Company</option>
      <option v-for="company in companies" :key="company.id" :value="company.id">
        {{ company.name }}
      </option>
    </select>
  </template>
  
  <script setup lang="ts">
  import { watch } from "vue";
import { ref, defineProps, onMounted, defineEmits, defineExpose } from "vue";
  
  const { modelValue, companies } = defineProps(["modelValue", "companies"]);
  const selectedCompany = ref(modelValue);
  
  const emit = defineEmits();
  
  const input = ref<HTMLSelectElement | null>(null);
  
  onMounted(() => {
    if (input.value?.hasAttribute("autofocus")) {
      input.value?.focus();
    }
  });
  
  defineExpose({ focus: () => input.value?.focus() });
  
  watch(() => selectedCompany.value, (newValue) => {
    emit("update:modelValue", newValue);
  });
  </script>
  