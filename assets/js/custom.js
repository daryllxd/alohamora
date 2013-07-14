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
        
$.validator.addMethod(
    'in_array',
    
    function(value, element, array) {
        return  (array.indexOf(value) > -1);
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
    participants : {},
    schools : {},
    textboxes: $('#txtFirstName, #txtLastName, #txtEmail, #txtCellphone, #txtSchool'),
    loginText : '',
    logoutText : '',
    confirmText : '',
    init : function(){
        UI.prepare();
        AJAX.getParticipants();
        AJAX.getSchools();
        
        Alohamora.loginText = AJAX.getChalkboard('login', UI.loadLogin);
        Alohamora.confirmText = AJAX.getChalkboard('confirm', UI.loadConfirm);   
        Alohamora.logoutText = AJAX.getChalkboard('logout', UI.loadLogout);
        
        $('#showLogin').on('click', function(){
            UI.loadLogin();
        });        
        $('#showLogout').on('click', function(){
            UI.loadLogout();            
        });       
    },
    prepareUI: function(){
        $('.borderer').css('height', $(window).height());
        $('#registrationSplash').sdVertAlign($('#registrationBackground'), 'm');
        $('#logoutSplash').sdVertAlign($('#logoutBackground'), 'm');
        $('#logoutCenterThis').sdVertAlign($('#logoutSplash'), 'm');
              
        $('#txtFullNameLogout').autocomplete({
            source: Alohamora.participant_list,
            change: function (event, ui) {
                if (!ui.item) {
                    $(this).val('');
                }
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
    loadConfirm : function(){
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.confirmText).fadeIn(1000);   
            $('#confirmText').center();
        });
        
    },
    loadLogin: function(data){                        
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.loginText).fadeIn(1000);            
            Alohamora.Validations.validateRegistration();            
            
            $('#txtSchool').typeahead({
                source: _.pluck(Alohamora.schools, 'school_name'),
                items: 5               
            });
            
            $('#txtFirstName').typeahead({
                source: _.uniq(_.pluck(Alohamora.participants, 'first_name'))
            });
            
            $('#txtLastName').typeahead({
                source: _.uniq(_.pluck(Alohamora.participants, 'last_name'))
            });
            
            $('#chalkboard').off('change', '#txtLastName, #txtFirstName')
            .on('change', '#txtLastName, #txtFirstName',function(){
                var name = $('#txtFirstName').val() + " " + $('#txtLastName').val();
                if (name in Alohamora.participants){
                    $('#txtSchool').val(_.find(Alohamora.schools, {
                        school_id : Alohamora.participants[name].school_id
                    }).school_name);
                    $('#txtEmail').val(Alohamora.participants[name].email_address);
                    $('#txtCellphone').val(Alohamora.participants[name].cellphone_number);            
                }
            });
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
    loadLogout: function(){
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.logoutText).fadeIn(1000);            
            Alohamora.Validations.validateLogout();        
            $('#txtFullName').typeahead({
                source: _.keys(Alohamora.participants)
            });
        });
        $('#chalkboard').off('click', '#btnLogout').on('click', '#btnLogout',
            function(){
                if ($('#logout-form').valid()){
                    AJAX.logout();
                }else{
                    $('#btnLogout').shake(2,10,150);
                }            
            });
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
                var context = data[count];
                var full_name = context.first_name + " " + context.last_name;
                
                Alohamora.participants[full_name] = context;
                Alohamora.participants[full_name].full_name = full_name;
                
                count++;
            }
            
        });
    },
    getSchools: function(){
        $.getJSON(Alohamora.baseURL + 'school/', function(data){
            var count = 0;            
            while(count < data.length){     
                var context = data[count];
                Alohamora.schools[data[count].school_name] = context;
                count++;
            }
        });
    },
    register : function(){        
        $.ajax({
            url: Alohamora.baseURL + 'user/add',
            method: 'POST',
            data : {                
                user_id : Alohamora.Validations.findUser($('#txtFirstName').val() + ' ' + $('#txtLastName').val()),
                first_name: Alohamora.Validations.sanitizeNames($('#txtFirstName').val()),
                last_name: Alohamora.Validations.sanitizeNames($('#txtLastName').val()),
                email_address: $('#txtEmail').val().toLowerCase(),
                cellphone_number: $('#txtCellphone').val(),
                school_id: Alohamora.Validations.findSchool()
            },
            success : function (data) {                                
                $('#txtFirstName, #txtLastName, #txtEmail, #txtCellphone, #txtSchool').val('');  
                
                alert(data);
                
                UI.loadConfirm();                
                setTimeout(function(){
                    UI.loadLogin();
                }, 2000)
            }
        })
               
        
    },
    logout: function(){
        $.ajax({
            url: Alohamora.baseURL + 'transaction/logout',
            method: 'POST',
            data : {                
                user_id : Alohamora.Validations.findUser($('#txtFullName').val())
            },
            success : function (data) {                                
                $('#txtFullname').val('');  
                
                console.log(data);
                
                UI.loadConfirm();                
                setTimeout(function(){
                    UI.loadLogout();
                }, 2000)
            },
            error : function (xhr,err){
                alert(Alohamora.baseURL + 'transaction/logout');
                alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
                console.log("responseText: "+xhr.responseText);
            }
        })
        
    }
};

Alohamora.Validations = {
    findSchool : function(){
        var ret = _.findWhere(Alohamora.schools, {
            school_name :($('#txtSchool').val())
        });  
                
        if (_.isUndefined(ret)){
            return $('#txtSchool').val();
        }else{
            return ret.school_id;
        }       
        
    },
    findUser : function(full_name){        
        if (full_name in Alohamora.participants){
            return Alohamora.participants[full_name].user_id;                   
        }else{
            return -1;
        }
    },
    sanitizeNames : function(str){
        return str.replace(/\w\S*/g, function(txt){
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    },    
    validateLogout : function(){
        $('#logout-form').validate({
            onfocusout: false,
            rules: {
                txtFullName: {
                    in_array: _.keys(Alohamora.participants)
                }
            }
            , 
            messages : {
                txtFullName : {
                    in_array: "Not a participant. Did you log in?"
                }
            }             
        });
    },
    validateRegistration: function(){
        $('#registration-form').validate({
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