import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/store/auth.store'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "Home",
            redirect: "/dashboard"
        },
        {
            path: '/login',
            name: "Login",
            component: () => import('@/views/Auth/LoginView.vue')
        },
        {
            path: '/dashboard',
            name: "Dashboard",
            component: () => import('@/views/DashboardView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/travel-orders/new',
            name: 'TravelOrderCreate',
            component: () => import('../views/TravelOrderForm.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/travel-orders/status',
            name: 'TravelOrderStatus',
            component: () => import('../views/TravelOrderStatus.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/travel-orders/:id',
            name: 'TravelOrderDetails',
            component: () => import('../views/TravelOrderDetails.vue'),
            meta: { requiresAuth: true },
            props: true
        },
        {
            path: '/register',
            name: 'Register',
            component: () => import('../views/Auth/RegisterView.vue'),
            props: true
        },
        {
            path: '/notifications',
            name: 'Notifications',
            component: () => import('../views/NotificationsView.vue'),
            meta: { requiresAuth: true }
        },
        {
          path: '/notifications/:id',
          name: 'NotificationDetail',
          component: () => import('../views/NotificationDetailView.vue'),
          meta: { requiresAuth: true }
        }
    ]
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/register')
    }else if (to.name === 'Register' && authStore.isAuthenticated) {
        next('/dashboard')
    } else {
        next()
    }
})

export default router
