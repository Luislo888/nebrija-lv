<template>
  <div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>
        <i class="bi bi-people-fill primaryText me-2"></i>
        Listado de Alumnos
      </h2>
    </div>

    <div v-if="alumnos.length === 0 && !loading" class="alert alert-info">
      <i class="bi bi-info-circle me-2"></i>
      No hay alumnos registrados.
    </div>

    <div v-if="paginacion.total > 0">
      <!-- Información y selector de registros por página -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted small">
          <i class="bi bi-info-circle me-1"></i>
          Mostrando {{ rango.inicio }} - {{ rango.fin }} de {{ rango.total }} alumnos
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
            <table class="table table-hover vista-alumnos">
              <thead class="table-light">
                <tr>
                  <th width="80" class="sortable-header" @click="toggleSort('id')">
                    ID
                    <i :class="'bi ms-1 ' + getIconoOrden('id')"></i>
                  </th>
                  <th class="sortable-header" @click="toggleSort('nombre')">
                    Nombre del Alumno
                    <i :class="'bi ms-1 ' + getIconoOrden('nombre')"></i>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="alumno in alumnos" :key="alumno.id">
                  <td>
                    <span class="badge primaryBg">{{ alumno.id }}</span>
                  </td>
                  <td>
                    <i class="bi bi-person-fill text-muted me-2"></i>
                    {{ alumno.nombre }}
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
        label="alumnos"
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

const alumnos = ref([])
const loading = ref(false)
const limit = ref(5)
const paginacion = ref({
  page: 1,
  limit: 5,
  total: 0,
  totalPages: 0,
  hasNext: false,
  hasPrev: false
})

const { sortBy, sortOrder, toggleSort, getIconoOrden } = useSort('nombre', 'ASC')
const { paginas, rango } = usePagination(paginacion)

const cargarAlumnos = async (page = 1) => {
  loading.value = true
  try {
    const data = await api.getAlumnos(page, limit.value, sortBy.value, sortOrder.value)
    alumnos.value = data.data
    paginacion.value = {
      page: data.page,
      limit: data.limit,
      total: data.total,
      totalPages: data.totalPages,
      hasNext: data.hasNext,
      hasPrev: data.hasPrev
    }
  } catch (err) {
    console.error('Error al cargar alumnos:', err)
  } finally {
    loading.value = false
  }
}

const cambiarPagina = (page) => {
  if (page >= 1 && page <= paginacion.value.totalPages) {
    cargarAlumnos(page)
  }
}

const cambiarLimite = () => {
  paginacion.value.limit = parseInt(limit.value)
  cargarAlumnos(1)
}

watch([sortBy, sortOrder], () => {
  cargarAlumnos(1)
})

onMounted(() => {
  cargarAlumnos()
})
</script>
