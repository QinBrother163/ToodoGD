<template>
    <div>
        <el-row>
            <el-date-picker
                  style = "width: auto"
                  @change="handleDateRangeChange_val1"
                  v-model="date_val1"
                  type="date"
                  placeholder="开始日期">
            </el-date-picker>
            <span class="demonstration">至</span>
            <el-date-picker
                  style = "width: auto"
                  @change="handleDateRangeChange_val2"
                  v-model="date_val2"
                  type="date"
                  placeholder="结束日期">
            </el-date-picker>

            <el-button type="button" @click="screening = true">筛选</el-button>
            <el-dialog title="套餐类型：" :visible.sync="screening" :modal="false" :close-on-click-modal="false" :show-close="false">
                    <el-checkbox-group v-model="check_List" @change="handleCurrentChangeAmount">
                        <el-checkbox label="包月">包月</el-checkbox>
                        <el-checkbox label="套餐一">套餐一</el-checkbox>
                        <el-checkbox label="套餐二">套餐二</el-checkbox>
                        <el-checkbox label="黄金套餐">黄金套餐</el-checkbox>
                        <el-checkbox label="钻石套餐">钻石套餐</el-checkbox>
                    </el-checkbox-group>
                    <span style="margin: 20px 0 30px 0;display: block" class="el-dialog__title">订单状态：</span>
                    <el-checkbox-group v-model="state_order" @change="handleCurrentChangeOrderStatus">
                    <el-checkbox label="5">已订购</el-checkbox>
                    <el-checkbox label="1">未订购</el-checkbox>
                    </el-checkbox-group>
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
                <a href='javascript:'><span>{{amount_3}}</span></a>
                <a href='javascript:'><span>{{amount_4}}</span></a>
                <a href='javascript:'><span>{{showOrderStatus}}</span></a>
            </div>
        </el-row>

        <el-row>
        <el-table id='tdo_order_datas'
                v-if="data.length > 0"
                :data="data"
                :summary-method="getSummaries"
                show-summary
                style="width: 100%;display: block;text-align: center">
            <el-table-column
                    prop="OrderNo"
                    label="订单号"
                    header-align='center'
                    width="250">
            </el-table-column>
            <el-table-column
                    prop="ShopId"
                    label="渠道"
                    header-align='center'
                    width="70">
            </el-table-column>
            <el-table-column
                    prop="UserId"
                    label="用户"
                    header-align='center'
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="Signature"
                    label="标题"
                    header-align='center'
                    width="430">
            </el-table-column>
            <el-table-column
                    prop="Amount"
                    label="金额"
                    header-align='center'
                    width="130">
            </el-table-column>
            <el-table-column
                    label="状态"
                    header-align='center'
                    width="100">
                     <template slot-scope="scope">
                        {{ scope.row.PayStatus === 5?'已订购':'未订购'}}
                     </template>
            </el-table-column>
            <el-table-column
                    prop="CreateDate"
                    label="创建时间"
                    header-align='center'
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="DeviceId"
                    label="电话"
                    header-align='center'
                    width="170">
            </el-table-column>
        </el-table>
        </el-row>

        <el-row>
        <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="current_page"
                :page-sizes="[15, 30, 45, 60,9999]"
                :page-size="per_page"
                layout="total, sizes, prev, pager, next, jumper"
                :total="total">
        </el-pagination>
        </el-row>
    </div>
</template>


<script>
    import {pageUrl} from '../../app/PageUrl';
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
                check_List:[],
                state_order: [],
                amount_0: '',
                amount_1: '',
                amount_2: '',
                amount_3: '',
                amount_4: '',
                amount_list: [],
                showOrderStatus: '',




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
                      if (index === 0 || index === 1 || index === 2 || index === 3 || index === 5 || index === 6 || index === 7) {
                                    sums[index] = '总价';
                                    sums[1] = 'N/A';
                                    sums[2] = 'N/A';
                                    sums[3] = 'N/A';
                                    sums[5] = 'N/A';
                                    sums[6] = 'N/A';
                                    sums[7] = 'N/A';
                                    return;
                      }
                      const values = data.map(item => Number(item[column.property]));
                      let number100 = parseInt(100);
                      if (!values.every(value => isNaN(value))) {
                        sums[index] = values.reduce((prev, curr) => {
                          const value = Number(curr);
                          if (!isNaN(value)) {
                            return prev + curr;
                          } else {
                            return prev;
                          }
                        }, 0);
                        sums[index] = sums[index]/number100 + ' 元';

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

            handleCurrentChangeAmount(val) {
                this.amount_0 = val[0];
                this.amount_1 = val[1];
                this.amount_2 = val[2];
                this.amount_3 = val[3];
                this.amount_4 = val[4];
                this.amount_list = val;
                let owner = this;
                let selected_condition_style = document.getElementById('selected_condition_style');
                let a = selected_condition_style.querySelectorAll('a');

                for (let i=0;i<a.length;i++){
                    a[i].onclick = function(){
                        if(owner.amount_list[i] == this.innerText){
                            owner.amount_list.splice(i,1);
                            owner.amount_0 = val[0];
                            owner.amount_1 = val[1];
                            owner.amount_2 = val[2];
                            owner.amount_3 = val[3];
                            owner.amount_4 = val[4];
                            owner.getOrders();
                        }
                    }
                }
            },
            handleCurrentChangeOrderStatus(val) {

                if (val == 5) {
                this.showOrderStatus = '已订购';
                }else if (val == 1) {
                this.showOrderStatus = '未订购';
                }

                this.state_order = val;
            },
            handleSearchClick(){
                this.getOrders();
                this.screening = false;
            },
            cancelSearchCondition(){
                this.screening = false;
            },

            getOrders(){
                let owner = this;
                const baseUrl = 'components/toodoGD/ToodoGDLT?';
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

                     let arr_1 ,arr_2;
                     let arr_list = [];
                     arr_1 = this.amount_list;

                     let amount_2500 = '2500';
                     let amount_22900 = '22900';
                     let amount_13900 = '13900';
                     let amount_19900 = '19900';
                     let amount_28900 = '28900';

                     if (arr_1[0] == '包月') {
                         arr_list[0] = amount_2500;
                     }else if (arr_1[0] == '套餐一') {
                         arr_list[0] = amount_22900;
                     }else if (arr_1[0] == '套餐二') {
                         arr_list[0] = amount_13900;
                     }else if (arr_1[0] == '黄金套餐') {
                         arr_list[0] = amount_19900;
                     }else if (arr_1[0] == '钻石套餐') {
                         arr_list[0] = amount_28900;
                     }else if (!arr_1[0]) {
                          arr_list[0]= '';
                     }

                     if (arr_1[1] == '包月') {
                         arr_list[1] = amount_2500;
                     }else if (arr_1[1] == '套餐一') {
                         arr_list[1] = amount_22900;
                     }else if (arr_1[1] == '套餐二') {
                         arr_list[1] = amount_13900;
                     }else if (arr_1[1] == '黄金套餐') {
                         arr_list[1] = amount_19900;
                     }else if (arr_1[1] == '钻石套餐') {
                         arr_list[1] = amount_28900;
                     }else if (!arr_1[1]) {
                         arr_list[1]= '';
                     }

                     if (arr_1[2] == '包月') {
                         arr_list[2] = amount_2500;
                     }else if (arr_1[2] == '套餐一') {
                         arr_list[2] = amount_22900;
                     }else if (arr_1[2] == '套餐二') {
                         arr_list[2] = amount_13900;
                    }else if (arr_1[2] == '黄金套餐') {
                         arr_list[2] = amount_19900;
                     }else if (arr_1[2] == '钻石套餐') {
                         arr_list[0] = amount_28900;
                     }else if (!arr_1[2]) {
                     arr_list[2]= '';
                     }

                     if (arr_1[3] == '包月') {
                         arr_list[3] = amount_2500;
                     }else if (arr_1[3] == '套餐一') {
                         arr_list[3] = amount_22900;
                     }else if (arr_1[3] == '套餐二') {
                         arr_list[3] = amount_13900;
                     }else if (arr_1[3] == '黄金套餐') {
                         arr_list[3] = amount_19900;
                     }else if (arr_1[3] == '钻石套餐') {
                         arr_list[3] = amount_28900;
                     }else if (!arr_1[3]) {
                         arr_list[3]= '';
                     }

                     if (arr_1[4] == '包月') {
                         arr_list[4] = amount_2500;
                     }else if (arr_1[4] == '套餐一') {
                         arr_list[4] = amount_22900;
                     }else if (arr_1[4] == '套餐二') {
                         arr_list[4] = amount_13900;
                     }else if (arr_1[4] == '黄金套餐') {
                         arr_list[4] = amount_19900;
                     }else if (arr_1[4] == '钻石套餐') {
                         arr_list[4] = amount_28900;
                     }else if (!arr_1[4]) {
                         arr_list[4]= '';
                     }

                    arr_2 = arr_list.join(" ");
                    args.amount_list = arr_2;
                }
                if (this.state_order) {
                    args.state_order = this.state_order;
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
