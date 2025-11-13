<template>
  <div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>
        <i class="bi bi-book-fill primaryText me-2"></i>
        Listado de Estudios
      </h2>
    </div>

    <div v-if="estudios.length === 0 && !loading" class="alert alert-info">
      <i class="bi bi-info-circle me-2"></i>
      No hay estudios disponibles.
    </div>

    <div class="row g-4">
      <div v-for="estudio in estudios" :key="estudio.id" class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm hover-card">
          <div class="card-body">
            <h5 class="card-title">
              <i class="bi bi-mortarboard primaryText me-2"></i>
              {{ estudio.nombre }}
            </h5>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <span class="badge bg-secondary">ID: {{ estudio.id }}</span>
              <router-link
                :to="{ name: 'asignaturas', params: { id: estudio.id }, query: { nombre: estudio.nombre } }"
                class="btn primaryButton btn-sm">
                <i class="bi bi-list-ul me-1"></i>
                Ver Asignaturas
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from '@/services/api'

const estudios = ref([])
const loading = ref(false)
const error = ref(null)

const cargarEstudios = async () => {
  loading.value = true
  error.value = null

  try {
    const data = await api.getEstudios()
    estudios.value = data.data
  } catch (err) {
    console.error('Error al cargar estudios:', err)
    error.value = 'No se pudieron cargar los estudios'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  cargarEstudios()
})
</script>
