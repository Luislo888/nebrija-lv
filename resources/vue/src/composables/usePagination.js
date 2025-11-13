import { computed } from 'vue'

export function usePagination(paginacion) {
    const paginas = computed(() => {
        const paginas = []
        const { page, totalPages } = paginacion.value
        const maxPaginas = 5

        let inicio = Math.max(1, page - Math.floor(maxPaginas / 2))
        let fin = Math.min(totalPages, inicio + maxPaginas - 1)

        if (fin - inicio + 1 < maxPaginas) {
            inicio = Math.max(1, fin - maxPaginas + 1)
        }

        for (let i = inicio; i <= fin; i++) {
            paginas.push(i)
        }

        return paginas
    })

    const rango = computed(() => {
        const { page, limit, total } = paginacion.value
        const inicio = (page - 1) * limit + 1
        const fin = Math.min(page * limit, total)
        return { inicio, fin, total }
    })

    return { paginas, rango }
}
