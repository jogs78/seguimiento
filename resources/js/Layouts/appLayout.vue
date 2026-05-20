<template>
  <!-- Solo mostrar el layout si hay usuario autenticado -->
   <div v-if="userData" class="app-layout">
    <!-- Header mejorado -->
    <header class="app-header">
      <!-- Barra superior de colores -->
      <div class="header-bars">
        <div class="bar-blue"></div>
        <div class="bar-dark"></div>
      </div>
      
      <!-- Contenido del header -->
      <div class="header-main">
        <div class="header-left">
          <!-- Botón de menú hamburguesa (siempre visible) -->
          <button @click="toggleMenu" class="menu-toggle" :class="{ active: menuOpen }">
              <!-- Usar fa-solid fa-bars -->
              <i class="fa-solid" :class="menuOpen ? 'fa-times' : 'fa-bars'"></i>
          </button>
    
          
          <div class="logo-area">
            <img id="logo_1" src="/images/logo_tecnm_tuxtla.png"  alt="Logo" >
            <h2 class="logo-text">Sistema de Seguimiento</h2>
          </div>
        </div>
        
        <div class="header-right">
          <div class="user-info">
            <i class="fas fa-user-circle user-icon"></i>
            <div class="user-details">
              <span class="user-name">{{ user.usa.nombre }} {{ user.usa.apellido_paterno }}</span>
              <span class="user-role">({{ userType }})</span>
              <span v-if="carreraActual" class="carrera-badge">
                <i class="fas fa-graduation-cap"></i> {{ carreraActual }}
              </span>
            </div>
          </div>
          
          <div class="header-actions">
            <Link :href="route('Cambiar_Contraseña')" class="header-btn btn-password">
              <i class="fas fa-key"></i>
              <span>Cambiar Contraseña</span>
            </Link>
            <Link :href="route('salida')" class="header-btn btn-logout">
              <i class="fas fa-sign-out-alt"></i>
              <span>Salir</span>
            </Link>
          </div>
        </div>
      </div>
      
      <div class="header-line"></div>
    </header>

    <!-- Cuerpo principal con menú lateral y contenido -->
    <div class="app-body" :class="{ 'menu-collapsed': !menuOpen }">
      <!-- Menú lateral -->
      <aside class="sidebar" :class="{ 'sidebar-open': menuOpen }">
        <nav class="sidebar-nav">
          <!-- Coordinador -->
          <template v-if="user.usa_type === 'App\\Models\\Coordinador'">
            <div class="nav-section" v-if="user.es_jefe_division">
              <div class="nav-section-title">Administración</div>
              <Link :href="route('periodos.index')" class="nav-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Gestionar Periodo</span>
              </Link>
              <Link :href="route('configuraciones.index')" class="nav-item">
                <i class="fas fa-cogs"></i>
                <span>Configuraciones</span>
              </Link>
            </div>
            
            <div class="nav-section" v-else>
              <div class="nav-section-title">Gestión</div>
              <Link :href="route('coordinadores.tabla')" class="nav-item">
                <i class="fas fa-table"></i>
                <span>Tabla de Proyectos</span>
              </Link>
              <Link :href="route('coordinadores.historico')" class="nav-item">
                <i class="fas fa-history"></i>
                <span>Histórico de proyectos</span>
              </Link>
              <Link :href="route('estudiantes.index')" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Lista de Estudiantes</span>
              </Link>
              <Link :href="route('coordinador.evidencias')" class="nav-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Seguimiento de Documentos</span>
              </Link>
              <Link :href="route('asesores.index')" class="nav-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Asesores Internos</span>
              </Link>
              <Link :href="route('externos.index')" class="nav-item">
                <i class="fas fa-building"></i>
                <span>Asesores Externos</span>
              </Link>
            </div>
          </template>

          
      <!-- Asesor Interno -->
      <template v-else-if="user.usa_type === 'App\\Models\\Asesor'">
        <div class="nav-section">
          <div class="nav-section-title">Mis Proyectos</div>
          <Link :href="route('asesor.listar-proyectos')" class="nav-item">
            <i class="fas fa-folder-open"></i>
            <span>PROYECTOS ASIGNADOS</span>
          </Link>
           <Link :href="route('asesor.historico')" class="nav-item">
              <i class="fas fa-history"></i> 
              <span>HISTÓRICO DE PROYECTOS</span>
            </Link>
        </div>
      </template>
          <!-- Estudiante -->
          <template v-else-if="user.usa_type === 'App\\Models\\Estudiante'">
            <div class="nav-section">
              <div class="nav-section-title">Mi Cuenta</div>
              <Link :href="route('estudiantes.edit', user.usa_id)" class="nav-item">
                <i class="fas fa-user-edit"></i>
                <span>Actualizar Datos</span>
              </Link>
              <Link :href="route('estudiante.evidencias')" class="nav-item">
                <i class="fas fa-folder-open"></i>
                <span>Subir Evidencias</span>
              </Link>
              <Link :href="route('proyectos.create')" class="nav-item">
                <i class="fas fa-project-diagram"></i>
                <span>Mi Proyecto</span>
              </Link>


            </div>


            
            <div class="nav-section" v-if="user.tiene_proyecto">
              <div class="nav-section-title">Documentos</div>
              <a :href="route('estudiante.impresiones.solicitud')" class="nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Imprimir Solicitud</span>
              </a>
              <a :href="route('estudiante.impresiones.anteproyecto')" class="nav-item">
                <i class="fas fa-file-pdf"></i>
                <span>Imprimir Anteproyecto</span>
              </a>
              <Link :href="route('estudiante.promedio')" class="nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Verificar Seguimientos</span>
              </Link>
            </div>
          </template>

          <!-- Asesor Externo -->
          <template v-else-if="user.usa_type === 'App\\Models\\Externo'">
            <div class="nav-section">
              <div class="nav-section-title">Mis Proyectos</div>
              <Link :href="route('externo.lista-de-proyectos')" class="nav-item">
                <i class="fas fa-folder-open"></i>
                <span>Proyectos Asignados</span>
              </Link>
              <Link :href="route('externo.historico')" class="nav-item">
              <i class="fas fa-history"></i> 
              <span>Histórico de Proyectos</span>
            </Link>
            </div>
          </template>
        </nav>
      </aside>

      <!-- Contenido principal -->
      <main class="main-content">
        <div class="content-wrapper">
          <slot />
        </div>
      </main>
    </div>
  </div>
  
  <!-- Loading -->
  <div v-else class="loading-screen">
    <div class="loading-spinner">
      <i class="fas fa-spinner fa-spin"></i>
      <span>Cargando...</span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

// Recibir usuario como prop
const props = defineProps({
  authUser: {
    type: Object,
    default: null
  }
})


const page = usePage()
// Usar el prop si existe, si no usar page.props
const userData = computed(() => props.authUser || page.props.auth?.user)

//verificar que hay en userData
console.log("Datos; "+userData.value)


const user = computed(() => userData.value)
const carreraActual = computed(() => page.props.carrera_actual?.nombre)

const menuOpen = ref(true) // Por defecto abierto en escritorio

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}

// Mapeo de tipos
const userType = computed(() => {
  if (!user.value) return ''
  
  const types = {
    'App\\Models\\Estudiante': 'Estudiante',
    'App\\Models\\Asesor': 'Asesor Interno',
    'App\\Models\\Externo': 'Asesor Externo',
    'App\\Models\\Coordinador': 'Coordinador'
  }
  return types[user.value.usa_type] || user.value.usa_type
})

</script>

<style >
/* ===== VARIABLES DE COLORES ===== */
:root {
  /* Colores principales - Azules */
  --primary-dark: #050E3C;
  --primary-medium: #002455;
  --primary-light: #1a3a6e;
  
  /* Colores de acento - Rojos */
  --danger: #DC0000;
  --danger-light: #FF3838;
  --danger-dark: #b00000;
  
  /* Colores neutrales */
  --white: #ffffff;
  --black: #1a1a2e;
  --gray-100: #f8f9fa;
  --gray-200: #e9ecef;
  --gray-300: #dee2e6;
  --gray-400: #ced4da;
  --gray-500: #adb5bd;
  --gray-600: #6c757d;
  --gray-700: #495057;
  --gray-800: #343a40;
  --gray-900: #212529;
  
  /* Sombras */
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  
  /* Transiciones */
  --transition-fast: 0.2s ease;
  --transition-normal: 0.3s ease;
  --transition-slow: 0.5s ease;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.app-layout {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
  
}


#logo_1 {
  width: 40px;  
  height: auto;  /* mantiene proporción */
}
.menu-toggle {
  background: rgba(255, 255, 255, 0.15);
  border: none;
  color: var(--white);
  width: 44px;
  height: 44px;
  border-radius: 10px;
  cursor: pointer;
  transition: all var(--transition-normal);
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.menu-toggle:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: scale(1.05);
}

.menu-toggle.active {
  background: var(--danger);
}

.logo-area {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.logo-img {
  height: 40px;
  width: auto;
  filter: brightness(0) invert(1);
}

.logo-text {
  color: var(--white);
  font-size: 1.25rem;
  font-weight: 600;
  letter-spacing: -0.5px;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.user-icon {
  font-size: 2rem;
  color: var(--white);
}

.user-details {
  display: flex;
  flex-wrap: wrap;
  align-items: baseline;
  gap: 0.25rem 0.5rem;
}

.user-name {
  color: var(--white);
  font-weight: 600;
  font-size: 0.95rem;
}

.user-role {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.85rem;
}

.carrera-badge {
  background: rgba(255, 56, 56, 0.15);
  color: #ffb3b3;
  padding: 2px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  border: 1px solid rgba(255, 56, 56, 0.3);
}

.btn-password {
  background: rgba(255, 255, 255, 0.15);
  color: var(--white);
}

.btn-password:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.btn-logout {
  background: rgba(220, 0, 0, 0.2);
  color: var(--white);
  border: 1px solid rgba(220, 0, 0, 0.5);
}

.btn-logout:hover {
  background: rgba(220, 0, 0, 0.35);
  transform: translateY(-2px);
}

.header-line {
  height: 4px;
  background: linear-gradient(90deg, var(--primary-light), var(--primary-medium), var(--danger-light));
}

/* ===== BODY ===== */
.app-body {
  display: flex;
  transition: all var(--transition-normal);
}

/* ===== SIDEBAR ===== */
.sidebar {
  width: 280px;
  
  background: var(--danger-light);
  min-height: calc(150vh - 140px);
  transition: all var(--transition-normal);
  box-shadow: var(--shadow-md);
  overflow-x: hidden;
}

.menu-collapsed .sidebar {
  width: 0;
  opacity: 0;
  overflow: hidden;
}

.sidebar-nav {
  padding: 1.5rem 0;
}

.nav-section {
  margin-bottom: 1.5rem;
}

.nav-section-title {
  padding: 0.5rem 1.5rem;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--black);
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1.5rem;
  color: var(--black);
  text-decoration: none;
  transition: all var(--transition-fast);
  font-size: 0.9rem;
  font-weight: 500;
  border-left: 3px solid transparent;
}

.nav-item i {
  width: 1.5rem;
  font-size: 1.1rem;
  color: var(--primary-medium);
  transition: color var(--transition-fast);
}

.nav-item:hover {
  background: var(--danger);
  border-left-color: var(--danger-light);
  padding-left: 1.8rem;
}

.nav-item:hover i {
  color: var(--danger-light);
}

.nav-item:active {
  background: var(--gray-200);
}

/* ===== MAIN CONTENT ===== */
.main-content {
  flex: 1;
  min-width: 0;
  padding: 1.5rem;
  transition: all var(--transition-normal);
  
}

.content-wrapper {
  background: var(--white);
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: var(--shadow-md);
  min-height: calc(100vh - 200px);
 
}

/* ===== LOADING ===== */
.loading-screen {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
}

.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  color: var(--primary-dark);
  font-size: 1.2rem;
}

.loading-spinner i {
  font-size: 3rem;
  color: var(--danger-light);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .header-main {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .header-left {
    width: 100%;
    justify-content: space-between;
  }
  
  .header-right {
    width: 100%;
    justify-content: space-between;
    flex-wrap: wrap;
  }
  
  .user-info {
    flex: 1;
  }
  
  .logo-text {
    font-size: 1rem;
  }
  
  .header-btn span {
    display: none;
  }
  
  .header-btn {
    padding: 0.5rem;
  }
  
  .header-btn i {
    margin: 0;
    font-size: 1.2rem;
  }
  
  .sidebar {
    position: fixed;
    top: 130px;
    left: 0;
    height: calc(100vh - 130px);
    z-index: 99;
    transform: translateX(-100%);
  }
  
  .sidebar-open {
    transform: translateX(0);
    min-width: 25%;
  }
  
  .menu-collapsed .sidebar {
    width: 280px;
    opacity: 1;
  }
  
  .main-content {
    padding: 1rem;
  }
  
  .content-wrapper {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .user-details {
    flex-direction: column;
    gap: 0;
  }
  
  .user-name {
    font-size: 0.85rem;
  }
  
  .user-role {
    font-size: 0.7rem;
  }
  
  .carrera-badge {
    font-size: 0.65rem;
  }
  
  .sidebar {
    top: 150px;
    height: calc(100vh - 150px);
  }
}

/* ===== ANIMACIONES ===== */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.nav-item {
  animation: slideIn var(--transition-normal) ease-out;
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>