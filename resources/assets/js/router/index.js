/**
 * Created by Administrator on 2017/10/20 0020.
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import Page1 from '../pages/Page1_gamePlayer.vue'
import Page2 from '../pages/Page3.vue'
import Page3 from '../pages/Page2.vue'
import Page4 from '../pages/Page4_incomeData.vue'
import Page5 from '../pages/Page5_productOrder.vue'
Vue.use(VueRouter);

const router = new VueRouter({
    routes:[{
        path: '/Page1', component: Page1
    },{
        path: '/Page2', component: Page2
    },{
        path:'/Page3', component: Page3
    },{
        path:'/Page4', component: Page4
    },{
        path:'/Page5', component: Page5
    }]
})

export default router;