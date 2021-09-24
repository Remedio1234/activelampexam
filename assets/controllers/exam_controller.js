import { Controller } from 'stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }
}





$(function(){
    function sortByKeyDesc(array, key) {
        return array.sort(function (a, b) {
            var x = a[key]; var y = b[key];
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        });
    }

    function truncate(str, n){
        return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
    };

    function validURL(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    return !!pattern.test(str);
    }

    

    

    function handleException(request, message, error) {
        var msg = "";
        msg += "Code: " + request.status + "\n";
        msg += "Text: " + request.statusText + "\n";
        if (request.responseJSON != null) {
        msg += "Message" + request.responseJSON.Message + "\n";
        }
        alert(msg);
    }

    
    // ************************************************
    // URL API
    // ************************************************

    var shortEndAPI = (function() {
        // =============================
        // Private methods and propeties
        // =============================
        var shortend = [];
        
        // Constructor
        function Item(id, hash, tinyurl, url, created_at) {
            this.id             = id;
            this.hash           = hash;
            this.tinyurl        = tinyurl;
            this.url            = url;
            this.created_at     = created_at;
        }
        
        // Save shortend
        function saveHistory() {
            sessionStorage.setItem('shortEndAPI', JSON.stringify(shortend));
        }
        
            // Load history
        function loadHistory() {
            shortend = JSON.parse(sessionStorage.getItem('shortEndAPI'));
        }
        if (sessionStorage.getItem("shortEndAPI") != null) {
            loadHistory();
        }
        

        // =============================
        // Public methods and propeties
        // =============================
        var obj = {};
        
        // Add item
        obj.addItem = function(id, hash, tinyurl, url, created_at) {
            for(var item in shortend) {
                if(shortend[item].url == url){
                    return;
                }
            }
            var item = new Item(id, hash, tinyurl, url, created_at);
            shortend.push(item);
            saveHistory();
        }

        // List History
        obj.listHistory = function() {
            var historyCopy = [];
            for(var i in shortend) {
                var item = shortend[i],
                itemCopy = {};
                for(var p in item) {
                    itemCopy[p] = item[p];
                }
                historyCopy.push(itemCopy)
            }
            return historyCopy;
        }
        return obj;
    })();

    getHistory()

    function showResult(){
        var $history    = shortEndAPI.listHistory();
        var $lasts      = $history[$history.length - 1],
            $output = `<li class="list-group-item d-flex justify-content-between lh-sm">
                <div>
                <h6 class="my-0"><a href="">${$lasts.tinyurl}</a></h6>
                <small class="text-muted">
                    ${$lasts.url}
                </small>
                </div>
                <span class="text-muted"><a href="">Copy</a></span>
            </li>`;
        $("#result").show().html($output);    
        getHistory();
    }

    function insert(formData){
        $.ajax({
            url: "/create",
            type: 'POST',
            data: formData,
            success: function (res) {
                if(res && res.id != undefined){
                    shortEndAPI.addItem(res.id, res.hash, res.tinyurl, res.url, res.created_at);
                    showResult()
                }
            },
            error: function (request, message, error) {
                handleException(request, message, error);
            }
        });
    }

    function getHistory(){
        var $history    = shortEndAPI.listHistory(), //JSON.parse(sessionStorage.getItem('history')),
        $counter        = $("#counter"),
        $output         = "";
        
        for(var i in sortByKeyDesc($history, 'id')) {
            $output += `
                <div class="d-flex text-muted pt-3">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <strong class="text-gray-dark">${$history[i].tinyurl}</strong>
                            <small class="text-muted"><a href="${$history[i].tinyurl}">Visit</a> | <a href="#">Copy</a> </small>
                        </div>
                        <span class="d-block">${truncate($history[i].url, 35)}</span>
                    </div>
                </div>
            `
        }

        if($history != null && $history.length){
            console.log($history)
            $counter.text($history.length)
            $("#details_history").html($output)
            $('#history').show()
        }
    }

    var $url   = $("#url"),
        $error = $("#error_wrapper")

    $(document).on('submit', '#formSubmit', function(e){
        e.preventDefault();
        if($url.val() == ''){
            $url.focus();
        }
        $error.hide().text("")  
        $url.removeClass("url-error")
        if(validURL($url.val()))
            insert($(this).serialize())
        else {
            $error.show().text("Invalid URL")
            $url.addClass("url-error")
            setTimeout(() => {
                $error.hide().text("")
            }, 3000);
        }
    });

    $(document).on('input', '#url', function(){
        if(validURL($url.val()) || $url.val() == ''){
            $error.hide().text("")  
            $url.removeClass("url-error")
        } else {
            $error.show().text("Invalid URL")
            $url.addClass("url-error")
            setTimeout(() => {
                $error.hide().text("")
            }, 3000);
        }   
    })

    $('form input').keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
})