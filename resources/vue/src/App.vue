<template>
  <div id="app">
    <Navbar />

    <LoadingOverlay v-if="loading" />

    <main class="container my-4">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>

      <div v-if="error" class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Error:</strong> {{ error }}
        <button type="button" class="btn-close" @click="error = null"></button>
      </div>
    </main>

    <footer class="bg-white text-center text-muted py-3 mt-5">
      <div class="container">
        <p class="mb-0">Sistema de Gestión Académica</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, provide } from 'vue'
import Navbar from '@/components/Navbar.vue'
import LoadingOverlay from '@/components/LoadingOverlay.vue'

const loading = ref(false)
const error = ref(null)

provide('loading', loading)
provide('error', error)
</script>
