const setListeners = () => {
    // kural grubuna kural ekler
    $('#str-container').on('click', '#addBtn', (eventData) => {
        let ruleLen = eventData.currentTarget.parentElement.parentElement.parentElement.children.length;
        let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.children[ruleLen - 1].id;
        let strId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;
        let ruleIdCount = parseInt(ruleId.split('-')[1]);
        let strIdCount = parseInt(strId.split('-')[1]);
        let cloned = $(`#${ruleId}`).clone()
        cloned.prop('id', `rule-${ruleIdCount + 1}`);
        cloned.find('#period1').attr('name', `period1[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find('#ind1').attr('name', `ind1[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find('#comperator').attr('name', `comperator[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find('#ind2').attr('name', `ind2[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find('#val2').attr('name', `val2[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find('#min_val').attr('name', `min_val[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find('#max_val').attr('name', `max_val[${strIdCount}][${ruleIdCount + 1}]`)
        cloned.find(`[id*="rad_ind"]`).attr('name', `input_type[${strIdCount}][${ruleIdCount + 1}]`).attr('id', `rad_ind-${strIdCount}-${ruleIdCount + 1}`)
        cloned.find(`[id*="rad_val"]`).attr('name', `input_type[${strIdCount}][${ruleIdCount + 1}]`).attr('id', `rad_val-${strIdCount}-${ruleIdCount + 1}`)
        cloned.find(`[for*="rad_ind"]`).attr('for', `rad_ind-${strIdCount}-${ruleIdCount + 1}`)
        cloned.find(`[for*="rad_val"]`).attr('for', `rad_val-${strIdCount}-${ruleIdCount + 1}`)
        $(`#${strId} #ruleGroup`).append(cloned);
    });

    //kural grubundaki kuralı kopyalar
    $('#str-container').on('click', '#copyBtn', (eventData) => {
        //strategy clone
        let strLen = $('#str-container').children().length
        let lastStrId = $('#str-container').children()[strLen - 1].id;

        let strategyId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;
        let currStrCount = parseInt(strategyId.split('-')[1])
        let strCount = parseInt(lastStrId.split('-')[1]);
        let clonedStr = $(`#${strategyId}`).clone()
        clonedStr.prop('id', `strategy-${strCount + 1}`)

        $('#str-container').append(clonedStr);

        let ruleLen = eventData.currentTarget.parentElement.parentElement.parentElement.children.length;
        for (let i = 0; i < ruleLen; i++) {
            let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.children[i].id;
            if (ruleId.split('-')[0] != 'rule') {
                let andOr = $(`#${strategyId} #andOr`).find(":selected").val();
                $(`#strategy-${strCount + 1} #andOr`).val(andOr).attr('name', `andOr[${strCount + 1}]`);

                let sellBuy = $(`#${strategyId} #sellBuy`).find(":selected").val();
                $(`#strategy-${strCount + 1} #sellBuy`).val(sellBuy).attr('name', `sellBuy[${strCount + 1}]`);
                continue;
            }
            let ruleIdCount = parseInt(ruleId.split('-')[1]);
            //copy rule values
            let period1 = $(`#${strategyId} #${ruleId} #period1`).children('option:selected').val();
            let ind1 = $(`#${strategyId} #${ruleId} #ind1`).children().find(":selected").val();
            let ind2 = $(`#${strategyId} #${ruleId} #ind2`).children().find(":selected").val();
            let comperator = $(`#${strategyId} #${ruleId} #comperator`).find(":selected").val();
            let val2 = $(`#${strategyId} #${ruleId} #val2`).val();
            let min_val = $(`#${strategyId} #${ruleId} #min_val`).val();
            let max_val = $(`#${strategyId} #${ruleId} #max_val`).val();
            let input_type = $(`#${strategyId} #${ruleId} #rad_val-${strCount}-${ruleIdCount}`).val();
            //paste rule values
            $(`#strategy-${strCount + 1} #${ruleId} #period1`).val(period1).attr('name', `period1[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #ind1`).val(ind1).attr('name', `ind1[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #ind2`).val(ind2).attr('name', `ind2[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #comperator`).val(comperator).attr('name', `comperator[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #val2`).val(val2).attr('name', `val2[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #min_val`).val(min_val).attr('name', `min_val[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #max_val`).val(max_val).attr('name', `max_val[${strCount + 1}][${ruleIdCount}]`);
            $(`#strategy-${strCount + 1} #${ruleId} #rad_ind-${currStrCount}-${ruleIdCount}`).val(input_type).attr('name', `input_type[${strCount + 1}][${ruleIdCount}]`).attr('id', `rad_ind-${strCount + 1}-${ruleIdCount}`);
            $(`#strategy-${strCount + 1} #${ruleId} #rad_val-${currStrCount}-${ruleIdCount}`).val(input_type).attr('name', `input_type[${strCount + 1}][${ruleIdCount}]`).attr('id', `rad_val-${strCount + 1}-${ruleIdCount}`);
            $(`#strategy-${strCount + 1} #${ruleId} [for*="rad_ind-${currStrCount}-${ruleIdCount}"]`).attr('for', `rad_ind-${strCount + 1}-${ruleIdCount}`)
            $(`#strategy-${strCount + 1} #${ruleId} [for*="rad_val-${currStrCount}-${ruleIdCount}"]`).attr('for', `rad_val-${strCount + 1}-${ruleIdCount}`)
        }
    })

    //kural grubunu siler
    $('#str-container').on('click', '#strRemoveBtn', (eventData) => {
        let strId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;
        let strLen = $('.strategy').length;
        if (strLen > 1) {
            $(`#${strId}`).remove();
        } else {
            alert('Tek strateji silinemez')
        }
    });

    //kural grubundan kural siler
    $('#str-container').on('click', '#removeBtn', (eventData) => {
        let ruleId = eventData.currentTarget.parentElement.parentElement.id;
        let strId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;

        let ruleLen = $(`#${strId} .rule`).length;
        if (ruleLen > 1) {
            $(`#${strId} #${ruleId}`).remove();
        } else {
            alert('Tek koşul silinemez')
        }
    });

    // Yeni kural grubu ekler
    $('.ruleContainer').on('click', '#addStrBtn', (eventData) => {
        //strategy add
        let strLen = $('#str-container').children().length
        let lastStrId = $('#str-container').children()[strLen - 1].id;
        let strCount = parseInt(lastStrId.split('-')[1]);
        let cloned = $(`#str-container #strategy-${strCount}`).clone()
        let childrenLen = cloned.children('[id*="rule"]').find('[id*="rule"]').length;
        let rules = cloned.children('[id*="rule"]').find('[id*="rule"]');
        for (let i = 0; i < childrenLen; i++) {
            if (i != 0) {
                rules[i].remove();
            }
        }
        cloned.prop('id', `strategy-${strCount + 1}`);
        cloned.find(`#period1`).attr('name', `period1[${strCount + 1}][0]`);
        cloned.find(`#ind1`).attr('name', `ind1[${strCount + 1}][0]`);
        cloned.find(`#ind2`).attr('name', `ind2[${strCount + 1}][0]`);
        cloned.find(`#comperator`).attr('name', `comperator[${strCount + 1}][0]`);
        cloned.find('#val2').attr('name', `val2[${strCount + 1}][0]`)
        cloned.find('#min_val').attr('name', `min_val[${strCount + 1}][0]`)
        cloned.find('#max_val').attr('name', `max_val[${strCount + 1}][0]`)
        cloned.find('#sellBuy').attr('name', `sellBuy[${strCount + 1}]`)
        cloned.find('#andOr').attr('name', `andOr[${strCount + 1}]`)
        //todo rule idyi düzelt
        cloned.find(`#rad_ind-${strCount}-0`).attr('name', `input_type[${strCount + 1}][0]`).attr('id', `rad_ind-${strCount + 1}-0`)
        cloned.find(`#rad_val-${strCount}-0`).attr('name', `input_type[${strCount + 1}][0]`).attr('id', `rad_val-${strCount + 1}-0`)
        cloned.find(`[for="rad_ind-${strCount}-0"]`).attr('for', `rad_ind-${strCount + 1}-0`)
        cloned.find(`[for="rad_val-${strCount}-0"]`).attr('for', `rad_val-${strCount + 1}-0`)
        $('#str-container').append(cloned);
    });

    //radio button listeners
    $('.ruleContainer').on('click', '[id*="rad_ind"], [for*="rad_ind"]', (eventData) => {
        //indicator radio
        let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;
        let strId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.id;
        $(`#${strId} #${ruleId} #val_div`).hide();
        $(`#${strId} #${ruleId} #bet_div`).hide();
        $(`#${strId} #${ruleId} #ind2`).show();
    })

    $('.ruleContainer').on('click', '[id*="rad_val"], [for*="rad_val"]', (eventData) => {
        //value radio
        let ruleId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;
        let strId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.id;
        $(`#${strId} #${ruleId} #val_div`).show();
        $(`#${strId} #${ruleId} #bet_div`).hide();
        $(`#${strId} #${ruleId} #ind2`).hide();
    })

    // karşılaştırma dropdown listener
    $('.ruleContainer').on('change', '#comperator', (eventData) => {
        let ruleId = eventData.currentTarget.parentElement.parentElement.id;
        let ruleIdCount = parseInt(ruleId.split('-')[1])
        let strId = eventData.currentTarget.parentElement.parentElement.parentElement.parentElement.id;
        let strCount = parseInt(strId.split('-')[1])
        let selectedOperator = $(`#${strId} #${ruleId} #comperator option:selected`).val();
        if (selectedOperator == 'arasında') {
            $(`#${strId} #${ruleId} #val_div`).hide();
            $(`#${strId} #${ruleId} #bet_div`).show().css('display', 'flex');;
            $(`#${strId} #${ruleId} #ind2`).hide();
            $(`#${strId} #${ruleId} #rad_val-${strCount}-${ruleIdCount}`).prop('checked', true);
            $(`#${strId} #${ruleId} #rad_ind-${strCount}-${ruleIdCount}`).attr('disabled', true);
            $(`#${strId} #${ruleId} #rad_val-${strCount}-${ruleIdCount}`).attr('disabled', true);
        } else if ($(`#${strId} #${ruleId} #bet_div`).is(':visible')) {
            $(`#${strId} #${ruleId} #val_div`).show();
            $(`#${strId} #${ruleId} #bet_div`).hide();
            $(`#${strId} #${ruleId} #ind2`).hide();
            $(`#${strId} #${ruleId} #rad_ind-${strCount}-${ruleIdCount}`).attr('disabled', false);
            $(`#${strId} #${ruleId} #rad_val-${strCount}-${ruleIdCount}`).attr('disabled', false);
            $(`#${strId} #${ruleId} #rad_val-${strCount}-${ruleIdCount}`).prop('checked', true);
        } else {
            $(`#${strId} #${ruleId} #rad_ind-${strCount}-${ruleIdCount}`).attr('disabled', false);
            $(`#${strId} #${ruleId} #rad_val-${strCount}-${ruleIdCount}`).attr('disabled', false);
        }
    })
    $(`#bet_div`).hide();
}

