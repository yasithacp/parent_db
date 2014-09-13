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
            },
            'addParent[curClass]' : {
                required:true,
                maxlength: 10
            },
            'addParent[dateOfBirth]' : {
                required:true
//                maxlength: 50
            },
            'addParent[religion]' : {
                required:true,
                maxlength: 20
            },
            'addParent[stuAdmissionNo]' : {
                required:true,
                maxlength: 10
            },
            'addParent[classOfAdmission]' : {
                required:true,
                maxlength: 10
            },
            'addParent[race]' : {
                required:true,
                maxlength: 20
            },
            'addParent[dateOfAdmission]' : {
                required:true
//                maxlength: 50
            },
            'addParent[house]' : {
                required:true,
                maxlength: 20
            },
            'addParent[medium]' : {
                required:true,
                maxlength: 10
            },
            'addParent[resAddress]' : {
                required:true,
                maxlength: 255
            },
            'addParent[scholarFromOther]' : {
                required:false
            },
            'addParent[scholarFromRoyal]' : {
                required:false
            },
            'addParent[noScholar]' : {
                required:false
            },
            'addParent[dadName]' : {
                required:true,
                maxlength: 100
            },
            'addParent[dadOccupation]' : {
                required:true,
                maxlength: 50
            },
            'addParent[dadOtherOccupation]' : {
                required:false,
                maxlength: 50
            },
            'addParent[dadDesignation]' : {
                required:false,
                maxlength: 50
            },
            'addParent[dadCompany]' : {
                required:false,
                maxlength: 50
            },
            'addParent[isFatherOldBoy]' : {
                required:false  
            },
            'addParent[dadObMemId]' : {
                required:false,
                maxlength: 50
            },
            'addParent[dadOfficeAddress]' : {
                required:false,
                maxlength: 255
            },
            'addParent[dadMobileNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[dadOfficeNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[dadResidentialNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[dadEmail]' : {
                required:false,
                maxlength: 100
            },
            'addParent[momName]' : {
                required:true,
                maxlength: 100
            },
            'addParent[momOccupation]' : {
                required:false,
                maxlength: 50
            },
            'addParent[momOtherOccupation]' : {
                required:false,
                maxlength: 50
            },
            'addParent[momDesignation]' : {
                required:false,
                maxlength: 50
            },
            'addParent[momCompany]' : {
                required:false,
                maxlength: 50
            },
            'addParent[momAdmissionNumber]' : {
                required:false,
                maxlength: 20
            },
            'addParent[momOfficeAddress]' : {
                required:false,
                maxlength: 255
            },
            'addParent[momMobileNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[momOfficeNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[momResidentialNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[momEmail]' : {
                required:false,
                maxlength: 100
            },
            'addParent[guardianName]' : {
                required:false,
                maxlength: 100
            },
            'addParent[guardianDesignation]' : {
                required:false,
                maxlength: 20
            },
            'addParent[guardianRelationship]' : {
                required:false,
                maxlength: 20
            },
            'addParent[guardianHomeAddress]' : {
                required:false,
                maxlength: 255
            },
            'addParent[guardianOfficeAddress]' : {
                required:false,
                maxlength: 255
            },
            'addParent[guardianMobileNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[guardianOfficeNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[guardianResidentialNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[guardianEmail]' : {
                required:false,
                maxlength: 100
            },
            'addParent[emergencyContactName]' : {
                required:true,
                maxlength: 100
            },
            'addParent[emergencyContactRelationship]' : {
                required:true,
                maxlength: 20
            },
            'addParent[emergencyContactAddress]' : {
                required:true,
                maxlength: 255
            },
            'addParent[emergencyContactMobileNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[emergencyContactOfficeNo]' : {
                required:false,
                min: 0,
                max: 9999999999
            },
            'addParent[emergencyContactResidentialNo]' : {
                required:false,
                min: 0,
                max: 9999999999
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
            },
            'addParent[curClass]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed10Charactors
            },
            'addParent[dateOfBirth]' : {
                required:lang_fieldRequired
//                maxlength: 50
            },
            'addParent[religion]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed20Charactors
            },
            'addParent[stuAdmissionNo]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed10Charactors
            },
            'addParent[classOfAdmission]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed10Charactors
            },
            'addParent[race]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed20Charactors
            },
            'addParent[dateOfAdmission]' : {
                required:lang_fieldRequired
//                maxlength: 50
            },
            'addParent[house]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed20Charactors
            },
            'addParent[medium]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed10Charactors
            },
            'addParent[resAddress]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed255Charactors
            },
            'addParent[scholarFromOther]' : {
            },
            'addParent[scholarFromRoyal]' : {
            },
            'addParent[noScholar]' : {
            },
            'addParent[dadName]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed100Charactors
            },
            'addParent[dadOccupation]' : {
                required: lang_fieldRequired,
                maxlength: lang_exceed50Charactors
            },
            'addParent[dadOtherOccupation]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[dadDesignation]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[dadCompany]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[isFatherOldBoy]' : {
            },
            'addParent[dadObMemId]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[dadOfficeAddress]' : {
                maxlength: lang_exceed255Charactors
            },
            'addParent[dadMobileNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[dadOfficeNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[dadResidentialNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[dadEmail]' : {
                maxlength: lang_exceed100Charactors
            },
            'addParent[momName]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed100Charactors
            },
            'addParent[momOccupation]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[momOtherOccupation]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[momDesignation]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[momCompany]' : {
                maxlength: lang_exceed50Charactors
            },
            'addParent[momAdmissionNumber]' : {
                maxlength: lang_exceed20Charactors
            },
            'addParent[momOfficeAddress]' : {
                maxlength: lang_exceed255Charactors
            },
            'addParent[momMobileNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[momOfficeNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[momResidentialNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[momEmail]' : {
                maxlength: lang_exceed100Charactors
            },
            'addParent[guardianName]' : {
                maxlength: lang_exceed100Charactors
            },
            'addParent[guardianDesignation]' : {
                maxlength: lang_exceed20Charactors
            },
            'addParent[guardianRelationship]' : {
                maxlength: lang_exceed20Charactors
            },
            'addParent[guardianHomeAddress]' : {
                maxlength: lang_exceed255Charactors
            },
            'addParent[guardianOfficeAddress]' : {
                maxlength: lang_exceed255Charactors
            },
            'addParent[guardianMobileNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[guardianOfficeNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[guardianResidentialNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[guardianEmail]' : {
                maxlength: lang_exceed100Charactors
            },
            'addParent[emergencyContactName]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed100Charactors
            },
            'addParent[emergencyContactRelationship]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed20Charactors
            },
            'addParent[emergencyContactAddress]' : {
                required:lang_fieldRequired,
                maxlength: lang_exceed255Charactors
            },
            'addParent[emergencyContactMobileNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[emergencyContactOfficeNo]' : {
//                min: 0,
//                max: 9999999999
            },
            'addParent[emergencyContactResidentialNo]' : {
//                min: 0,
//                max: 9999999999
            }

        },

        errorPlacement: function(error, element) {
            error.appendTo(element.next('div.errorHolder'));

        }

    });
    return true;
}