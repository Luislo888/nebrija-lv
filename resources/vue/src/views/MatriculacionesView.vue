<template>
  <div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>
        <i class="bi bi-journal-check primaryText me-2"></i>
        Matriculaciones
      </h2>
    </div>

    <div v-if="matriculaciones.length === 0 && !loading" class="alert alert-info">
      <i class="bi bi-info-circle me-2"></i>
      No hay matriculaciones registradas.
    </div>

    <div v-if="paginacion.total > 0">
      <!-- Información y selector de registros por página -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted small">
          <i class="bi bi-info-circle me-1"></i>
          Mostrando {{ rango.inicio }} - {{ rango.fin }} de {{ rango.total }} matriculaciones
        </div>
        <div class="d-flex align-items-center">
          <label class="me-2 small text-muted">Registros por página:</label>
          <select class="form-select form-select-sm" style="width: auto;" v-model="limit" @change="cambiarLimite">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover vista-matriculaciones">
              <thead class="table-light">
                <tr>
                  <th class="sortable-header" @click="toggleSort('id')">
                    ID
                    <i :class="'bi ms-1 ' + getIconoOrden('id')"></i>
                  </th>
                  <th class="sortable-header" @click="toggleSort('alumnoNombre')">
                    Alumno
                    <i :class="'bi ms-1 ' + getIconoOrden('alumnoNombre')"></i>
                  </th>
                  <th class="sortable-header" @click="toggleSort('asignaturaNombre')">
                    Asignatura
                    <i :class="'bi ms-1 ' + getIconoOrden('asignaturaNombre')"></i>
                  </th>
                  <th class="sortable-header" @click="toggleSort('estudioNombre')">
                    Estudio
                    <i :class="'bi ms-1 ' + getIconoOrden('estudioNombre')"></i>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in matriculaciones" :key="item.id">
                  <td>
                    <span class="badge primaryBg">{{ item.id }}</span>
                  </td>
                  <td>
                    <i class="bi bi-person-fill text-muted me-2"></i>
                    {{ item.alumnoNombre }}
                  </td>
                  <td>
                    <i class="bi bi-journal-text text-muted me-2"></i>
                    {{ item.asignaturaNombre }}
                  </td>
                  <td>
                    <i class="bi bi-mortarboard text-muted me-2"></i>
                    {{ item.estudioNombre }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <Pagination
        :paginacion="paginacion"
        :paginas="paginas"
        label="matriculaciones"
        @changePage="cambiarPagina"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { api } from '@/services/api'
import { useSort } from '@/composables/useSort'
import { usePagination } from '@/composables/usePagination'
import Pagination from '@/components/Pagination.vue'

const matriculaciones = ref([])
const loading = ref(false)
const limit = ref(10)
const paginacion = ref({
  page: 1,
  limit: 10,
  total: 0,
  totalPages: 0,
  hasNext: false,
  hasPrev: false
})

const { sortBy, sortOrder, toggleSort, getIconoOrden } = useSort('alumnoNombre', 'ASC')
const { paginas, rango } = usePagination(paginacion)

const cargarMatriculaciones = async (page = 1) => {
  loading.value = true
  try {
    const data = await api.getMatriculaciones(page, limit.value, sortBy.value, sortOrder.value)
    matriculaciones.value = data.data
    paginacion.value = {
      page: data.page,
      limit: data.limit,
      total: data.total,
      totalPages: data.totalPages,
      hasNext: data.hasNext,
      hasPrev: data.hasPrev
    }
  } catch (err) {
    console.error('Error al cargar matriculaciones:', err)
  } finally {
    loading.value = false
  }
}

const cambiarPagina = (page) => {
  if (page >= 1 && page <= paginacion.value.totalPages) {
    cargarMatriculaciones(page)
  }
}

const cambiarLimite = () => {
  paginacion.value.limit = parseInt(limit.value)
  cargarMatriculaciones(1)
}

watch([sortBy, sortOrder], () => {
  cargarMatriculaciones(1)
})

onMounted(() => {
  cargarMatriculaciones()
})
</script>
