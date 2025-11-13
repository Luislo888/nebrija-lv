import { createRouter, createWebHistory } from 'vue-router'
import EstudiosView from '@/views/EstudiosView.vue'
import AsignaturasView from '@/views/AsignaturasView.vue'
import AlumnosView from '@/views/AlumnosView.vue'
import MatriculacionesView from '@/views/MatriculacionesView.vue'

const routes = [
    {
        path: '/',
        name: 'estudios',
        component: EstudiosView
    },
    {
        path: '/estudio/:id/asignaturas',
        name: 'asignaturas',
        component: AsignaturasView
    },
    {
        path: '/alumnos',
        name: 'alumnos',
        component: AlumnosView
    },
    {
        path: '/matriculaciones',
        name: 'matriculaciones',
        component: MatriculacionesView
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
