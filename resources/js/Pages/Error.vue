<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
      <h1 class="text-xl font-bold">Error {{ status }}</h1>
      <p class="mt-2">{{ message }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  status: Number,
  message: String,
});
</script> 