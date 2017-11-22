
<template>
    <div style="margin-top:50px">
        <el-row :gutter="10">
            <el-col :xs="5" :sm="5" :md="3" :lg="3">
                <el-menu default-active="1" class="el-menu-vertical-demo" style="min-height:800px"  @select="handleSelect">
                    <el-submenu index="11">
                    <template slot="title">收入分析</template>
                    <el-menu-item-group>
                        <template slot="title">分组一</template>
                        <el-menu-item index="11">收入数据</el-menu-item>
                    </el-menu-item-group>
                    </el-submenu>
                    <el-menu-item index="2">游戏玩家</el-menu-item>
                    <el-menu-item index="3">在线分析</el-menu-item>
                    </el-submenu>
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
            this.$router.push('/Page11');
        },
        methods:{
            handleSelect(key, keyPath){
                switch(key){
                    case '11':
                        this.$router.push('/Page11');
                        break;
                    case '2':
                        this.$router.push('/Page2')
                        break;
                    case '3':
                        this.$router.push('/Page3')
                        break;
                }
            },

        },
    }
</script>