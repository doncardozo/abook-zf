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

        function insert(btn) {

            switch (btn) {
                case 'add-email' :
                    var wrapper = "#email-wrapper";
                    break;
                case 'add-phone' :
                    var wrapper = "#phone-wrapper";
                    break;
            }

            var currentCount = $(wrapper + " > fieldset > .form-control").length;
            var template = $(wrapper + " > fieldset > span").data("template");
            template = template.replace(/__index__/g, currentCount);
            $(wrapper + " > fieldset").append(template);
            return false;
        }

        $(document).on("click", "#rem", function() {

            $(this).prev().prev().attr("data-delete", "on");            
            $(this).prev().attr("data-delete", "on");            
            $(this).attr("data-delete", "on");            
            
            $("*[data-delete='on']").remove();
        });

    });


