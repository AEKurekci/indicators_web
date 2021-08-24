const placed = (data, per, page) => {
    let dataLen = data.length
    $('.paginatorContainer').empty();
    if (dataLen == 0) {
        $('#condsTab').empty();
        $('#condsTab').append(`
        <tr class='notr'>
            <td class='lightFont'></td>
            <td class='lightFont'></td>
            <td class='lightFont'></td>
            <td class='lightFont' colspan='3' style='text-align:center'>Hiç kayıt bulunamadı!</td>
            <td class='lightFont'></td>
            <td class='lightFont'></td>
            <td class='lightFont'></td>
        </tr>
        `);
        return
    }
    // Paginator
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
            placed(trades, per, page - 1)
        } else if (paginatorButtonContent == "Sonraki") {
            if (page == Math.ceil(pagCount)) {
                return
            }
            placed(trades, per, parseInt(page) + 1)
        } else {
            placed(trades, per, parseInt(eventData.target.innerHTML))
        }
    })
    //Table
    $('#condsTab').empty();
    let upper = per * page > dataLen ? dataLen : per * page;
    let lower = per * (page - 1);
    for (let i = lower; i < upper; i++) {
        $('#condsTab').append(`
        <tr class='${parseInt(data[i].durum) == 1 ? 'notr' : parseFloat(data[i].kar) > 0 ? 'green' : parseFloat(data[i].kar) < 0 ? 'red' : 'notr'}'>
            <td class='boldText'>${data[i].parite}</td>
            <td class='lightFont'>${data[i].buy}</td>
            <td class='lightFont'>${data[i].sell}</td>
            <td class='lightFont'>${data[i].kar}</td>
            <td class='lightFont'>${data[i].en_yuksek}</td>
            <td class='lightFont'>${data[i].en_dusuk}</td>
            <td class='lightFont'>${data[i].durum == 0 ? 'Pasif' : 'Aktif'}</td>
            <td class='lightFont'>${new Date(parseInt(data[i].opentime)).toLocaleString('tr-TR')}</td>
            <td class='lightFont'>${new Date(parseInt(data[i].closetime)).toLocaleString('tr-TR')}</td>
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
