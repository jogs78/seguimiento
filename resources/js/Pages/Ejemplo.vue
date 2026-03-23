<template>
    <div>
        
        <div class="hderecho" style="width: 100%; height: 20px; ">
            <div class="cmenor" style="width: 20%;"></div>
            <div class="cmayor" style="width: 80%; "></div>
        </div>

        <div class="horizontal">
            <p class="titulo">Inicio de Sesión</p>
        </div>
        
        <div class="horizontal">
            <div class="linea"></div>
        </div>

        <div class="centro" style="margin-top: 7%;">
            <!-- Mensajes de depuración (solo mientras hago pruebas, quitar después) -->
            <div v-if="debug" class="debug-info">
                <strong>Debug:</strong>
                <pre>Flash: {{ $page.props.flash }}</pre>
                <pre>Errores: {{ form.errors }}</pre>
            </div>

            <form @submit.prevent="submit">
                <div>
                    <label for="nombre" class="parrafo">Correo electronico: </label>
                    <div>
                        <div v-if="$page.props.flash?.errorsesion" class="alert alert-danger">
                            {{ $page.props.flash.errorsesion }}
                        </div>
                        <span class="error-message">{{ form.errors.nombre }}</span>
                    </div>
                    <input 
                        class="llenar" 
                        type="text" 
                        v-model="form.nombre" 
                        placeholder="Ingresa tu correo"
                    ><br>
                </div>

                <div style="margin-top: 20px;">
                    <label for="contra" class="parrafo">Contraseña: </label>
                    <div>
                        <div v-if="$page.props.flash?.errorcontra" class="alert alert-danger">
                            {{ $page.props.flash.errorcontra }}
                        </div>
                        <span class="error-message">{{ form.errors.contra }}</span>
                    </div>
                    <input 
                        class="llenar" 
                        type="password" 
                        v-model="form.contra" 
                        placeholder="Ingresa tu Contraseña"
                    ><br>
                </div>

                <div class="horizontal" style="margin-top: 60px;">
                    <button 
                        class="boton" 
                        type="submit" 
                        :disabled="form.processing"
                    >
                        {{ form.processing ? 'Enviando...' : 'Entrar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// Activar/desactivar modo debug
const debug = ref(true)

const form = useForm({
    nombre: '',
    contra: ''
})

const submit = () => {
    console.log('📤 Enviando formulario con datos:', form.data())
    
    form.post('/adentro', {
        preserveScroll: true,
        onStart: () => console.log('🟡 Iniciando envío...'),
        onSuccess: (page) => {
            console.log('✅ Éxito!', page)
            form.reset('contra')
        },
        onError: (errors) => {
            console.log('❌ Errores del servidor:', errors)
            console.log('📦 Flash messages:', window.$page?.props?.flash)
        },
        onFinish: () => console.log('🏁 Envío completado')
    })
}

// Exponer para depuración en consola
window.form = form
window.page = window.$page
</script>

<style>
.debug-info {
    background: #f0f0f0;
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px 0;
    font-family: monospace;
    font-size: 12px;
}
.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
    font-size: 14px;
}
.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}
</style>