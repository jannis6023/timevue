import { createRouter, createWebHistory } from 'vue-router'
import AdminFrontend from "../views/AdminFrontend";
import DashBoard from "../views/DashBoard";
import Team from "../views/Team";
import EmployeeDetail from "../views/EmployeeDetail";
import MeEmployee from "../views/MeEmployee";
import store from "../store/index"

const routes = [
    {
        path: '/admin',
        name: 'AdminFrontend',
        component: AdminFrontend,
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: DashBoard
            },
            {
                path: 'team',
                name: 'team',
                component: Team
            },
            {
                path: 'team/:id',
                name: 'employeeDetail',
                component: EmployeeDetail,
                props: true
            },
            {
                path: 'shiftplan/template'
            }
        ]
    },
    {
        path: '/employee/:id',
        name: 'employee',
        component: MeEmployee,
        props: true
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

function isMobile() {
    return screen.width <= 760;
}

router.afterEach((to, from) => {
    if(isMobile()){store.commit('toggleNav')}
})

export default router
