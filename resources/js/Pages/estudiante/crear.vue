<template>
  <!-- Si es coordinador (autenticado), usar AppLayout -->
     <AppLayout v-if="esCoordinador" :auth-user="authUser">
    <div class="authenticated-container">
      <RegistroEstudianteForm :carreras="carreras" />
    </div>
  </AppLayout>

  <!-- Si NO está autenticado (registro público), usar layout personalizado -->
  <div v-else class="registro-page">
    <header class="app-header">
      <div class="header-bars">
        <div class="bar-blue"></div>
        <div class="bar-dark"></div>
      </div>
      
      <div class="header-main">
        <div class="header-left">
          <div class="logo-area">
            <img id="logo_1" src="/images/logo_tecnm_tuxtla.png" alt="Logo">
            <h2 class="logo-text">Sistema de Seguimiento</h2>
          </div>
        </div>
        
        <div class="header-actions">
          <Link :href="route('welcome')" class="nav-link home">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
          </Link>
          <Link :href="route('Inicio_Sesion')" class="nav-link login">
            <i class="fas fa-sign-in-alt"></i>
            <span>Iniciar Sesión</span>
          </Link>
        </div>
      </div>
      
      <div class="header-line"></div>
    </header>

    <main class="main-content">
      <RegistroEstudianteForm :carreras="carreras" />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'
import RegistroEstudianteForm from '@/Components/registroEstudianteForm.vue'

const props = defineProps({
  carreras: Array,
   esCoordinador: Boolean,
  auth: Boolean,
  authUser: Object  // ✅ Recibir el usuario autenticado
})
</script>

<style scoped>
/* ===== ESTILOS PARA REGISTRO PÚBLICO ===== */
.registro-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
}

.app-header {
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.header-bars {
  display: flex;
  height: 8px;
}

.bar-blue {
  flex: 2;
  background-color: rgb(40, 95, 139);
}

.bar-dark {
  flex: 1;
  background-color: rgb(19, 46, 68);
}

.header-main {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: white;
}

.logo-area {
  display: flex;
  align-items: center;
  gap: 1rem;
}

#logo_1 {
  height: 60px;
  width: auto;
}

.logo-text {
  font-size: 1.3rem;
  font-weight: 600;
  color: rgb(19, 46, 68);
  margin: 0;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
}

.nav-link.home {
  background: rgba(40, 95, 139, 0.1);
  color: rgb(40, 95, 139);
}

.nav-link.home:hover {
  background: rgba(40, 95, 139, 0.2);
}

.nav-link.login {
  background: rgb(239, 68, 68);
  color: white;
}

.nav-link.login:hover {
  background: rgb(220, 38, 38);
  transform: translateY(-2px);
}

.header-line {
  height: 3px;
  background: linear-gradient(90deg, rgb(19, 46, 68), rgb(40, 95, 139), #ef4444);
}

.main-content {
  margin-top: 140px;
  padding: 2rem;
  min-height: calc(100vh - 140px);
}

/* ===== ESTILOS PARA COORDINADOR ===== */
.authenticated-container {
  padding: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
  .header-main {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .logo-area {
    flex-direction: column;
    text-align: center;
  }
  
  .logo-text {
    font-size: 1.1rem;
  }
  
  .main-content {
    margin-top: 180px;
    padding: 1rem;
  }
  
  .authenticated-container {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .main-content {
    margin-top: 200px;
  }
  
  .header-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .nav-link {
    justify-content: center;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>