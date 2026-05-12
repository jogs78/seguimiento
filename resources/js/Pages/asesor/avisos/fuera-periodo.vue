<template>
  <AppLayout>
    <div class="aviso-container">
      <div class="aviso-card warning">
        <div class="aviso-icon">
          <i class="fas fa-calendar-times"></i>
        </div>
        <div class="aviso-content">
          <h1 class="aviso-titulo">⚠️ Fuera del período de evaluación</h1>
          <p class="aviso-mensaje">Actualmente te encuentras fuera del período establecido para realizar evaluaciones.</p>
          
          <div class="fechas-info">
            <h3><i class="fas fa-calendar-alt"></i> Fechas del período actual:</h3>
            <ul>
              <li><strong>📅 Inicio:</strong> {{ fechas.fecha_inicio || 'No definida' }}</li>
              <li><strong>📅 Término:</strong> {{ fechas.fecha_final || 'No definida' }}</li>
              <li v-if="fechas.siguiente"><strong>⏰ Próximo período:</strong> {{ fechas.siguiente }}</li>
            </ul>
          </div>
          
          <div class="acciones">
            <Link :href="route('home')" class="btn-volver">
              <i class="fas fa-arrow-left"></i>
              Volver al Inicio
            </Link>
            <Link :href="route('asesor.listar-proyectos')" class="btn-proyectos">
              <i class="fas fa-folder-open"></i>
              Ver mis proyectos
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

// Obtener período actual de las props globales o configuraciones
const page = usePage()
const periodoActual = computed(() => page.props.periodoActual || null)
const configuraciones = computed(() => page.props.configuraciones || [])

// Fechas para mostrar
const fechas = computed(() => {
  if (periodoActual.value) {
    return {
      fecha_inicio: formatFecha(periodoActual.value.fecha_inicio),
      fecha_final: formatFecha(periodoActual.value.fecha_final),
      siguiente: null
    }
  }
  
  // Buscar configuración de fechas
  const fechaInicio = configuraciones.value.find(c => c.variable === 'fecha_inicio_periodo')
  const fechaFinal = configuraciones.value.find(c => c.variable === 'fecha_final_periodo')
  
  return {
    fecha_inicio: fechaInicio?.valor || 'No definida',
    fecha_final: fechaFinal?.valor || 'No definida',
    siguiente: 'Contacta al coordinador para más información'
  }
})

// Formatear fecha
const formatFecha = (fecha) => {
  if (!fecha) return 'No definida'
  return new Date(fecha).toLocaleDateString('es-MX', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
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
  max-width: 600px;
  width: 100%;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.aviso-card.warning {
  border-left: 6px solid #ffc107;
  background: white;
}

.aviso-icon {
  background: #fff3cd;
  padding: 20px;
  text-align: center;
}

.aviso-icon i {
  font-size: 64px;
  color: #ffc107;
}

.aviso-content {
  padding: 30px;
}

.aviso-titulo {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 15px;
  color: #856404;
}

.aviso-mensaje {
  font-size: 16px;
  color: #666;
  margin-bottom: 25px;
  line-height: 1.5;
}

.fechas-info {
  background: #f8f9fa;
  padding: 15px 20px;
  border-radius: 12px;
  margin-bottom: 25px;
}

.fechas-info h3 {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 10px;
  color: #333;
}

.fechas-info ul {
  margin: 0;
  padding-left: 20px;
}

.fechas-info li {
  margin: 8px 0;
  color: #555;
}

.acciones {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}

.btn-volver,
.btn-proyectos {
  flex: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}

.btn-volver {
  background-color: #6c757d;
  color: white;
}

.btn-volver:hover {
  background-color: #5a6268;
  text-decoration: none;
  color: white;
}

.btn-proyectos {
  background: linear-gradient(135deg, #050E3C 0%, #0a1a6e 100%);
  color: white;
}

.btn-proyectos:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(5, 14, 60, 0.3);
  text-decoration: none;
  color: white;
}

@media (max-width: 768px) {
  .acciones {
    flex-direction: column;
  }
  
  .aviso-titulo {
    font-size: 20px;
  }
}
</style>