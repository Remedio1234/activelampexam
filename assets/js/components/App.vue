<template>
    <div class="col-lg-8 mx-auto p-3 py-md-5">
        <Header />
        <!-- {# Main #} -->
        <main>
            <h1>Short links, big results</h1>
            <p class="fs-5 col-md-8">
                A URL shortener built with powerful tools 
                to help you grow and protect your brand.
            </p>
            <div class="mb-5">
            <a 
                href="javascript://;" 
                class="btn btn-success btn-lg px-4">Get Started for Free</a>
            </div>
            <!-- {# start #} -->
            <div class="row g-5">
                <!-- {# History #} -->
                <component 
                    :is="load_history" />
                <!-- {# End History #} -->
                <div class="col-md-7 col-lg-8">
                    <!-- {# Form #} -->
                    <Form />
                    <!-- {# End Form #} -->
                    <!-- {# Result #} -->
                    <Result />
                    <!-- {# End Result #} -->
                </div>
                <!-- {# End Form #} -->
            </div>
            <!-- {# end #} -->
            <hr class="my-4">
            <Footer />
        </main>
    </div>
  <!-- {# End Main #} -->
</template>

<script>
import Header   from './Header';
import Footer   from './Footer';
import History  from './History';
import Result   from './Result';
import Form     from './Form';
export default {
    components:  { 
        Header, 
        Footer,
        Form,
        History,
        Result 
    },
    data() {
        return {
            load_history: null
        };
    },
    created(){
        if (sessionStorage.getItem("shortEndAPI") != null) 
            this.load_history = 'history'
    },
    methods: {
        copyToClipboard: function(el) {
            let $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(el).text()).select();
                document.execCommand("copy");
                $temp.remove();
        },
        truncate: function (str, n){
            return (str.length > n) ? 
                str.substr(0, n-1) + '&hellip;' : 
                str;
        },
        sortByKeyDesc: function(array, key) {
            return array.sort(function (a, b) {
                var x = a[key]; var y = b[key];
                return ((x > y) ? -1 : ((x < y) ? 1 : 0));
            });
        },
        validURL: function(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
            return !!pattern.test(str);
        }
    }
}
</script>   