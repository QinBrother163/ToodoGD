<template>
    <div>
        <el-row>
            <el-row style="margin: 0 0 20px 0;">
            <el-date-picker
                  @change="handleDateRangeChange_val1"
                  v-model="date_val1"
                  type="datetime"
                  placeholder="开始日期时间">
            </el-date-picker>
            <span class="demonstration">至</span>
            <el-date-picker
                  @change="handleDateRangeChange_val2"
                  v-model="date_val2"
                  type="datetime"
                  placeholder="结束日期时间">
            </el-date-picker>
            </el-row>
            <el-table
                :data="data"
                style="width: 100%">
                <el-table-column
                  label="编号"
                  width="100">
                  <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.id }}</span>
                  </template>
                </el-table-column>
                <el-table-column
                  label="日期"
                  header-align='center'
                  width="200">
                  <template slot-scope="scope">
                    <i class="el-icon-time"></i>
                    <span style="margin-left: 10px">{{ scope.row.createDate }}</span>
                  </template>
                </el-table-column>
                <el-table-column
                  label="记录表"
                  width="100">
                  <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.completionStatus }}</span>
                  </template>
                </el-table-column>
                <el-table-column
                  label="补充表"
                  width="100">
                  <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.fee }}</span>
                  </template>
                </el-table-column>
                <el-table-column label="操作">
                  <template slot-scope="scope">
                    <el-button
                      size="mini"
                      type="primary"
                      @click="handleEdit(scope.$index, data)">全部添加</el-button>
                    <el-button
                      size="mini"
                      type="success"
                      @click="handleDdd(scope.$index, scope.row)">添加</el-button>
                  </template>
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
                indexDataRow: '',
                indexDataAddRow:'',

              }
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
            handleDdd(index,row){
                let arr = [];

                for(let j in row){
                    let str = row[j]
                    arr.push( str );
                }
                //console.log(arr);
                this.indexDataAddRow = arr;
                this.getOrders();
            },
            handleEdit(index,row) {

                let arr = [];

                for(let i=0;i<row.length;i++){
                    let arr1 = row[i];
                    for(let j in arr1){
                        let str = arr1[j]
                        arr.push( str );
                    }
                }

                //console.log(arr);
                this.indexDataRow = arr;
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
                const baseUrl = 'components/managementOrder103?';
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
                if(this.indexDataRow){
                    args.indexDataRow = this.indexDataRow;
                }
                if(this.indexDataAddRow){
                    args.indexDataAddRow = this.indexDataAddRow;
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
