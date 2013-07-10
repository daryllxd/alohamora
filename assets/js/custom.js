/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

"use strict";

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
        $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
        $(window).scrollLeft()) + "px");
    return this;
}

var Rigel = {
    baseURL: 'http://localhost/alohamora/',
    //Production baseURL: 'http://192.168.1.10/rigel/',    
    participants : [],
    participant_list : [],
    schools : [],
    textboxes: $('#txtFullName, #txtEmail, #txtCellphone, #txtSchool'),
    init : function(){
        $(window).bind('resize', function() {
            $('#chalkboard').center();
        });
        
        $('#showLogin').on('click', function(){
            $('#chalkboard').children().fadeOut(1000, function(){
                $('#chalkboard').empty().append('<h1>new_content</h1>').fadeIn(1000);
            });
        });
        
        $('#showLogout').on('click', function(){
            $('#chalkboard').children().fadeOut(1000, function(){
                $('#chalkboard').empty().append('<h1>new_content</h1>').fadeIn(1000);
            });
        });
        
        
        $('#btnRegister').on('click', function(){
            $.ajax({
                url: Rigel.baseURL + 'user',
                method: 'POST',
                data : {
                    intent: 'add'
                },
                success : function (data) {
                    //                    update the internal structure
                    alert(data);
                },
                complete : function(){                
                }
            })
            
            
        });
        
    //        Rigel.AJAX.getParticipants();
    //        Rigel.textboxes.tooltip();        
    //        Rigel.prepareUI();
    //        $('#frmRegistration').validate({
    //            rules: {
    //                txtFullName: "required",
    //                txtEmail: {                    
    //                    email: true
    //                },
    //                txtCellphone: {
    //                    required : true
    //                },
    //                txtSchool: "required"
    //            },
    //            messages : {
    //                txtFullName: "Required",
    //                txtEmail: {
    //                    email: "Must be email"
    //                },
    //                txtCellphone: {
    //                    required : "Required"
    //                },
    //                txtSchool: "Required"
    //            },
    //            submitHandler: function(form){                
    //                Rigel.AJAX.register();                                
    //                Rigel.textboxes.val('');
    //                return false;
    //            },
    //            invalidHandler: function(form){
    //            },
    //            errorClass: "invalid",
    //            onfocusout: false
    //            
    //            
    //        });
    //        
    //        $('#frmLogout').validate({
    //            submitHandler: function(form){
    //                Rigel.AJAX.logout();                                
    //                $('#txtFullNameLogout').val('');
    //                return false;
    //            }
    //        });
    //        
    //        
    //        Rigel.bindEvents();
        
    },
    prepareUI: function(){
        $('.borderer').css('height', $(window).height());
        $('#registrationSplash').sdVertAlign($('#registrationBackground'), 'm');
        $('#logoutSplash').sdVertAlign($('#logoutBackground'), 'm');
        $('#logoutCenterThis').sdVertAlign($('#logoutSplash'), 'm');
        $('#txtSchool').autocomplete({
            source:Rigel.schools
        });        
        $('#txtFullNameLogout').autocomplete({
            source:Rigel.participant_list,
            change: function (event, ui) {
                if (!ui.item) {
                    $(this).val('');
                }
            }
        });
        
        $('#txtFullName').autocomplete({
            source: Rigel.participant_list,
            select: function(event, ui){
                $('#txtFullName').val(ui.item);                
                var person_found = _.findWhere(Rigel.participants, {
                    full_name: ui.item.value
                });
                if(person_found){                
                    $('#txtSchool').val(person_found.school);
                    $('#txtCellphone').val(person_found.cellphone_number);
                    $('#txtEmail').val(person_found.email);
                };
            //                Rigel.events.findOnBlur();
            }
        });
    },
    bindEvents: function(){
        //        Rigel.events.findOnBlur();
        
        $('#closeModal').click(function(){
            $('#myModal').modal('hide');
        });
        
        //        $('#frmRegistration').submit(function(){
        //            Rigel.AJAX.register();            
        //            return false;
        //        });
        
        $('#btnPrint').click(function(){
            //            $('#greeting').text('Hi!' );
            //            $('#greeting2').text('Good morning!');
            window.open(Rigel.baseURL + 'scripts/print.php?name=' + $('#txtPrintName').val());
            
            return false;
        });
    }
}

Rigel.UI = {
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

Rigel.AJAX = {
    setup: function(){
        $.ajaxSetup({
            async: false,
            method: 'GET',
            dataType: 'json',
            error: function(){
                alert('err');
            }            
        });
    },
    getParticipants: function(){
        $.getJSON(Rigel.baseURL + 'scripts/getParticipants.php', function(data){
            var count = 0;            
            while(count < data.length){                
                Rigel.participant_list[count] = data[count].first_name + " " + data[count].last_name;
                Rigel.participants[count] = data[count];
                Rigel.participants[count].full_name = data[count].first_name + " " + data[count].last_name;
                Rigel.schools[count] = data[count].school;
                count++;
            }
        });
    },
    register : function(){
        var person_found = _.findWhere(Rigel.participants, {
            full_name: $('#txtFullName').val()
        });
        
        if(person_found){
            //            returning
            console.log(person_found);
            $.ajax({
                url: Rigel.baseURL + 'scripts/login.php',
                data : {                    
                    email: Rigel.Utilities.cleanNoneValues($('#txtEmail').val()),
                    cellphone: Rigel.Utilities.cleanNoneValues($('#txtCellphone').val()),
                    school: $('#txtSchool').val(),
                    user_number: person_found.user_number
                },
                success : function (data) {
                    //                    update the internal structure
                    console.log(data);
                    Rigel.UI.showConfirmer();
                    
                },
                complete : function(){                
                }
            })
        }else{
            //            insert
            $.ajax({
                url: Rigel.baseURL + 'scripts/login.php',
                data : {
                    //                            todo fix data insertion
                    firstName: $('#txtFullName').val().split(' ').slice(0, -1).join(' '),
                    lastName: $('#txtFullName').val().split(' ').slice(-1).join(' '),
                    email: Rigel.Utilities.cleanNoneValues($('#txtEmail').val()),
                    cellphone: Rigel.Utilities.cleanNoneValues($('#txtCellphone').val()),
                    school: $('#txtSchool').val()
                        
                },
                success : function (data) {
                    console.log(data);
                    Rigel.UI.showConfirmer();                
                },
                complete : function(){                
                }
            })
        }
    },
    logout: function(){
        var person_found = _.findWhere(Rigel.participants, {
            full_name: $('#txtFullNameLogout').val()
        });        
        if(person_found){
            console.log(person_found);
            $.ajax({
                url: Rigel.baseURL + 'scripts/logout.php',
                data : {
                    user_number: person_found.user_number
                },
                success : function (data) {
                    //                    update the internal structure
                    Rigel.UI.showLogout();
                },
                complete : function(){                
                }
            })
        }
        
    }
};

Rigel.Utilities = {
    cleanNoneValues: function(str){
        if (str == ''){
            return '(none)';
        }else{
            return str;
        }
    },
    cleanNames: function(str){
        
    }
    
}


$(document).ready(Rigel.init);
