/**
 * Created By Mohammed Ali
 */

$(document).ready(function(){
    $("#myBtn").click(function(){
        var str = $("#providerLink").val();
        console.log(str);
        var result = /[^/]*$/.exec(str)[0];

        $.ajax({
            url: '/tasks/'+ result,
            method: 'POST'
        }).then(function(response) {
            console.log(response.response);
            $('#showMessage').html(response.response);
            $("#showMessage").show(2000);
            window.location.replace("/");
            // window.location.reload();
        });
    });


    var fixmeTop = $('#fixmeId').offset().top;       // get initial position of the element
    $(window).scroll(function() {                  // assign scroll event listener
        var currentScroll = $(window).scrollTop(); // get current position
        if (currentScroll >= fixmeTop) {           // apply position: fixed if you
            $('#fixmeId').css({                      // scroll to that element or below it
                position: 'fixed',
                // top: '0',
                // right: '0'
            });
        } else {                                   // apply position: static
            $('#fixmeId').css({                      // if you scroll above it
                position: 'static'
            });
        }
    });


});





