import VueRouter from 'vue-router'
// Pages
import Home from './pages/Home'
import Register from './pages/login/Register'
import Login from './pages/login/Login'
import Dashboard from './pages/user/Dashboard'
import AdminDashboard from './pages/admin/Dashboard'
import AdminUsers from './pages/admin/AdminUsers'
// Routes
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: {
      auth: undefined
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      auth: false
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      auth: false
    }
  },
  // USER ROUTES
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      auth: true
    }
  },
  // ADMIN ROUTES
  {
    path: '/admin',
    name: 'admin.dashboard',
    component: AdminDashboard,
    meta: {
      auth: {
        roles: 'Manage_Users',
        redirect: {
          name: 'login'
        },
        forbiddenRedirect: '/403'
      }
    }
  },
  {
    path: '/admin/users',
    name: 'admin.users',
    component: AdminUsers,
    meta: {
      auth: {
        roles: 'Manage_Users',
        redirect: {
          name: 'login'
        },
        forbiddenRedirect: '/403'
      }
    }
  },
]
const router = new VueRouter({
  history: true,
  mode: 'history',
  routes,
})
export default router
