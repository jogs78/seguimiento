<template>
  <div>
    <h1 class="centro">Sistema de seguimiento</h1>
    <br><br>
    <div style="text-align: center;">
      <img id="logo" src="/images/logo_tecnm_tuxtla.png"  alt="Logo" >
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const page = usePage()

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