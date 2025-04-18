<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

defineProps({
  title: String,
});
const providers = computed(() => usePage().props.providers || [])
</script>

<template>
  <section class="min-h-screen bg-gray-100 flex flex-wrap content-between">
      <Head :title="title" />
      <header class="w-full py-5 bg-white border-b">
          <div class="flex px-3 max-width items-center divide-x">
              <Link :href="route('dashboard')">
                <ApplicationLogo class="block w-56" />
              </Link>
              <div class="px-5">
                  <h4 class="text-lg">{{ title }}</h4>
              </div>
          </div>
      </header>
      <main class="w-full flex justify-center">
          <slot />
      </main>
      <footer class="w-full p-3">
          <small><span>&copy {{ new Date().getFullYear() }} Curacel</span></small>
      </footer>
  </section>
</template>
