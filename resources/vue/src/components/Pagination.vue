<template>
  <nav :aria-label="`paginación de ${label}`" class="mt-3">
    <ul class="pagination justify-content-center">
      <!-- Botón Primera página -->
      <li class="page-item" :class="{ disabled: !paginacion.hasPrev }">
        <a class="page-link" href="#" @click.prevent="$emit('changePage', 1)"
           :tabindex="!paginacion.hasPrev ? -1 : 0">
          <i class="bi bi-chevron-double-left"></i>
        </a>
      </li>

      <!-- Botón Anterior -->
      <li class="page-item" :class="{ disabled: !paginacion.hasPrev }">
        <a class="page-link" href="#" @click.prevent="$emit('changePage', paginacion.page - 1)"
           :tabindex="!paginacion.hasPrev ? -1 : 0">
          <i class="bi bi-chevron-left"></i>
        </a>
      </li>

      <!-- Números de página -->
      <li v-for="pagina in paginas" :key="pagina" class="page-item"
          :class="{ active: pagina === paginacion.page }">
        <a class="page-link" href="#" @click.prevent="$emit('changePage', pagina)">
          {{ pagina }}
        </a>
      </li>

      <!-- Botón Siguiente -->
      <li class="page-item" :class="{ disabled: !paginacion.hasNext }">
        <a class="page-link" href="#" @click.prevent="$emit('changePage', paginacion.page + 1)"
           :tabindex="!paginacion.hasNext ? -1 : 0">
          <i class="bi bi-chevron-right"></i>
        </a>
      </li>

      <!-- Botón última página -->
      <li class="page-item" :class="{ disabled: !paginacion.hasNext }">
        <a class="page-link" href="#" @click.prevent="$emit('changePage', paginacion.totalPages)"
           :tabindex="!paginacion.hasNext ? -1 : 0">
          <i class="bi bi-chevron-double-right"></i>
        </a>
      </li>
    </ul>
  </nav>
</template>

<script setup>
defineProps({
  paginacion: {
    type: Object,
    required: true
  },
  paginas: {
    type: Array,
    required: true
  },
  label: {
    type: String,
    default: 'registros'
  }
})

defineEmits(['changePage'])
</script>
