<!-- resources/js/Pages/acceso/Adentro.vue -->
<template>
  <div>
    <h1 class="centro">Sistema de seguimiento</h1>
    <br><br>
    <div style="text-align: center;">
      <img 
        :src="logoUrl" 
        alt="Logo"
        style="width: 25%; height: auto;"
      >
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const page = usePage()
const logoUrl = computed(() => {
  // Usa la URL base desde las props o construye la ruta
  return '/storage/img/logo.png'
})
</script>

<script>
// Esta es la forma de asignar layout en Vue 3 con Options API
export default {
  layout: AppLayout
}
</script>

<style scoped>
.centro {
  display: flex;
  justify-content: center;
}
</style>