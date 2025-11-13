const API_BASE_URL = 'http://nebrija-lv.local/api'

async function handleResponse(response) {
    if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`)
    }

    const data = await response.json()

    if (data.error) {
        throw new Error(data.message || 'Error en la petición')
    }

    return data
}

export const api = {
    async getEstudios() {
        const response = await fetch(`${API_BASE_URL}/estudios`, {
            headers: { 'Content-Type': 'application/json' }
        })
        return handleResponse(response)
    },

    async getAsignaturas(idEstudio, sortBy = 'nombre', sortOrder = 'ASC') {
        const url = `${API_BASE_URL}/asignaturas?idEstudio=${idEstudio}&sortBy=${sortBy}&sortOrder=${sortOrder}`
        const response = await fetch(url, {
            headers: { 'Content-Type': 'application/json' }
        })
        return handleResponse(response)
    },

    async getAlumnos(page = 1, limit = 5, sortBy = 'nombre', sortOrder = 'ASC') {
        const url = `${API_BASE_URL}/alumnos?page=${page}&limit=${limit}&sortBy=${sortBy}&sortOrder=${sortOrder}`
        const response = await fetch(url, {
            headers: { 'Content-Type': 'application/json' }
        })
        return handleResponse(response)
    },

    async getMatriculaciones(page = 1, limit = 10, sortBy = 'alumnoNombre', sortOrder = 'ASC') {
        const url = `${API_BASE_URL}/alumnos-asignaturas?page=${page}&limit=${limit}&sortBy=${sortBy}&sortOrder=${sortOrder}`
        const response = await fetch(url, {
            headers: { 'Content-Type': 'application/json' }
        })
        return handleResponse(response)
    }
}
