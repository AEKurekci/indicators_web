const setListeners = () => {
    $('#ruleGroup').on('click', '#addBtn', (eventData) => {
        let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.id;
        let idCount = $('.rule').length;
        $(`#${ruleId}`).clone().prop('id', `rule-${idCount}`).appendTo('#ruleGroup');
    });

    $('#ruleGroup').on('click', '#copyBtn', (eventData) => {
        let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.id;
        let period1 = $(`#${ruleId} #period1`).children('option:selected').val();
        let period2 = $(`#${ruleId} #period2`).children('option:selected').val();
        let ind1 = $(`#${ruleId} #ind1`).children().find(":selected").val();
        let ind2 = $(`#${ruleId} #ind2`).children().find(":selected").val();
        let comperator = $(`#${ruleId} #comperator`).children().find(":selected").val();
        let andOr = $(`#${ruleId} #andOr`).find(":selected").val();
        let idCount = $('.rule').length;
        $(`#${ruleId}`).clone().prop('id', `rule-${idCount}`).appendTo('#ruleGroup');
        $(`#rule-${idCount} #period1`).val(period1);
        $(`#rule-${idCount} #period2`).val(period2);
        $(`#rule-${idCount} #ind1`).val(ind1);
        $(`#rule-${idCount} #ind2`).val(ind2);
        $(`#rule-${idCount} #comperator`).val(comperator);
        $(`#rule-${idCount} #andOr`).val(andOr);
    })

    $('#ruleGroup').on('click', '#removeBtn', (eventData) => {
        let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.id;
        let ruleLen = $('.rule').length;
        if (ruleLen > 1) {
            $(`#${ruleId}`).remove();
        } else {
            alert('Tek kural silinemez')
        }
    });
}