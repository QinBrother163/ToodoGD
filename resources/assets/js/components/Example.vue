
<template>
    <div style="margin-top:50px">
        <el-row :gutter="10">
            <el-col :xs="5" :sm="5" :md="3" :lg="3">
                <el-menu default-active="1" class="el-menu-vertical-demo" style="min-height:800px"  @select="handleSelect">
                    <el-submenu index="1">
                    <template slot="title">收入分析</template>
                    <el-menu-item-group>
                        <template slot="title">分组一</template>
                        <el-menu-item index="1">状态数据</el-menu-item>
                        <el-menu-item index="4">收入数据</el-menu-item>
                    </el-menu-item-group>
                    </el-submenu>
                    <el-menu-item index="2">游戏玩家</el-menu-item>
                    <el-menu-item index="3">在线分析</el-menu-item>
                    <el-menu-item index="5">产品订购</el-menu-item>
                </el-menu>
            </el-col>
            <el-col :xs="18" :sm="18" :md="18" :lg="18">
                    <div>
                        <router-view></router-view>
                    </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$router.push('/Page1');
        },
        methods:{
            handleSelect(key, keyPath){
                switch(key){
                    case '1':
                        this.$router.push('/Page1');
                        break;
                    case '2':
                        this.$router.push('/Page2')
                        break;
                    case '3':
                        this.$router.push('/Page3')
                        break;
                    case '4':
                        this.$router.push('/Page4')
                        break;
                    case '5':
                        this.$router.push('/Page5')
                        break;
                }
            },

        },
    }
</script>