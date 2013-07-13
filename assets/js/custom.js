"use strict";

$.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
        $(window).scrollTop()) + "px");
    //    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
    //        $(window).scrollLeft()) + "px");    
    return this;
}
$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        console.log(re.test(value))
        return this.optional(element) || re.test(value);
    },
    "Please check your input."
    );
        
$.fn.shake = function(intShakes, intDistance, intDuration) {
    this.each(function() {
        $(this).css("position","relative"); 
        for (var x=1; x<=intShakes; x++) {
            $(this).animate({
                left:(intDistance*-1)
            }, (((intDuration/intShakes)/4)))
            .animate({
                left:intDistance
            }, ((intDuration/intShakes)/2))
            .animate({
                left:0
            }, (((intDuration/intShakes)/4)));
        }
    });
    return this;
};
        
        

var Alohamora = {
    baseURL: 'http://localhost/alohamora/',
    //Production baseURL: 'http://192.168.1.10/rigel/',    
    participants : [],
    participant_list : [],
    schools : [],
    textboxes: $('#txtFirstName, #txtLastName, #txtEmail, #txtCellphone, #txtSchool'),
    loginText : '',
    confirmText : '',
    init : function(){
        UI.prepare();
        AJAX.getParticipants();
        Alohamora.loginText = AJAX.getChalkboard('login', UI.loadLogin);
        Alohamora.confirmText = AJAX.getChalkboard('confirm', UI.loadConfirm);        
        
        $('#showLogin').on('click', function(){
            UI.loadLogin();
        //            AJAX.getChalkboard('login', UI.loadLogin)
        });
               
        
        $('#showLogout').on('click', function(){
            $.ajax({
                url: 'http://localhost/alohamora/ajax/logout',
                method: 'POST',
                data : {
                    intent: 'add'
                },
                success : function (data) {
                    
                    $('#chalkboard').children().fadeOut(1000, function(){
                        $('#chalkboard').empty().append(data).fadeIn(1000);
                    });
                    
                    
                }
            })
        });       
    },
    prepareUI: function(){
        $('.borderer').css('height', $(window).height());
        $('#registrationSplash').sdVertAlign($('#registrationBackground'), 'm');
        $('#logoutSplash').sdVertAlign($('#logoutBackground'), 'm');
        $('#logoutCenterThis').sdVertAlign($('#logoutSplash'), 'm');
        $('#txtSchool').autocomplete({
            source:Alohamora.schools
        });        
        $('#txtFullNameLogout').autocomplete({
            source:Alohamora.participant_list,
            change: function (event, ui) {
                if (!ui.item) {
                    $(this).val('');
                }
            }
        });
        
        $('#txtFullName').autocomplete({
            source: Alohamora.participant_list,
            select: function(event, ui){
                $('#txtFullName').val(ui.item);                
                var person_found = _.findWhere(Alohamora.participants, {
                    full_name: ui.item.value
                });
                if(person_found){                
                    $('#txtSchool').val(person_found.school);
                    $('#txtCellphone').val(person_found.cellphone_number);
                    $('#txtEmail').val(person_found.email);
                };
            //                Alohamora.events.findOnBlur();
            }
        });
    }
};



var UI = {
    prepare: function(){
        $(window).bind('resize', function() {
            $('#chalkboard').center();
        });
    },    
    loadLogin: function(data){                        
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.loginText).fadeIn(1000);            
            Alohamora.Validations.validateRegistration();
        });
        
        $('#chalkboard').off('click', '#btnRegister').on('click', '#btnRegister',
            function(){
                if ($('#registration-form').valid()){
                    AJAX.register();
                }else{
                    $('#btnRegister').shake(2,10,150);
                }            
            });
    },
    loadConfirm : function(){
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.confirmText).fadeIn(1000);   
            $('#confirmText').center();
        });
        
        
        
        
    },
    showConfirmer: function(){
        $('#confirmReg').fadeIn(500);
                        
        setTimeout(function(){
            $('#confirmReg').fadeOut(500);
        }, 1500)
    },
    showLogout: function(){
        $('#confirmOut').fadeIn(500);
                        
        setTimeout(function(){
            $('#confirmOut').fadeOut(500);
        }, 1500)
    }
    
}

var AJAX = {
    setup: function(){
        $.ajaxSetup({
            async: false,
            method: 'POST',
            dataType: 'json',
            error: function(){
                alert('err');
            }            
        });
    },
    getChalkboard : function(controller, callback){
        var template = '';
        $.ajax({
            url: Alohamora.baseURL + 'ajax/' + controller,
            method: 'POST',
            async: false,
            success : function (data) {
                if (typeof callback == 'function'){
                    callback(data);
                }              
                template = data;
            },
            error : function(data){
                return data;
            }
        })
        
        return template;
    },
    getParticipants: function(){
        $.getJSON(Alohamora.baseURL + 'user/', function(data){
            var count = 0;            
            while(count < data.length){                
                Alohamora.participant_list[count] = data[count].first_name + " " + data[count].last_name;
                Alohamora.participants[count] = data[count];
                Alohamora.participants[count].full_name = data[count].first_name + " " + data[count].last_name;
                Alohamora.schools[count] = data[count].school;
                count++;
            }
        });
    },
    register : function(){
        $.ajax({
            url: Alohamora.baseURL + 'user/add',
            method: 'POST',
            data : {
                first_name: $('#txtFirstName').val(),
                last_name: $('#txtLastName').val(),
                email_address: $('#txtEmail').val(),
                cellphone_number: $('#txtCellphone').val(),
                school: $('#txtSchool').val()                                        
            },
            success : function (data) {
                $('#txtFirstName, #txtLastName, #txtEmail, #txtCellphone, #txtSchool').val('');     
                UI.loadConfirm(data);                
                setTimeout(function(){
                    UI.loadLogin();
                }, 3000)
            }
        })
        
        
        
        
    //        var person_found = _.findWhere(Alohamora.participants, {
    //            full_name: $('#txtFullName').val()
    //        });
    //        
    //        if(person_found){
    //            //            returning
    //            console.log(person_found);
    //            $.ajax({
    //                url: Alohamora.baseURL + 'scripts/login.php',
    //                data : {                    
    //                    email: Alohamora.Utilities.cleanNoneValues($('#txtEmail').val()),
    //                    cellphone: Alohamora.Utilities.cleanNoneValues($('#txtCellphone').val()),
    //                    school: $('#txtSchool').val(),
    //                    user_number: person_found.user_number
    //                },
    //                success : function (data) {
    //                    //                    update the internal structure
    //                    console.log(data);
    //                    UI.showConfirmer();
    //                    
    //                },
    //                complete : function(){                
    //                }
    //            })
    //        }else{
    //            //            insert
    //            $.ajax({
    //                url: Alohamora.baseURL + 'scripts/login.php',
    //                data : {
    //                    //                            todo fix data insertion
    //                    firstName: $('#txtFullName').val().split(' ').slice(0, -1).join(' '),
    //                    lastName: $('#txtFullName').val().split(' ').slice(-1).join(' '),
    //                    email: Alohamora.Utilities.cleanNoneValues($('#txtEmail').val()),
    //                    cellphone: Alohamora.Utilities.cleanNoneValues($('#txtCellphone').val()),
    //                    school: $('#txtSchool').val()
    //                        
    //                },
    //                success : function (data) {
    //                    console.log(data);
    //                    UI.showConfirmer();                
    //                },
    //                complete : function(){                
    //                }
    //            })
    //        }
    },
    logout: function(){
        var person_found = _.findWhere(Alohamora.participants, {
            full_name: $('#txtFullNameLogout').val()
        });        
        if(person_found){
            console.log(person_found);
            $.ajax({
                url: Alohamora.baseURL + 'scripts/logout.php',
                data : {
                    user_number: person_found.user_number
                },
                success : function (data) {
                    //                    update the internal structure
                    UI.showLogout();
                },
                complete : function(){                
                }
            })
        }
        
    }
};

Alohamora.Validations = {
    validateRegistration: function(){
        $('#registration-form').validate({
            debug: true,
            rules: {
                txtFirstName: "required",
                txtLastName: {
                    required: true
                },
                txtEmail: {
                    email: true
                },                    
                txtCellphone: {
                    regex: '0\\d{10}'
                },
                txtSchool: {
                    required: true
                }
            }
            , 
            messages : {
                txtFirstName : {
                    required: "Required"
                },
                txtEmail: {                    
                    email: 'Valid email address: hello@yahoo.com'
                },
                txtCellphone: {
                    regex: 'Valid phone number: 09xx1234567'
                },
                txtSchool: {   
                    required: "Don't leave it blank"
                }
            }
            
             
        });
    }
    
}

$(document).ready(Alohamora.init);