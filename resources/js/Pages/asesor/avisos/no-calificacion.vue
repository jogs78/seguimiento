<template>
  <AppLayout>
    <div class="aviso-container">
      <div class="aviso-card info">
        <div class="aviso-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <div class="aviso-content">
          <h1 class="aviso-titulo">📄 El estudiante aún no ha subido sus calificaciones</h1>
          <p class="aviso-mensaje">
            El estudiante no ha registrado la información de la empresa ni ha subido los documentos requeridos.
          </p>
          
          <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <div class="info-text">
              <strong>¿Qué significa esto?</strong>
              <p>Para poder realizar la evaluación, el estudiante debe primero:</p>
              <ul>
                <li>✅ Registrar los datos de la empresa</li>
                <li>✅ Subir los documentos del proyecto</li>
                <li>✅ Completar la información del seguimiento</li>
              </ul>
            </div>
          </div>
          
          <div class="acciones">
            <Link :href="route('home')" class="btn-volver">
              <i class="fas fa-arrow-left"></i>
              Volver al Inicio
            </Link>
            <Link :href="route('asesor.listar-proyectos')" class="btn-proyectos">
              <i class="fas fa-sync-alt"></i>
              Ver otros proyectos
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'
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

.aviso-card.info {
  border-left: 6px solid #17a2b8;
  background: white;
}

.aviso-icon {
  background: #d1ecf1;
  padding: 20px;
  text-align: center;
}

.aviso-icon i {
  font-size: 64px;
  color: #17a2b8;
}

.aviso-content {
  padding: 30px;
}

.aviso-titulo {
  font-size: 22px;
  font-weight: bold;
  margin-bottom: 15px;
  color: #0c5460;
}

.aviso-mensaje {
  font-size: 16px;
  color: #666;
  margin-bottom: 25px;
  line-height: 1.5;
}

.info-box {
  display: flex;
  gap: 15px;
  background: #f8f9fa;
  padding: 15px 20px;
  border-radius: 12px;
  margin-bottom: 25px;
}

.info-box i {
  font-size: 24px;
  color: #17a2b8;
}

.info-text {
  flex: 1;
}

.info-text strong {
  display: block;
  margin-bottom: 8px;
  color: #333;
}

.info-text p {
  margin: 5px 0;
  color: #555;
}

.info-text ul {
  margin: 8px 0 0 20px;
  padding: 0;
}

.info-text li {
  margin: 5px 0;
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
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
  color: white;
}

.btn-proyectos:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
  text-decoration: none;
  color: white;
}

@media (max-width: 768px) {
  .acciones {
    flex-direction: column;
  }
  
  .info-box {
    flex-direction: column;
  }
  
  .aviso-titulo {
    font-size: 18px;
  }
}
</style>