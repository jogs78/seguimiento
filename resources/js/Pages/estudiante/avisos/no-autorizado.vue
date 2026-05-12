<template>
  <AppLayout>
    <div class="aviso-container">
      <div class="aviso-card error">
        <div class="aviso-icon">
          <i class="fas fa-ban"></i>
        </div>
        <div class="aviso-content">
          <h1 class="aviso-titulo">
            <i class="fas fa-exclamation-triangle"></i>
            Acceso No Autorizado
          </h1>
          
          <p class="aviso-mensaje">
            {{ razon || 'No tienes permiso para realizar esta acción.' }}
          </p>
          
          <div class="info-box" v-if="razon">
            <i class="fas fa-info-circle"></i>
            <div class="info-text">
              <strong>Motivo:</strong> {{ razon }}
            </div>
          </div>
          
          <div class="countdown-container">
            <div class="countdown-timer">
              <i class="fas fa-hourglass-half"></i>
              <span class="countdown-text">{{ countdownMessage }}</span>
            </div>
            <div class="progress-bar">
              <div 
                class="progress-fill" 
                :style="{ width: progressPercent + '%' }"
              ></div>
            </div>
          </div>
          
          <div class="acciones">
            <button @click="redirigirAhora" class="btn-redirigir">
              <i class="fas fa-arrow-right"></i>
              Redirigir ahora
            </button>
            <Link :href="route('home')" class="btn-volver">
              <i class="fas fa-home"></i>
              Ir al Inicio
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  razon: {
    type: String,
    default: 'No tienes permisos suficientes para acceder a esta sección.'
  },
  segundos: {
    type: Number,
    default: 10
  }
})

// Estado del contador
const secondsLeft = ref(props.segundos)
let countdownInterval = null

// Mensaje del contador
const countdownMessage = computed(() => {
  if (secondsLeft.value <= 0) {
    return 'Redirigiendo...'
  }
  return `Serás redirigido en ${secondsLeft.value} segundo${secondsLeft.value !== 1 ? 's' : ''}...`
})

// Porcentaje de progreso
const progressPercent = computed(() => {
  return (secondsLeft.value / props.segundos) * 100
})

// Redirigir ahora
const redirigirAhora = () => {
  if (countdownInterval) {
    clearInterval(countdownInterval)
  }
  router.visit(route('home'))
}

// Iniciar contador
onMounted(() => {
  countdownInterval = setInterval(() => {
    if (secondsLeft.value <= 1) {
      clearInterval(countdownInterval)
      router.visit(route('home'))
    } else {
      secondsLeft.value--
    }
  }, 1000)
})

// Limpiar intervalo al desmontar
onUnmounted(() => {
  if (countdownInterval) {
    clearInterval(countdownInterval)
  }
})
</script>

<style scoped>
.aviso-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 70vh;
  padding: 20px;
}

.aviso-card {
  max-width: 550px;
  width: 100%;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.2);
  background: white;
  transition: transform 0.3s ease;
}

.aviso-card.error {
  border-top: 6px solid #dc3545;
}

.aviso-icon {
  background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
  padding: 30px;
  text-align: center;
}

.aviso-icon i {
  font-size: 80px;
  color: #dc3545;
}

.aviso-content {
  padding: 30px;
}

.aviso-titulo {
  font-size: 26px;
  font-weight: bold;
  margin-bottom: 15px;
  color: #721c24;
  text-align: center;
}

.aviso-titulo i {
  margin-right: 10px;
}

.aviso-mensaje {
  font-size: 16px;
  color: #555;
  margin-bottom: 20px;
  line-height: 1.6;
  text-align: center;
}

.info-box {
  display: flex;
  gap: 12px;
  background: #f8f9fa;
  padding: 15px 18px;
  border-radius: 12px;
  margin-bottom: 25px;
  border-left: 4px solid #dc3545;
}

.info-box i {
  font-size: 20px;
  color: #dc3545;
}

.info-text {
  flex: 1;
  font-size: 14px;
  color: #555;
}

.info-text strong {
  display: block;
  margin-bottom: 5px;
  color: #333;
}

.countdown-container {
  margin-bottom: 25px;
}

.countdown-timer {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-bottom: 10px;
}

.countdown-timer i {
  font-size: 20px;
  color: #dc3545;
}

.countdown-text {
  font-size: 16px;
  font-weight: 500;
  color: #dc3545;
}

.progress-bar {
  height: 6px;
  background-color: #e9ecef;
  border-radius: 3px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #dc3545, #ff6b6b);
  border-radius: 3px;
  transition: width 1s linear;
}

.acciones {
  display: flex;
  gap: 15px;
}

.btn-redirigir,
.btn-volver {
  flex: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
  font-size: 15px;
}

.btn-redirigir {
  background-color: #dc3545;
  color: white;
}

.btn-redirigir:hover {
  background-color: #c82333;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-volver {
  background-color: #6c757d;
  color: white;
}

.btn-volver:hover {
  background-color: #5a6268;
  transform: translateY(-2px);
  text-decoration: none;
  color: white;
}

@media (max-width: 768px) {
  .aviso-card {
    margin: 10px;
  }
  
  .aviso-content {
    padding: 20px;
  }
  
  .aviso-titulo {
    font-size: 22px;
  }
  
  .acciones {
    flex-direction: column;
  }
  
  .aviso-icon i {
    font-size: 60px;
  }
}
</style>