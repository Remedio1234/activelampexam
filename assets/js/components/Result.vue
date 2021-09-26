<template>
    <div>
        <ul 
            class="list-group mb-3 mt-5" 
            v-if="row">
            <li class="list-group-item d-flex justify-content-between lh-sm">
                <div class=" wd-br"> 
                    <h6 class="my-0">
                        <a 
                            :href="row.hash" 
                            id="latest_url">{{row.tinyurl}}</a>
                    </h6>
                    <small class="text-muted">
                        {{row.url}}
                    </small>
                </div>
                <span class="text-muted">
                    <a 
                        href="javascripr://;" 
                        @click.prevent="copyLink">{{txt_copy}}</a>
                </span>
            </li>               
        </ul>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex'
    export default {
        name: 'Result',
        data () {
            return {
                txt_copy: 'Copy'
            }
        },
        computed:{
            ...mapGetters({
                row   : 'exam/result'
            })
        },
        methods: {
            copyLink: function(){
                this.txt_copy = 'Copied'
                this.$parent.copyToClipboard("#latest_url")
                setTimeout(() => {
                     this.txt_copy = 'Copy'
                }, 1000);
            }
        },
    }
</script>