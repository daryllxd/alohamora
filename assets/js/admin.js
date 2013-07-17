"use strict";

var Alohamora = {
    baseURL: 'http://localhost/alohamora/',
    participants : {},
    schools : {},
    textboxes: $('#txtFirstName, #txtLastName, #txtEmail, #txtCellphone, #txtSchool'),
    printText : '',
    logoutText : '',
    confirmText : '',
    init : function(){
        $('#chalkboard').shake(2,10,150);
        
        $('#link-home, #link-print, #link-spreadsheet').on('click', function(){
            $('li.active').removeClass('active');
            $(this).parent().addClass('active');
            UI.loadPrint();
        });
        
        //        UI.prepare();
        //        AJAX.setup();
        //        AJAX.getParticipants();
        //        AJAX.getSchools();
        //        
        Alohamora.printText = AJAX.getChalkboard('print');
        
        
        
//        alert(Alohamora.printText);
    //        Alohamora.confirmText = AJAX.getChalkboard('confirm', UI.loadConfirm);   
    //        Alohamora.logoutText = AJAX.getChalkboard('logout', UI.loadLogout);
    //        
    //        $('#showLogin').on('click', function(){
    //            UI.loadLogin();
    //        });        
    //        $('#showLogout').on('click', function(){
    //            UI.loadLogout();            
    //        });       
    }
};



var UI = {
    prepare: function(){
        $(window).bind('resize', function() {
            $('#chalkboard').center();
        });
    },    
    loadConfirm : function(callback){
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.confirmText).fadeIn(1000);   
            $('#confirmText').center();
        });
        
        if (typeof callback == 'function'){
            setTimeout(function(){
                callback();
            }, 2000)
        }   
        
        
    },
    loadPrint: function(){
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.printText).fadeIn(1000);
            
            $('#btnPrint').on('click', function(){AJAX.print()});
            
            
            
            
            
            
            }); 
            
            
            
            
        
    },
    loadLogin: function(data){                        
        $('#chalkboard').children().fadeOut(1000, function(){
            $('#chalkboard').empty().append(Alohamora.loginText).fadeIn(1000);            
            Alohamora.Utilities.validateRegistration();            
            
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
            Alohamora.Utilities.validateLogout();        
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
            method: 'GET',
            dataType: 'text',
            error: function(xhr, ajaxOptions, thrownError){
                console.log(xhr);
                alert(Alohamora.baseURL + 'transaction/logout');
                alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
                console.log("responseText: "+xhr.responseText);
            }            
        });
    },
    print : function(){
        $.ajax({
            url : Alohamora.baseURL + 'report',
            success: function(data){
                window.open(Alohamora.baseURL + 'assets/doc.pdf');
//                alert(data);
            }
        });
        
    },
    getChalkboard : function(controller, callback){
        var template = '';
        $.ajax({
            url: Alohamora.baseURL + 'ajax/' + controller,
            async: false,
            success : function (data) {
                if (typeof callback == 'function'){
                    callback(data);
                }              
                template = data;
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
        $.post(Alohamora.baseURL + 'user/add',{                
            user_id : Alohamora.Utilities.findUser($('#txtFirstName').val() + ' ' + $('#txtLastName').val()),
            first_name: Alohamora.Utilities.sanitizeNames($('#txtFirstName').val()),
            last_name: Alohamora.Utilities.sanitizeNames($('#txtLastName').val()),
            email_address: $('#txtEmail').val().toLowerCase(),
            cellphone_number: $('#txtCellphone').val(),
            school_id: Alohamora.Utilities.findSchool()
        }, function (data) {                                
            $('#txtFirstName, #txtLastName, #txtEmail, #txtCellphone, #txtSchool').val('');  
                
            alert(data);
                
            UI.loadConfirm(UI.loadLogin);                
        }
        );
    },
    logout: function(){
        $.post(Alohamora.baseURL + 'transaction/logout', {                
            user_id : Alohamora.Utilities.findUser($('#txtFullName').val())
        }, function (data) {                                
            $('#txtFullname').val('');
            
            UI.loadConfirm(UI.loadLogout);    
        }
        )
    }
};

Alohamora.Utilities = {
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
        return $.trim(str.replace(/\w\S*/g, function(txt){            
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }));
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
                txtFirstName: {
                    required: true,
                    regex: "[a-zA-Z]+"
                },
                txtLastName: {
                    required: true,
                    regex: "[a-zA-Z]+"
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
                    required: 'Please enter your first name.',
                    regex: 'Must be letters only.'
                },
                txtLastName : {
                    required: 'Please enter your last name.',
                    regex: 'Must be letters only.'
                },
                txtEmail: {                    
                    email: 'Valid email address: hello@yahoo.com'
                },
                txtCellphone: {
                    regex: 'Valid phone number: 09xx1234567'
                },
                txtSchool: {   
                    required: 'Please enter your school name.'
                }
            }
            
             
        });
    }
    
}

$(document).ready(Alohamora.init);