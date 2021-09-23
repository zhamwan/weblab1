let r,x,y;

if (localStorage.length == 0){
    localStorage.setItem('table', '');
}

window.onload = (event) => {
        if (localStorage.table !== '') {
            localStorage.table.split("|").forEach(function (strElem) {
                let newRow = strElem;
                $('#result-table tr:first').after(newRow);
            });
        }
    };

function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

function validateX() {
    x = document.getElementById("x-select").value;
    if (x !== undefined) {
        return true;
    } else {
        showError("Выберите X");
        return false;
    }
}

function validateY() {
    const Y_MIN = -3;
    const Y_MAX = 3;
    y = document.getElementById("y-input").value;
    if (y === '') {
        showError("Введите Y");
        return false;
    }
    y.replace(',' , '.')
    if (!isNumeric(y)) {
        showError("Y не является числом");
        return false;
    }
    if (!((y > Y_MIN) && (y < Y_MAX))) {
        showError("Y не входит в область допустимых значений");
        return false;
    }
    return true;
}
function chooseR(element){
    r = element.value;
    [...document.getElementsByClassName("r-button")].forEach(function (btn) {
        btn.style.transform = "";
    });
    element.style.transform =   "scale(1.1)";
}

function showError(message) {
    $('#errors').append(`<li>${message}</li>`)
}

function validateR() {
    if (r !== undefined){
        return true;
    } else {
        showError("Выберите R");
        return false;
    }
}

function validateForm() {
    return validateX() & validateY() & validateR();
}
    $('#input-form').on('submit', function(event) {
        $('#errors').empty();
        event.preventDefault();
        if (!validateForm()) return;
        const url ="php/main.php";
        const request = new XMLHttpRequest();
        const data = "x=" + x + "&y=" + y + "&r=" + r;
        request.open('POST', url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener('readystatechange', function () {
            if(request.readyState === 4 && request.status === 200) {
                result = request.responseText;
                $('#result-table tr:first').after(result);
                if (localStorage.table !== '') {
                    localStorage.table += '|' + result;
                } else localStorage.table = result;
            }
        })
        request.send(data);

    });