$( document ).ready(function() {

    $('#dadOtherOccuDiv').hide();
    $('#momOtherOccuDiv').hide();

    $('#addParent_dadOccupation').change(function(){
        if($('#addParent_dadOccupation').val() == 'Other') {
            $('#dadOtherOccuDiv').show();
        } else {
            $('#dadOtherOccuDiv').hide();
//            $('#addParent_dadOccupation').val("");
        }
    })

    $('#addParent_momOccupation').change(function(){
        if($('#addParent_momOccupation').val() == 'Other') {
            $('#momOtherOccuDiv').show();
        } else {
            $('#momOtherOccuDiv').hide();
//            $('#addParent_momOccupation').val("");
        }
    })

    $('#dadTogDiv').hide();
    $('#momTogDiv').hide();
    $('#guardTogDiv').hide();
    $('#emergeTogDiv').hide();

    $('#studentTog').click(function(){
        if($('#studentTog').text() == '[+]') {
            $('#studentTogDiv').show("fast");
            $('#studentTog').text("[-]");
        } else {
            $('#studentTogDiv').hide("fast");
            $('#studentTog').text("[+]");
        }
    })

    $('#fatherTog').click(function(){
        if($('#fatherTog').text() == '[+]') {
            $('#dadTogDiv').show("fast");
            $('#fatherTog').text("[-]");
        } else {
            $('#dadTogDiv').hide("fast");
            $('#fatherTog').text("[+]");
        }
    })

    $('#motherTog').click(function(){
        if($('#motherTog').text() == '[+]') {
            $('#momTogDiv').show("fast");
            $('#motherTog').text("[-]");
        } else {
            $('#momTogDiv').hide("fast");
            $('#motherTog').text("[+]");
        }
    })

    $('#guardianTog').click(function(){
        if($('#guardianTog').text() == '[+]') {
            $('#guardTogDiv').show("fast");
            $('#guardianTog').text("[-]");
        } else {
            $('#guardTogDiv').hide("fast");
            $('#guardianTog').text("[+]");
        }
    })

    $('#emergeTog').click(function(){
        if($('#emergeTog').text() == '[+]') {
            $('#emergeTogDiv').show("fast");
            $('#emergeTog').text("[-]");
        } else {
            $('#emergeTogDiv').hide("fast");
            $('#emergeTog').text("[+]");
        }
    })

    $('#btnSave').click(function() {
        if(isValidForm()){
            $('form#frmAddParent').submit();
        }
    });

});

function isValidForm(){

    var validator = $("#frmAddParent").validate({

        rules: {
            'addParent[stuSurname]' : {
                required:true,
                maxlength: 35
            },
            'addParent[stuOtherNames]' : {
                required:true,
                maxlength: 50
            }

        },
        messages: {
            'addParent[stuSurname]' : {
                required: lang_fieldRequired,
                maxlength: lang_exceed35Charactors

            },
            'addParent[stuOtherNames]' : {
                required: lang_fieldRequired,
                maxlength: lang_exceed50Charactors
            }

        },

        errorPlacement: function(error, element) {
            error.appendTo(element.next('div.errorHolder'));

        }

    });
    return true;
}