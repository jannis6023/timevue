import { createRouter, createWebHistory } from 'vue-router'
import AdminFrontend from "../views/AdminFrontend";
import DashBoard from "../views/DashBoard";
import Team from "../views/Team";
import EmployeeDetail from "../views/EmployeeDetail";
import MeEmployee from "../views/MeEmployee";

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

export default router
