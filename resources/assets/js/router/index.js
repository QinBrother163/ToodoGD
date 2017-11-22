/**
 * Created by Administrator on 2017/10/20 0020.
 */
import Vue from 'vue'
import ElementUI from 'element-ui';
import VueRouter from 'vue-router'
import Page1 from '../pages/Page1_gamePlayer.vue'
import Page2 from '../pages/Page3.vue'
import Page3 from '../pages/Page2.vue'
import Page4 from '../pages/Page4_incomeData.vue'
import Page5 from '../pages/Page5_productOrder.vue'
import Page6 from '../pages/managementActivityTABLE.vue'
import Page7 from '../pages/managementMgpayrecordTABLE.vue'
import Page8 from '../pages/managementOrder103TABLE.vue'
import Page9 from '../pages/managementConsumelogGdTABLE.vue'
import Page10 from '../pages/Page_consumelog_gd.vue'
import toodolt from '../pages/toodoltpage/income_order.vue'

//import 'element-ui/lib/theme-default/index.css';
Vue.use(ElementUI);
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
    },{
        path:'/Page6', component: Page6
    },{
        path:'/Page7', component: Page7
    },{
        path:'/Page8', component: Page8
    },{
        path:'/Page9', component: Page9
    },{
        path:'/Page10', component: Page10
    },{
        path:'/Page11', component: toodolt
    }]
})

export default router;