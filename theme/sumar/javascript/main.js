function colorear(div){
    $(div).css('color', 'red');
}

var items = ['#fitem_id_profile_field_tconvenio', '#fitem_id_efectores', '#fitem_id_profile_field_estOtro', '#fitem_id_profile_field_orgGubernamental', '#fitem_id_profile_field_cargoOtro', '#fitem_id_profile_field_listaprof', '#fitem_id_profile_field_orgOtro'];


items.forEach(function(item){
    $(item).addClass('required');
    $(item+' label').append('<img class="req" title="Campo obligatorio" alt="Campo obligatorio" src="'+M.cfg.wwwroot+'/theme/sumar/pix_core/req.gif">');
})



function requerido(div){    
    validado = true;
    if ($(div).css('display') == 'block') {
        if ($(div+' select').length) {
            $(div+' select').each(function(item){
                validado = validar(this);
            })
        };
        if ($(div+' input').length) {
            $(div+' input').each(function(item){
                validado = validar(this);
            })
        };
    };
    return validado;
}




$('#mform1').submit(function(event) {
    var link = window.location.href;
    var bandera = true;
    var i = 0;
    items.forEach(function(item){
        var flag = requerido(item);
        console.log(flag);
        if (flag == false) {
            //console.log(item);
            if (!$(item+' .erroreabc').length) {

                $(item).prepend("<span class='erroreabc' style='color:#A00; margin-left:150px;'>Obligatorio</span>");
            };
            bandera = false;
        }else{
            if ($(item+' .erroreabc').length) {

                $(item+' .erroreabc').remove();
               
            };
        }
    })
   if (bandera == false) {
        return false;    
   }
});


$("form#mform1.mform").ready(function(){

    $("#fgroup_id_firstaccess_grp").show();
    $("#fgroup_id_firstaccess_grp .felement").show();

    $("#fgroup_id_lastaccess_grp").show();
    $("#fgroup_id_lastaccess_grp .felement").show();

    $("#fgroup_id_lastlogin_grp").show();
    $("#fgroup_id_lastlogin_grp .felement").show();

    $("#fgroup_id_timemodified_grp").show();
    $("#fgroup_id_timemodified_grp .felement").show();
})

$("form#mform1.mform").click(function(){

    $("#fgroup_id_firstaccess_grp").show();
    $("#fgroup_id_firstaccess_grp .felement").show();

    $("#fgroup_id_lastaccess_grp").show();
    $("#fgroup_id_lastaccess_grp .felement").show();

    $("#fgroup_id_lastlogin_grp").show();
    $("#fgroup_id_lastlogin_grp .felement").show();

    $("#fgroup_id_timemodified_grp").show();
    $("#fgroup_id_timemodified_grp .felement").show();
})

$("form#mform1.mform").change(function(){

    $("#fgroup_id_firstaccess_grp").show();
    $("#fgroup_id_firstaccess_grp .felement").show();

    $("#fgroup_id_lastaccess_grp").show();
    $("#fgroup_id_lastaccess_grp .felement").show();

    $("#fgroup_id_lastlogin_grp").show();
    $("#fgroup_id_lastlogin_grp .felement").show();

    $("#fgroup_id_timemodified_grp").show();
    $("#fgroup_id_timemodified_grp .felement").show();
})

if ($("#page-backup-backup form#mform2.mform").length) {
    //console.log('existe');    
    $(document).ready(function(){
        setTimeout(function(){
            $("form#mform2.mform").show();
         }, 3000);
    })

    $(document).click(function(){

        $("form#mform2.mform").show();
    })

    $("form#mform2.mform").change(function(){

        $("form#mform2.mform").show();
    })


    var formback = document.querySelector("#page-backup-backup form#mform2.mform");
    formback.addEventListener('onclick', mostrarBack);
    document.addEventListener('DOMContentLoaded', mostrarBack);
    formback.addEventListener('onchange', mostrarBack);
}

if ($("#page-backup-import form.mform").length) {
    //console.log('existe');    
    $(document).ready(function(){
        setTimeout(function(){
            $("#page-backup-import form.mform").show();
         }, 3000);
    })

    $(document).click(function(){

        $("#page-backup-import form.mform").show();
    })

    $("#page-backup-import form.mform").change(function(){

        $("#page-backup-import form.mform").show();
    })
}
function mostrarBack(){
    $("#page-backup-backup form#mform2.mform").show();    
}

function validar(item){
   if (item.value == '') {
        return false;
   };
   return true;
}



$(document).ready(function(){
         $('.bxslider').bxSlider({auto:true, pause:6000});
        });