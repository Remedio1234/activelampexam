<template>
    <div class="col-md-5 col-lg-4 order-md-last">
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Your recent URLs <span class="badge bg-primary rounded-pill">{{ counter }}</span></h6>
            <div class="details_history" v-if="history != null">
                <template v-for="(row, index) in history">
                    <div class="d-flex text-muted pt-3"  :key="index">
                        <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                            <div class="d-flex justify-content-between">
                                <strong class="text-gray-dark" :id="'history_url_'+index">{{ row.tinyurl }}</strong>
                                <small class="text-muted">
                                    <a :href="row.tinyurl">Visit</a> | 
                                    <a 
                                        href="javascript://;" 
                                        :data-index="index" 
                                        class="copy_text"
                                        @click="copyHistoryText(index)">{{selected_index == index ? 'Copied' : 'Copy'}}</a> 
                                    </small>
                            </div>
                            <span class="d-block">{{ $parent.truncate(row.url, 35) }}</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:   'History',
        data(){
            return {
                history         : null,
                counter         : 0,
                selected_index  : null
            }
        },
        created(){
            if (sessionStorage.getItem("shortEndAPI") != null) {
                this.history = this.$parent.sortByKeyDesc(eval(sessionStorage.getItem("shortEndAPI")))
                this.counter = this.history.length
            }
        },
        methods: {
            copyHistoryText: function(index){
                this.selected_index = index
                this.$parent.copyToClipboard("#history_url_"+index)
                setTimeout(() => {
                     this.selected_index = null
                }, 1000);
            }
        },
    }
</script>