import { createWebHistory, createRouter } from "vue-router";

//auto register components to vue router
const components = import.meta.glob('../components/*.vue', {eager: true})
let routes =[]
Object.entries(components).forEach(([path, definition]) => {
    const componentName = path.split('/').pop().replace(/\.\w+$/, '')
    routes.push({path:'/'+componentName.toLowerCase(), name:componentName, component:() =>import(`../components/${componentName}.vue`)})
})

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
