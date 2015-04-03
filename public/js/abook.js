/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function() {

    $(document).on("click", "#add-email, #add-phone", function() {
        var btn = $(this).attr("id");
        insert(btn);
    });

    $(document).on("click", "#rem", function() {
        
        var wrapper = $(this).parent().parent().parent().attr("id");
        var count = countInput(wrapper);

        if (count > 1) {            
            //$(this).parent().css({"display":"none"});
            $(this).parent().attr("data-delete", "on");
            $(this).prev().prev().attr("data-delete", "on");
            $(this).prev().attr("data-delete", "on");
            $(this).attr("data-delete", "on");         
            $("*[data-delete='on']").remove();
        }
        
    });
    
    function countInput(wrapper){
        var count = $("#" + wrapper + " > fieldset > fieldset > .form-control").length;
        return count;
    }
    
    function insert(btn) {

        switch (btn) {
            case 'add-email' :
                var wrapper = "email-wrapper";
                break;
            case 'add-phone' :
                var wrapper = "phone-wrapper";
                break;
        }

        var currentCount = countInput(wrapper);
        wrapper = "#"+wrapper;
        var template = $(wrapper + " > fieldset > span").data("template");
        template = template.replace(/__index__/g, currentCount);
        $(wrapper + " > fieldset").append(template);
        return false;
    }

});


