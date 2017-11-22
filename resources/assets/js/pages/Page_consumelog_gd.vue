<template>
    <div>
        <el-row>
            <el-row style="margin: 0 0 20px 0;">
            <el-date-picker
                  @change="handleDateRangeChange_val1"
                  v-model="date_val1"
                  type="date"
                  placeholder="开始日期时间">
            </el-date-picker>
            <span class="demonstration">至</span>
            <el-date-picker
                  @change="handleDateRangeChange_val2"
                  v-model="date_val2"
                  type="date"
                  placeholder="结束日期时间">
            </el-date-picker>
            </el-row>
        </el-row>

        <el-row>
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
                    width="80">
            </el-table-column>
            <el-table-column
                    prop="tj_area"
                    label="地区"
                    header-align='center'
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="tj_product"
                    label="产品"
                    header-align='center'
                    width="100">
            </el-table-column>
            <el-table-column
                    prop="userid"
                    label="帐号ID"
                    header-align='center'
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="goodsname"
                    label="道具名称"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="pay_code"
                    label="流水号"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="price"
                    label="消耗元宝数"
                    header-align='center'
                    width="200">
            </el-table-column>
            <el-table-column
                    prop="num"
                    label="购买数量"
                    header-align='center'
                    width="80">
            </el-table-column>
            <el-table-column
                    prop="time"
                    label="时间"
                    header-align='center'
                    width="200">
            </el-table-column>
        </el-table>
        </el-row>

        <el-row>
            <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="current_page"
                :page-sizes="[15, 30, 45, 60, 9999999]"
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

              }
        },

        mounted() {
            this.getOrders();
        },

        methods: {

            getSummaries(param) {
                    const { columns, data } = param;
                    const sums = [];
                    columns.forEach((column, index) => {
                      if (index === 0 || index === 1 || index === 2 || index === 3 || index === 4 || index === 5 || index === 7 || index === 8) {
                        sums[0] = '总价';
                        sums[1] = 'N/A';
                        sums[2] = 'N/A';
                        sums[3] = 'N/A';
                        sums[4] = 'N/A';
                        sums[5] = 'N/A';
                        sums[7] = 'N/A';
                        sums[8] = 'N/A';
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

            getOrders(){
                let owner = this;
                const baseUrl = 'components/GDConsumeLog?';
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


                const reqUrl = baseUrl + pageUrl.parseArgs(args);
                console.log(reqUrl);

                axios.get(reqUrl)
                    .then(response => {
                    let paginate = response.data;
                    //console.log(paginate);

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
