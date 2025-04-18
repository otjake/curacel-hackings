<template>
    <div class="container px-4 py-8 mx-auto">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h2 class="mb-4 text-2xl font-bold">Document Context</h2>

            <!-- Content Display -->
            <div v-if="extractedContent" class="mt-6">
                <h3 class="mb-2 text-lg font-semibold">Extracted Content</h3>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <pre class="whitespace-pre-wrap">{{ extractedContent }}</pre>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const extractedContent = ref('');
</script>
