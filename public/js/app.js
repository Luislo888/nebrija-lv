/**
 * Aplicación Vue.js 3 - Sistema de Gestión Académica
 * Consume API REST en PHP para mostrar estudios, asignaturas, alumnos y matriculaciones
 * Versión con paginación y ordenación por columnas
 */

const { createApp } = Vue;

createApp({
    data() {
        return {
            // URL base del API
            apiUrl: 'http://nebrija-lv.local/api',

            // Arrays de datos
            estudios: [],
            asignaturas: [],
            alumnos: [],
            alumnosAsignaturas: [],

            // Control de vistas
            vistaActual: 'estudios',

            // Información adicional
            estudioSeleccionado: null,
            nombreEstudioSeleccionado: '',

            // Estados de la aplicación
            cargando: false,
            error: null,

            // Paginación para Alumnos
            paginacionAlumnos: {
                page: 1,
                limit: 5,
                total: 0,
                totalPages: 0,
                hasNext: false,
                hasPrev: false
            },

            // Paginación para Alumnos-Asignaturas
            paginacionMatriculaciones: {
                page: 1,
                limit: 10,
                total: 0,
                totalPages: 0,
                hasNext: false,
                hasPrev: false
            },

            // Ordenación para Asignaturas
            ordenAsignaturas: {
                sortBy: 'nombre',
                sortOrder: 'ASC'
            },

            // Ordenación para Alumnos
            ordenAlumnos: {
                sortBy: 'nombre',
                sortOrder: 'ASC'
            },

            // Ordenación para Matriculaciones
            ordenMatriculaciones: {
                sortBy: 'alumnoNombre',
                sortOrder: 'ASC'
            }
        };
    },

    computed: {

        //  Rango de páginas a mostrar en la paginación de alumnos
        paginasAlumnos() {
            return this.calcularRangoPaginas(this.paginacionAlumnos);
        },


        //  Rango de páginas a mostrar en la paginación de matriculaciones
        paginasMatriculaciones() {
            return this.calcularRangoPaginas(this.paginacionMatriculaciones);
        },


        //  Información del rango actual de alumnos mostrados
        rangoAlumnos() {
            const { page, limit, total } = this.paginacionAlumnos;
            const inicio = (page - 1) * limit + 1;
            const fin = Math.min(page * limit, total);
            return { inicio, fin, total };
        },


        //  Información del rango actual de matriculaciones mostradas
        rangoMatriculaciones() {
            const { page, limit, total } = this.paginacionMatriculaciones;
            const inicio = (page - 1) * limit + 1;
            const fin = Math.min(page * limit, total);
            return { inicio, fin, total };
        }
    },

    methods: {
        /**
        *  Calcula el rango de páginas a mostrar en el paginador
        *  @paginacion - Objeto de paginación
        */
        calcularRangoPaginas(paginacion) {
            const paginas = [];
            const { page, totalPages } = paginacion;
            const maxPaginas = 5;

            let inicio = Math.max(1, page - Math.floor(maxPaginas / 2));
            let fin = Math.min(totalPages, inicio + maxPaginas - 1);

            if (fin - inicio + 1 < maxPaginas) {
                inicio = Math.max(1, fin - maxPaginas + 1);
            }

            for (let i = inicio; i <= fin; i++) {
                paginas.push(i);
            }

            return paginas;
        },


        /** Obtiene el icono de ordenación para una columna
        *@param {string} campo - Nombre del campo
        *@param {Object} ordenActual - Objeto con sortBy y sortOrder */

        getIconoOrden(campo, ordenActual) {
            if (ordenActual.sortBy !== campo) {
                return 'bi-arrow-down-up';
            }
            return ordenActual.sortOrder === 'ASC' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-down-alt';
        },


        /** Cambia el orden de una tabla
        *@param {string} campo - Campo por el que ordenar
        *@param {string} vista - Vista actual (asignaturas, alumnos, matriculaciones) */

        cambiarOrden(campo, vista) {
            let ordenActual;

            if (vista === 'asignaturas') {
                ordenActual = this.ordenAsignaturas;
            } else if (vista === 'alumnos') {
                ordenActual = this.ordenAlumnos;
            } else if (vista === 'matriculaciones') {
                ordenActual = this.ordenMatriculaciones;
            }

            // Si es el mismo campo, invertir orden; si no, ordenar ASC
            if (ordenActual.sortBy === campo) {
                ordenActual.sortOrder = ordenActual.sortOrder === 'ASC' ? 'DESC' : 'ASC';
            } else {
                ordenActual.sortBy = campo;
                ordenActual.sortOrder = 'ASC';
            }

            // Recargar datos según la vista
            if (vista === 'asignaturas') {
                this.cargarAsignaturas(this.estudioSeleccionado);
            } else if (vista === 'alumnos') {
                this.cargarAlumnos(1);
            } else if (vista === 'matriculaciones') {
                this.cargarAlumnosAsignaturas(1);
            }
        },


        // Carga todos los estudios desde el API         
        async cargarEstudios() {
            this.cargando = true;
            this.error = null;

            try {
                const response = await fetch(`${this.apiUrl}/estudios`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message || 'Error al obtener estudios');
                }

                this.estudios = data.data;

            } catch (error) {
                console.error('Error al cargar estudios:', error);
                this.error = 'No se pudieron cargar los estudios. Por favor, verifica que el servidor esté funcionando.';
                this.estudios = [];
            } finally {
                this.cargando = false;
            }
        },


        /** Carga las asignaturas de un estudio específico
        * @param {number} idEstudio - ID del estudio */

        async cargarAsignaturas(idEstudio) {
            this.cargando = true;
            this.error = null;

            try {
                const { sortBy, sortOrder } = this.ordenAsignaturas;
                const url = `${this.apiUrl}/asignaturas?idEstudio=${idEstudio}&sortBy=${sortBy}&sortOrder=${sortOrder}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message || 'Error al obtener asignaturas');
                }

                this.asignaturas = data.data;

            } catch (error) {
                console.error('Error al cargar asignaturas:', error);
                this.error = 'No se pudieron cargar las asignaturas. Por favor, intenta de nuevo.';
                this.asignaturas = [];
            } finally {
                this.cargando = false;
            }
        },


        /** Carga todos los alumnos desde el API con paginación
        * @param {number} page - Número de página */

        async cargarAlumnos(page = 1) {
            this.cargando = true;
            this.error = null;

            try {
                const { sortBy, sortOrder } = this.ordenAlumnos;
                const url = `${this.apiUrl}/alumnos?page=${page}&limit=${this.paginacionAlumnos.limit}&sortBy=${sortBy}&sortOrder=${sortOrder}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message || 'Error al obtener alumnos');
                }

                // Actualizar datos y paginación
                this.alumnos = data.data || [];
                this.paginacionAlumnos = {
                    page: data.page,
                    limit: data.limit,
                    total: data.total,
                    totalPages: data.totalPages,
                    hasNext: data.hasNext,
                    hasPrev: data.hasPrev
                };

            } catch (error) {
                console.error('Error al cargar alumnos:', error);
                this.error = 'No se pudieron cargar los alumnos. Por favor, verifica que el servidor esté funcionando.';
                this.alumnos = [];
            } finally {
                this.cargando = false;
            }
        },


        /** Carga la relación completa de alumnos con sus asignaturas y estudios con paginación
        * @param {number} page - Número de página */

        async cargarAlumnosAsignaturas(page = 1) {
            this.cargando = true;
            this.error = null;

            try {
                const { sortBy, sortOrder } = this.ordenMatriculaciones;
                const url = `${this.apiUrl}/alumnos-asignaturas?page=${page}&limit=${this.paginacionMatriculaciones.limit}&sortBy=${sortBy}&sortOrder=${sortOrder}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message || 'Error al obtener matriculaciones');
                }

                // Actualizar datos y paginación
                this.alumnosAsignaturas = data.data || [];
                this.paginacionMatriculaciones = {
                    page: data.page,
                    limit: data.limit,
                    total: data.total,
                    totalPages: data.totalPages,
                    hasNext: data.hasNext,
                    hasPrev: data.hasPrev
                };

            } catch (error) {
                console.error('Error al cargar matriculaciones:', error);
                this.error = 'No se pudieron cargar las matriculaciones. Por favor, verifica que el servidor esté funcionando.';
                this.alumnosAsignaturas = [];
            } finally {
                this.cargando = false;
            }
        },


        /** Cambia de página en alumnos
        * @param {number} page - Número de página */

        cambiarPaginaAlumnos(page) {
            if (page >= 1 && page <= this.paginacionAlumnos.totalPages) {
                this.cargarAlumnos(page);
            }
        },


        /** Cambia de página en matriculaciones
        * @param {number} page - Número de página */

        cambiarPaginaMatriculaciones(page) {
            if (page >= 1 && page <= this.paginacionMatriculaciones.totalPages) {
                this.cargarAlumnosAsignaturas(page);
            }
        },


        //  Cambia el límite de registros página en alumnos
        cambiarLimiteAlumnos(event) {
            this.paginacionAlumnos.limit = parseInt(event.target.value);
            this.cargarAlumnos(1);
        },


        // Cambia el límite de registros página en matriculaciones
        cambiarLimiteMatriculaciones(event) {
            this.paginacionMatriculaciones.limit = parseInt(event.target.value);
            this.cargarAlumnosAsignaturas(1);
        },


        // Cambia la vista a Estudios
        mostrarEstudios() {
            this.vistaActual = 'estudios';
            this.estudioSeleccionado = null;
            this.nombreEstudioSeleccionado = '';

            if (this.estudios.length === 0) {
                this.cargarEstudios();
            }
        },


        /** Cambia la vista a Asignaturas de un estudio específico
        * @param {number} idEstudio - ID del estudio
        * @param {string} nombreEstudio - Nombre del estudio */

        mostrarAsignaturas(idEstudio, nombreEstudio) {
            this.vistaActual = 'asignaturas';
            this.estudioSeleccionado = idEstudio;
            this.nombreEstudioSeleccionado = nombreEstudio;

            // Resetear ordenación al cambiar de estudio
            this.ordenAsignaturas = {
                sortBy: 'nombre',
                sortOrder: 'ASC'
            };

            this.cargarAsignaturas(idEstudio);
        },


        // Cambia la vista a Alumnos        
        mostrarAlumnos() {
            this.vistaActual = 'alumnos';
            this.cargarAlumnos(1);
        },


        // Cambia la vista a Alumnos-Asignaturas (Matriculaciones)        
        mostrarAlumnosAsignaturas() {
            this.vistaActual = 'alumnosAsignaturas';
            this.cargarAlumnosAsignaturas(1);
        }
    },


    // Hook que se ejecuta cuando el componente es montado
    // Carga los datos iniciales    
    mounted() {
        this.cargarEstudios();
    }
}).mount('#app');