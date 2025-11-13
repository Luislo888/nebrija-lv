<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
      <router-link to="/" class="navbar-brand">
        <i class="bi bi-mortarboard-fill me-2"></i>
        Gestión Académica
      </router-link>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <router-link to="/" class="nav-link" active-class="active">
              <i class="bi bi-book-fill me-1"></i>
              Estudios
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/alumnos" class="nav-link" active-class="active">
              <i class="bi bi-people-fill me-1"></i>
              Alumnos
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/matriculaciones" class="nav-link" active-class="active">
              <i class="bi bi-journal-check me-1"></i>
              Matriculaciones
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
</script>
