var months  = [
    'Jan', 
    'Feb', 
    'Mar', 
    'Apr', 
    'Mei', 
    'Jun',
    'Jul',
    'Agu',
    'Sep', 
    'Okt', 
    'Nov', 
    'Des'
];

var days    = [
    'Minggu', 
    'Senin', 
    'Selasa', 
    'Rabu', 
    'Kamis', 
    'Jumat', 
    'Sabtu'
];

function _update_time(elem){
    var date 	    = new Date();
    var hours 	    = date.getHours();
    var minutes     = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
    
    // tanggal
    var dayOfWeek 	= days[date.getDay()];
    var month 		= months[date.getMonth()];
    var day 		= date.getDate();
    var year 		= date.getFullYear();
    
    // waktu
    var strTanggal 	= dayOfWeek + ', ' + day + ' ' + month + ' ' + year;
    var strJam 		= hours + ':' + minutes;
    
    elem.html(strTanggal+ ' - '+strJam);
}

function _pad(str, max) {
    str = str.toString();
    return str.length < max ? _pad("0" + str, max) : str;
}

function _make_id(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}