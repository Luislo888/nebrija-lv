import { ref } from 'vue'

export function useSort(initialSortBy = 'nombre', initialSortOrder = 'ASC') {
    const sortBy = ref(initialSortBy)
    const sortOrder = ref(initialSortOrder)

    const toggleSort = (campo) => {
        if (sortBy.value === campo) {
            sortOrder.value = sortOrder.value === 'ASC' ? 'DESC' : 'ASC'
        } else {
            sortBy.value = campo
            sortOrder.value = 'ASC'
        }
    }

    const getIconoOrden = (campo) => {
        if (sortBy.value !== campo) {
            return 'bi-arrow-down-up'
        }
        return sortOrder.value === 'ASC' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-down-alt'
    }

    return {
        sortBy,
        sortOrder,
        toggleSort,
        getIconoOrden
    }
}
