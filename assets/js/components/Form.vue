<template>
    <div>
        <h4 class="mb-3"> Enter a long URL to make a Shorten</h4>
        <form 
            class="needs-validation" 
            id="formSubmit"
            @submit.prevent="urlHandler">
            <div class="input-group">
                <input 
                    type="text" 
                    name="url"
                    id="url"
                    autofocus
                    class="form-control form-control-lg custom-input" 
                    placeholder="Shorten your link"
                    :class="{'url-error': is_error.length}"
                    v-model="form.url"
                    @input="checkUrl"
                    :disabled="is_submit"
                />
                <button 
                    type="submit" 
                    class="btn btn-success"
                    :disabled="is_submit"
                >Shorten</button>
                    
            </div>
            <div 
                class="alert alert-danger mt-3" 
                role="alert" 
                v-if="is_error.length"
                v-html="is_error[0].msg.errors"></div>
        </form>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    export default {
        name:'Form',
        computed:{
            ...mapGetters({
                is_submit       : 'exam/is_submit',
                is_error        : 'exam/is_error',
            }),
        },
        data(){
            return {
                form: {
                    url     :   ''
                },
                shortend    :   [],
                id          :   null,
                hash        :   null,
                tinyurl     :   null,
                url         :   null,
                created_at  :   null
            }
        },
        created(){
            if (sessionStorage.getItem("shortEndAPI") != null) 
                this.shortend = eval(sessionStorage.getItem("shortEndAPI"))
        },
        methods:{
            ...mapActions({
                urlForm         : 'exam/urlForm',
                isErrorSet      : 'exam/isErrorSet',
                getResult       : 'exam/getResult',
                isFormSubmit    : 'exam/isFormSubmit' 
            }),
            addItem: function(id, hash, tinyurl, url, created_at) {
                this.$parent.load_history = null
                for(var item in this.shortend) {
                    if(this.shortend[item].url == url) {
                        this.$parent.load_history = 'History'
                         //find exist url here
                        return;
                    }
                }
                this.shortend.push({
                    id:id,
                    hash:hash,
                    tinyurl:tinyurl,
                    url:url,
                    created_at:created_at
                });
                sessionStorage.setItem('shortEndAPI', JSON.stringify(this.shortend))
                setTimeout(() => {
                    this.$parent.load_history = 'History'
                }, 10);
            },
            listHistory: function() {
                var historyCopy = [];
                for(var i in this.shortend) {
                    var item = this.shortend[i],
                    itemCopy = {};
                    for(var p in item) {
                        itemCopy[p] = item[p];
                    }
                    historyCopy.push(itemCopy)
                }
                return historyCopy;
            },
            urlHandler: function(){
                var _this = this
                if(_this.$parent.validURL(_this.form.url)) 
                    _this.urlForm(_this.form).then(function(res){
                        if( res && res.data != undefined ){
                            var result = res.data
                            _this.getResult(result)
                            _this.addItem(result.id, result.hash, result.tinyurl, result.url, result.created_at);
                        }
                        _this.isFormSubmit(false)
                    })
                else
                    _this.isErrorSet([{
                        error : 422, msg: {errors : "Invalid URL"}
                        }
                    ])
            },
            checkUrl:function(){
                var _this = this
                if(_this.$parent.validURL(_this.form.url) || _this.form.url == '')
                    _this.isErrorSet([])
                else   
                    _this.isErrorSet([{
                        error : 422, msg: {errors : "Invalid URL"}
                        }
                    ])
            }
        }
    }
</script>