<template>
  <div class="welcome-page">
    <!-- Header con navegación -->
    <header class="header">
      <div class="header-container">
        <div class="logo-area">
          <h1 class="logo">ITTG</h1>
        </div>
        
        <nav class="nav-menu" v-if="!user">
          <Link href="/login" class="nav-link login">
            <i class="fas fa-sign-in-alt"></i>
            <span>Iniciar Sesión</span>
          </Link>
          <Link :href="route('estudiantes.create')" class="nav-link register">
            <i class="fas fa-user-plus"></i>
            <span>Registrarse</span>
          </Link>
        </nav>
        
        <nav class="nav-menu" v-else>
          <Link href="/home" class="nav-link home">
            <i class="fas fa-home"></i>
            <span>Home</span>
          </Link>
          <Link :href="route('salida')" class="nav-link logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar Sesión</span>
          </Link>
        </nav>
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="main-content">
      <div class="hero-section">
        <div class="hero-content">
          <h1 class="hero-title">
            <span class="title-main">SISTEMA DE SEGUIMIENTO</span>
            <span class="title-sub">A RESIDENCIAS PROFESIONALES</span>
          </h1>
          
          <div class="hero-decoration">
            <div class="decoration-line blue"></div>
            <div class="decoration-line red"></div>
            <div class="decoration-line blue"></div>
          </div>
          
          <p class="hero-description">
            Gestiona y da seguimiento a tus residencias profesionales 
          </p>
          
          <div class="hero-actions" v-if="!user">
            <Link href="/login" class="btn btn-primary">
              <i class="fas fa-sign-in-alt"></i>
              Comenzar ahora
            </Link>
            <!--
            <Link href="/registro" class="btn btn-secondary">
              <i class="fas fa-info-circle"></i>
              Más información
            </Link>
            -->
            
          </div>
        </div>
        
        <div class="hero-image">
          <div class="image-container">
            
            <div class="icon-grid">
              <div class="icon-item blue">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="icon-item red">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="icon-item blue">
                <i class="fas fa-users"></i>
              </div>
              <div class="icon-item red">
                <i class="fas fa-file-alt"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-container">
        <p>&copy; 2026 Sistema de Seguimiento a Residencias. ITTG . </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)

const logout = () => {
  router.post('/salida', {}, {
    onSuccess: () => {
      // Redirigir a la página de welcome después del logout
      router.visit('/')
    }
  })
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.welcome-page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
}

/*  HEADER  */
.header {
  background: linear-gradient(90deg, rgb(19, 46, 68) 0%, rgb(40, 95, 139) 100%);
  padding: 1rem 2rem;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-container {
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-area {
  display: flex;
  align-items: center;
}

.logo {
  font-size: 2rem;
  font-weight: 800;
  color: white;
  background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: 2px;
}

.nav-menu {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1rem;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
}

.nav-link i {
  font-size: 1.1rem;
}

.login {
  background-color: #ef4444;
  color: white;
  box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.login:hover {
  background-color: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.register {
  background-color: white;
  color: rgb(19, 46, 68);
  border: 2px solid rgb(19, 46, 68);
}

.register:hover {
  background-color: rgb(19, 46, 68);
  color: white;
  transform: translateY(-2px);
}

.home {
  background-color: rgb(40, 95, 139);
  color: white;
}

.home:hover {
  background-color: rgb(19, 46, 68);
  transform: translateY(-2px);
}

.logout {
  background-color: #6b7280;
  color: white;
}

.logout:hover {
  background-color: #4b5563;
  transform: translateY(-2px);
}

/*  MAIN CONTENT */
.main-content {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.hero-section {
  max-width: 1400px;
  width: 100%;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: center;
}

.hero-content {
  padding: 2rem;
}

.hero-title {
  margin-bottom: 2rem;
}

.title-main {
  display: block;
  font-size: 3rem;
  font-weight: 800;
  color: rgb(19, 46, 68);
  line-height: 1.2;
  text-transform: uppercase;
  letter-spacing: -1px;
}

.title-sub {
  display: block;
  font-size: 2.5rem;
  font-weight: 600;
  color: rgb(40, 95, 139);
  line-height: 1.2;
  margin-top: 0.5rem;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

.hero-decoration {
  display: flex;
  gap: 0.5rem;
  margin: 2rem 0;
}

.decoration-line {
  height: 4px;
  border-radius: 2px;
  flex: 1;
}

.decoration-line.blue {
  background: linear-gradient(90deg, rgb(19, 46, 68), rgb(40, 95, 139));
}

.decoration-line.red {
  background: linear-gradient(90deg, #ef4444, #dc2626);
  flex: 0.5;
}

.hero-description {
  font-size: 1.25rem;
  color: #4b5563;
  line-height: 1.6;
  margin-bottom: 2rem;
  max-width: 600px;
}

.hero-actions {
  display: flex;
  gap: 1rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 2rem;
  border-radius: 50px;
  font-weight: 600;
  font-size: 1.1rem;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
}

.btn-primary {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  box-shadow: 0 4px 15px rgba(19, 46, 68, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(19, 46, 68, 0.4);
}

.btn-secondary {
  background-color: #ef4444;
  color: white;
  box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-secondary:hover {
  background-color: #dc2626;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

/* HERO IMAGE  */
.hero-image {
  display: flex;
  justify-content: center;
  align-items: center;
}

.image-container {
  background: white;
  border-radius: 30px;
  padding: 3rem;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  transform: perspective(1000px) rotateY(-5deg);
  transition: transform 0.3s ease;
}

.image-container:hover {
  transform: perspective(1000px) rotateY(0deg);
}

.icon-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem;
}

.icon-item {
  width: 100px;
  height: 100px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  transition: all 0.3s ease;
}

.icon-item.blue {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  box-shadow: 0 10px 20px rgba(19, 46, 68, 0.3);
}

.icon-item.red {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
  box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
}

.icon-item:hover {
  transform: scale(1.1) rotate(5deg);
}

/*  FOOTER  */
.footer {
  background: rgb(19, 46, 68);
  color: white;
  padding: 1.5rem;
  margin-top: auto;
}

.footer-container {
  max-width: 1400px;
  margin: 0 auto;
  text-align: center;
}

.footer p {
  font-size: 0.95rem;
  opacity: 0.9;
}

/*  RESPONSIVE  */
@media (max-width: 968px) {
  .hero-section {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
  
  .hero-content {
    text-align: center;
    padding: 1rem;
  }
  
  .hero-decoration {
    justify-content: center;
  }
  
  .hero-actions {
    justify-content: center;
  }
  
  .title-main {
    font-size: 2.5rem;
  }
  
  .title-sub {
    font-size: 2rem;
  }
  
  .image-container {
    transform: none;
  }
}

@media (max-width: 768px) {
  .header {
    padding: 1rem;
  }
  
  .header-container {
    flex-direction: column;
    gap: 1rem;
  }
  
  .nav-menu {
    width: 100%;
    justify-content: center;
    flex-wrap: wrap;
  }
  
  .nav-link {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
  }
  
  .hero-title {
    text-align: center;
  }
  
  .title-main {
    font-size: 2rem;
  }
  
  .title-sub {
    font-size: 1.5rem;
  }
  
  .hero-description {
    font-size: 1rem;
    padding: 0 1rem;
  }
  
  .hero-actions {
    flex-direction: column;
    align-items: stretch;
    padding: 0 1rem;
  }
  
  .btn {
    justify-content: center;
  }
  
  .icon-grid {
    gap: 1rem;
  }
  
  .icon-item {
    width: 70px;
    height: 70px;
    font-size: 2rem;
  }
}

/* Animaciones */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hero-content {
  animation: fadeIn 1s ease-out;
}

.hero-image {
  animation: fadeIn 1s ease-out 0.3s both;
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>