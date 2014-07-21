$( document ).ready(function() {

    $('#dadOtherOccuDiv').hide();
    $('#momOtherOccuDiv').hide();

    $('#addParent_dadOccupation').change(function(){
        if($('#addParent_dadOccupation').val() == 'Other') {
            $('#dadOtherOccuDiv').show();
        } else {
            $('#dadOtherOccuDiv').hide();
            $('#addParent_dadOccupation').val("");
        }
    })

    $('#addParent_momOccupation').change(function(){
        if($('#addParent_momOccupation').val() == 'Other') {
            $('#momOtherOccuDiv').show();
        } else {
            $('#momOtherOccuDiv').hide();
            $('#addParent_momOccupation').val("");
        }
    })

    $('#dadTogDiv').hide();
    $('#momTogDiv').hide();
    $('#guardTogDiv').hide();
    $('#emergeTogDiv').hide();

    $('#studentTog').click(function(){
        if($('#studentTog').text() == '[+]') {
            $('#studentTogDiv').show();
            $('#studentTog').text("[-]");
        } else {
            $('#studentTogDiv').hide();
            $('#studentTog').text("[+]");
        }
    })

    $('#fatherTog').click(function(){
        if($('#fatherTog').text() == '[+]') {
            $('#dadTogDiv').show();
            $('#fatherTog').text("[-]");
        } else {
            $('#dadTogDiv').hide();
            $('#fatherTog').text("[+]");
        }
    })

    $('#motherTog').click(function(){
        if($('#motherTog').text() == '[+]') {
            $('#momTogDiv').show();
            $('#motherTog').text("[-]");
        } else {
            $('#momTogDiv').hide();
            $('#motherTog').text("[+]");
        }
    })

    $('#guardianTog').click(function(){
        if($('#guardianTog').text() == '[+]') {
            $('#guardTogDiv').show();
            $('#guardianTog').text("[-]");
        } else {
            $('#guardTogDiv').hide();
            $('#guardianTog').text("[+]");
        }
    })

    $('#emergeTog').click(function(){
        if($('#emergeTog').text() == '[+]') {
            $('#emergeTogDiv').show();
            $('#emergeTog').text("[-]");
        } else {
            $('#emergeTogDiv').hide();
            $('#emergeTog').text("[+]");
        }
    })

    $('#btnSave').click(function() {
        $('form#frmAddParent').submit();
    });

});