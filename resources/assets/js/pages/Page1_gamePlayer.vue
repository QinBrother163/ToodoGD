<template>
    <div>
        <el-row style="margin: 0 0 20px 0;">
            <el-col :xs="15" :sm="15" :md="10" :lg="10">
            <el-date-picker
                  @change="handleDateRangeChange_val1"
                  v-model="date_val1"
                  type="date"
                  placeholder="开始日期">
            </el-date-picker>
            <span class="demonstration">至</span>
            <el-date-picker
                  @change="handleDateRangeChange_val2"
                  v-model="date_val2"
                  type="date"
                  placeholder="结束日期">
            </el-date-picker>
            </el-col>
            <el-col :xs="5" :sm="5" :md="5" :lg="5">
            <el-input
                placeholder="请输入卡号"
                icon="search"
                v-model="input_facility"
                @change="handleIconClick_input">
            </el-input>
            </el-col>
        </el-row>
        <el-table
                v-if="data.length > 0"
                :data="data"
                style="width: 100%;display: block;text-align: center">
            <el-table-column
                    prop="id"
                    label="订单号"
                    header-align='center'
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="createDate"
                    label="创建时间"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="Flags"
                    label="成功状态"
                    header-align='center'
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="resultCode"
                    label="结果码"
                    header-align='center'
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="orderID"
                    label="用户"
                    header-align='center'
                    width="220">
            </el-table-column>
            <el-table-column
                    prop="needCnfm"
                    label="童锁"
                    header-align='center'
                    width="80">
            </el-table-column>
            <el-table-column
                    prop="custid"
                    label="客户编号"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="devNO"
                    label="卡号"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="CARegionCode"
                    label="地区编码"
                    header-align='center'
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="serviceid"
                    label="商品编码"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="streamingNO"
                    label="流水号"
                    header-align='center'
                    width="200">
            </el-table-column>
        </el-table>
        <el-row>
            <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="current_page"
                :page-sizes="[15, 30, 45, 60,9999999]"
                :page-size="per_page"
                layout="total, sizes, prev, pager, next, jumper"
                :total="total">
            </el-pagination>
        </el-row>
    </div>
</template>

<script>

    import {pageUrl} from '../app/PageUrl';

    export default {
        data() {
            return {
                current_page: 1,
                last_page: 4,
                next_page_url: "",
                per_page: 15,
                prev_page_url: null,
                from: 1,
                to: 15,
                data: [],
                total: 50,
                date_val1: '',
                date_val2: '',
                begin: '',
                end: '',
                input_facility: '',


            };
        },

        mounted() {
            this.getOrders();
        },
        methods: {
            handleSizeChange(val) {    //val  ---  当前显示15条数据
                if (this.per_page == val) return;
                if (this.per_page < val) {
                    this.last_page = parseInt(this.total / val);       //解析字符串转化整数
                    if (this.total % val) this.last_page++;

                    if (this.current_page > this.last_page) {
                        this.current_page = this.last_page;
                    }
                }
                this.per_page = val;
                this.getOrders();
            },
            handleCurrentChange(val) {
                if (this.current_page == val)return;
                this.current_page = val;
                this.getOrders();
            },
            handleDateRangeChange_val1(val){//时间函数
                this.begin = val;
            },
            handleDateRangeChange_val2(val){//时间函数
                this.end = val;
                this.getOrders();
            },
            handleIconClick_input(val) {
                this.input_facility = val;
                this.getOrders();
            },


            getOrders(){
                let owner = this;
                const baseUrl = 'components/Example?';
                let args = {};
                if (this.current_page) {
                    args.page = this.current_page;
                }
                if (this.per_page) {
                    args.size = this.per_page;
                }
                if(this.begin && this.end){
                    args.begin = this.begin;
                    args.end = this.end;
                }
                if (this.input_facility){
                    args.inputFacility = this.input_facility;
                }



                const reqUrl = baseUrl + pageUrl.parseArgs(args);
                console.log(reqUrl);


                axios.get(reqUrl)
                    .then(response => {
                    let paginate = response.data;
                    console.log(paginate);

                    this.total = paginate.total;
                    this.per_page = parseInt(paginate.per_page);// 当前 显示的 条数
                    this.current_page = paginate.current_page;//   当前 下标 页
                    this.last_page = paginate.last_page;//    当前 显示的条数 的总页数
                    this.data = paginate.data;

                    if (paginate.total) {
                            this.next_page_url = paginate.next_page_url;
                            this.prev_page_url = paginate.prev_page_url;

                            this.from = paginate.from;
                            this.to = paginate.to;
                            if (this.current_page > this.last_page) {
                                this.current_page = this.last_page;
                            }
                    }

                });
            }
        }
    }
</script>
