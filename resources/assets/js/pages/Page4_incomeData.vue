<template>
    <div>
        <el-row style="margin: 0 0 20px 0;">
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

            <el-button type="button" @click="screening = true">筛选</el-button>
            <el-dialog title="套餐类型：" :visible.sync="screening" :modal="false" :close-on-click-modal="false" :show-close="false">
                    <el-checkbox-group v-model="check_List" @change="handleCurrentChangeAmount">
                        <el-checkbox label="10">10</el-checkbox>
                        <el-checkbox label="20">20</el-checkbox>
                        <el-checkbox label="30">30</el-checkbox>
                    </el-checkbox-group>
                    <span style="margin: 20px 0 30px 0;display: block" class="el-dialog__title">经营类型：</span>
                    <el-radio-group v-model="state_order" @change="handleCurrentChangeOrderStatus">
                        <el-radio :label="5">飞奔科技</el-radio>
                        <el-radio :label="1">拓捷</el-radio>
                        <el-radio :label="10">不限</el-radio>
                    </el-radio-group>
                    <span style="margin: 20px 0 30px 0;display: block" class="el-dialog__title">卡号查询：</span>
                    <el-input
                        placeholder="请输入卡号"
                        icon="search"
                        v-model="input_facility"
                        @change="handleIconClick_input">
                    </el-input>
                    <div slot="footer" class="dialog-footer">
                        <el-button @click="cancelSearchCondition">取 消</el-button>
                        <el-button type="primary" @click="handleSearchClick">确 定</el-button>
                    </div>
            </el-dialog>
        </el-row>

        <el-row>
            <div id='selected_condition_style' class="selected_condition_style">
                <span>已选择的条件 ：</span>

                <a href='javascript:'><span>{{amount_0}}</span></a>
                <a href='javascript:'><span>{{amount_1}}</span></a>
                <a href='javascript:'><span>{{amount_2}}</span></a>
                <a href='javascript:'><span>{{showOrderStatus}}</span></a>
                <a href='javascript:'><span>{{input_facility}}</span></a>
            </div>
        </el-row>
        <el-table
                v-if="data.length > 0"
                :data="data"
                :summary-method="getSummaries"
                show-summary
                style="width: 100%;display: block;text-align: center">
            <el-table-column
                    prop="id"
                    label="序列号"
                    header-align='center'
                    width="185">
            </el-table-column>
            <el-table-column
                    prop="createDate"
                    label="创建时间"
                    header-align='center'
                    width="250">
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
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="needCnfm"
                    label="童锁"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="price"
                    label="价格"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="CPID"
                    label="经营公司"
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
                screening: false,
                check_List: [],
                state_order: 10,//指定设备号
                showOrderStatus: '',//经营公司 0 飞奔 1拓捷 10 不限
                amount_0: '',
                amount_1: '',
                amount_2: '',
                amount_list: [],
                input_facility: '',





            };
        },
        mounted() {
            this.getOrders();

        },
        methods: {
            getSummaries(param) {
                    const { columns, data } = param;
                    const sums = [];
                    columns.forEach((column, index) => {
                      if (index === 0 || index === 1 || index === 2 || index === 3 || index === 4 || index === 6) {
                        sums[0] = '总价';
                        sums[1] = 'N/A';
                        sums[2] = 'N/A';
                        sums[3] = 'N/A';
                        sums[4] = 'N/A';
                        sums[6] = 'N/A';
                        return;
                      }
                      const values = data.map(item => Number(item[column.property]));
                      if (!values.every(value => isNaN(value))) {
                        sums[index] = values.reduce((prev, curr) => {
                          const value = Number(curr);
                          if (!isNaN(value)) {
                            return prev + curr;
                          } else {
                            return prev;
                          }
                        }, 0);
                        sums[index] += ' 元';
                      } else {
                        sums[index] = 'N/A';
                      }
                    });

                    return sums;
            },
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
            handleCurrentChangeAmount(val) {// 金额筛选
                let owner = this;
                this.amount_0 = val[0];
                this.amount_1 = val[1];
                this.amount_2 = val[2];
                this.amount_list = val;
                let selected_condition_style = document.getElementById('selected_condition_style');
                let a = selected_condition_style.querySelectorAll('a');
                for (let i=0;i<a.length;i++){
                    a[i].onclick = function(){
                        if(owner.amount_list[i] == this.innerText){
                            owner.amount_list.splice(i,1);
                            owner.amount_0 = val[0];
                            owner.amount_1 = val[1];
                            owner.amount_2 = val[2];
                            owner.getOrders();
                        }
                    }
                }
            },
            handleCurrentChangeOrderStatus(val) {//设备状态

                if (val == 5) {
                this.showOrderStatus = '飞奔科技';
                }else if (val == 1) {
                this.showOrderStatus = '拓捷';
                }else if (val == 10) {
                this.showOrderStatus = '不限';
                }

                this.order_status = val;
            },
            cancelSearchCondition(){//筛选取消按钮函数
                this.screening = false;
            },
            handleSearchClick(){//筛选确认按钮函数
                this.getOrders();
                this.screening = false;
            },
            handleIconClick_input(val) {
                this.input_facility = val;
                let owner = this;
                let selected_condition_style = document.getElementById('selected_condition_style');
                let a = selected_condition_style.querySelectorAll('a');
                for (let i=0;i<a.length;i++){
                    a[i].onclick = function(){
                        if(owner.input_facility == this.innerText){
                            owner.input_facility = '';
                            owner.getOrders();
                        }
                    }
                }
            },

            getOrders(){
                let owner = this;
                const baseUrl = 'components/mgpayrecord?';
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
                if (this.amount_list) {
                    args.amount_list = this.amount_list;
                }
                if (this.order_status){
                    args.order_status = this.order_status;
                }else {
                    args.order_status = 10;
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
