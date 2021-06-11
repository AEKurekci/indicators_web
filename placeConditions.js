const placed = (data, per, page) => {
    let dataLen = data.length
    if (dataLen == 0) {
        $('#condsTab').append(`
        <tr class='notr'>
            <td class='cell'></td>
            <td class='cell'></td>
            <td class='cell' colspan='2' style='text-align:center'>Hiç kayıt bulunamadı!</td>
            <td class='cell'></td>
            <td class='cell'></td>
        </tr>
        `);
        return
    }
    // Paginator
    $('.paginatorContainer').empty();
    $('.paginatorContainer').append(`
        <div class="paginatorExplanation">${dataLen} Kayıttan ${per * (page - 1)} - ${dataLen < per * page ? dataLen : per * page} arası gösteriliyor</div>
        <div class="paginator">
            <div class="paginatorButton">
                <a id="pagPrev" class="paginatorA">Önceki</a>
            </div>
        </div>
    `);
    let pagCount = dataLen / per;
    let remainingPag = dataLen % per;
    for (let i = 0; i < pagCount; i++) {
        $('.paginator').append(`
            <div class="paginatorButton ${i == page - 1 ? 'selectedPaginator' : ''}">
                <a class="paginatorA">${i + 1}</a>
            </div>
        `);
    }
    $('.paginator').append(`
        <div class="paginatorButton">
            <a id="pagNext" class="paginatorA">Sonraki</a>
        </div>
    `);
    $('.paginatorA').click((eventData) => {
        let paginatorButtonContent = eventData.target.innerHTML;
        if (paginatorButtonContent == "Önceki") {
            if (page == 1) {
                return
            }
            placed(conditions, per, page - 1)
        } else if (paginatorButtonContent == "Sonraki") {
            if (page == Math.ceil(pagCount)) {
                return
            }
            placed(conditions, per, parseInt(page) + 1)
        } else {
            placed(conditions, per, parseInt(eventData.target.innerHTML))
        }
    })
    //Table
    $('#condsTab').empty();
    let floPagCount = Math.floor(pagCount);
    let upper = floPagCount == 0 ? remainingPag : floPagCount * page > dataLen ? dataLen : floPagCount * page;
    let lower = per * (page - 1);
    for (let i = lower; i < upper; i++) {
        $('#condsTab').append(`
        <tr class='${parseInt(data[i].durum) == 1 ? 'notr' : parseInt(data[i].kar) > 0 ? 'green' : parseInt(data[i].kar) < 0 ? 'red' : 'notr'}'>
            <td class='cell boldText'>${data[i].parite}</td>
            <td class='cell'>${data[i].kar}</td>
            <td class='cell'>${data[i].en_yuksek}</td>
            <td class='cell'>${data[i].en_dusuk}</td>
            <td class='cell'>${data[i].durum == 0 ? 'Closed' : 'Open'}</td>
            <td class='cell'>${data[i].giris_tarihi}</td>
        </tr>
        `);
    }
}

const byteCount = (s) => {
    return encodeURI(s).split(/%..|./).length - 1;
}
let encryptText = (secretkey = '', messageToencrypt = '') => {
    var encryptedMessage = CryptoJS.AES.encrypt(messageToencrypt, secretkey);
    return encryptedMessage.toString();
}

let decryptText = (secretkey = '', encryptedMessage = '') => {
    var decryptedBytes = CryptoJS.AES.decrypt(encryptedMessage, secretkey);
    var decryptedMessage = decryptedBytes.toString(CryptoJS.enc.Utf8);
    return decryptedMessage;
}

let disseppearContent = (elem, sec) => {
    setTimeout(() => {
        $(elem).fadeOut().empty();
    }, sec)
}

let setDisableness = (elem, boolType) => {
    $(elem).prop("disabled", boolType)
}