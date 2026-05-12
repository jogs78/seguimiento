<template>

<AppLayout>

<div class="bodydiv">

    <div class="horizontal">

        <p class="subtitulo">

            <i class="fas fa-edit"></i>

            Editar Proyecto

        </p>

    </div>

    <div class="card-section">

        <div class="card-header">

            <i class="fas fa-project-diagram"></i>

            Actualiza los datos del proyecto

        </div>

        <div class="card-body">

            <form @submit.prevent="actualizarProyecto" class="form-grid">

                <div class="form-group full-width">

                    <label>Nombre</label>

                    <input
                        type="text"
                        v-model="form.nombre"
                        class="input-text"
                    >

                    <span v-if="errores.nombre" class="error">
                        {{ errores.nombre }}
                    </span>

                </div>

                <div class="form-group full-width">

                    <label>Objetivo General</label>

                    <textarea
                        v-model="form.objetivo_general"
                        class="textarea"
                    ></textarea>

                </div>

                <div class="form-group">

                    <label>Lugar</label>

                    <input
                        type="text"
                        v-model="form.lugar"
                        class="input-text"
                    >

                </div>

                <div class="form-group">

                    <label>Información</label>

                    <input
                        type="text"
                        v-model="form.informacion"
                        class="input-text"
                    >

                </div>

                <div class="form-group full-width">

                    <label>Justificación</label>

                    <textarea
                        v-model="form.justificacion"
                        class="textarea"
                    ></textarea>

                </div>

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-submit"
                        :disabled="cargando"
                    >

                        <i
                            class="fas"
                            :class="cargando
                                ? 'fa-spinner fa-pulse'
                                : 'fa-save'"
                        ></i>

                        {{ cargando
                            ? 'Actualizando...'
                            : 'Actualizar Proyecto' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</AppLayout>

</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
    proyecto: Object
})

const cargando = ref(false)

const errores = ref({})

const form = ref({

    nombre: props.proyecto.nombre,

    objetivo_general: props.proyecto.objetivo_general,

    lugar: props.proyecto.lugar,

    informacion: props.proyecto.informacion,

    justificacion: props.proyecto.justificacion,
})

const actualizarProyecto = () => {

    cargando.value = true

    errores.value = {}

    router.put(

        route('proyectos.update', props.proyecto.id),

        form.value,

        {

            preserveScroll: true,

            onSuccess: () => {

                Swal.fire({
                    icon: 'success',
                    title: 'Proyecto actualizado',
                    text: 'Los datos se actualizaron correctamente'
                })
            },

            onError: (errors) => {

                errores.value = errors

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Verifica los campos'
                })
            },

            onFinish: () => {

                cargando.value = false
            }
        }
    )
}
</script>

<style scoped>
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
  padding: 20px;
}

.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
}

.subtitulo {
  text-align: center;
  font-size: 32px;
  font-weight: bold;
  margin: 20px 0;
  color: #050E3C;
}

.subtitulo i {
  margin-right: 12px;
}

/* Tarjetas */
.card-section {
  border: 2px solid #050E3C;
  border-radius: 12px;
  margin-bottom: 30px;
  overflow: hidden;
}

.card-header {
  background-color: #050E3C;
  color: white;
  padding: 15px 20px;
  font-size: 18px;
  font-weight: bold;
}

.card-header i {
  margin-right: 10px;
}

.card-body {
  padding: 25px;
}

/* Formularios */
.form-inline {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.form-group {
  flex: 1;
  min-width: 200px;
}

.form-group.full-width {
  flex: 100%;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 5px;
  color: #333;
}

.form-group label i {
  margin-right: 5px;
}

.required {
  color: #dc3545;
}

.input-text, .select, .textarea {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
  transition: all 0.3s;
}

.input-text:focus, .select:focus, .textarea:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.textarea {
  resize: vertical;
  font-family: inherit;
}

.error {
  color: #dc3545;
  font-size: 12px;
  margin-top: 5px;
  display: block;
}

/* Sugerencias */
.sugerencias-box {
  position: absolute;
  background: white;
  border: 1px solid #ccc;
  z-index: 1000;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
  top: 100%;
  left: 0;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sugerencia-item {
  padding: 8px 12px;
  cursor: pointer;
}

.sugerencia-item:hover {
  background-color: #f0f0f0;
}

/* Radio group */
.radio-group {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-top: 5px;
}

.radio-group label {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-weight: normal;
}

/* Nueva empresa */
.nueva-empresa {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-top: 10px;
  width: 100%;
}

.nueva-empresa h4 {
  margin: 0 0 15px 0;
  color: #050E3C;
}

.nueva-empresa h4 i {
  margin-right: 8px;
}

/* Botones */
.btn-unirse, .btn-submit {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border: none;
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-unirse:hover, .btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.form-actions {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
  .subtitulo {
    font-size: 24px;
  }
  
  .form-row {
    flex-direction: column;
  }
  
  .radio-group {
    flex-direction: column;
    gap: 8px;
  }
}
</style>