<template>
  <div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>
        <i class="bi bi-journal-text primaryText me-2"></i>
        Asignaturas: {{ route.query.nombre }}
      </h2>
      <router-link to="/" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Volver a Estudios
      </router-link>
    </div>

    <div v-if="asignaturas.length === 0 && !loading" class="alert alert-warning">
      <i class="bi bi-exclamation-triangle me-2"></i>
      No hay asignaturas disponibles para este estudio.
    </div>

    <div v-if="asignaturas.length > 0">
      <div class="text-muted small mb-3">
        <i class="bi bi-info-circle me-1"></i>
        Total de asignaturas: {{ asignaturas.length }}
      </div>
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover vista-asignaturas">
              <thead class="table-light">
                <tr>
                  <th width="80" class="sortable-header" @click="toggleSort('id')">
                    ID
                    <i :class="'bi ms-1 ' + getIconoOrden('id')"></i>
                  </th>
                  <th class="sortable-header" @click="toggleSort('nombre')">
                    Nombre de la Asignatura
                    <i :class="'bi ms-1 ' + getIconoOrden('nombre')"></i>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="asignatura in asignaturas" :key="asignatura.id">
                  <td>
                    <span class="badge primaryBg">{{ asignatura.id }}</span>
                  </td>
                  <td>
                    <i class="bi bi-journal-text text-muted me-2"></i>
                    {{ asignatura.nombre }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import { api } from '@/services/api'
import { useSort } from '@/composables/useSort'

const route = useRoute()
const asignaturas = ref([])
const loading = ref(false)

const { sortBy, sortOrder, toggleSort, getIconoOrden } = useSort('nombre', 'ASC')

const cargarAsignaturas = async () => {
  loading.value = true
  try {
    const data = await api.getAsignaturas(route.params.id, sortBy.value, sortOrder.value)
    asignaturas.value = data.data
  } catch (err) {
    console.error('Error al cargar asignaturas:', err)
  } finally {
    loading.value = false
  }
}

watch([sortBy, sortOrder], () => {
  cargarAsignaturas()
})

onMounted(() => {
  cargarAsignaturas()
})
</script>
