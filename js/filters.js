function getFilters() {
    loadData('GetCN.php', 'CN');
    loadData('GetPC.php', 'PC');
    loadData('GetName.php', 'Name');

}

function update(Select) {
    let PC = Select.value;
    let lv = document.getElementById('LV_DEEP').value;
    window.location.replace(`OrgChart.html?PositionCode=` + PC + `&LV_DEEP=` + lv);
}

function loadData(URI, TAG) {
    function httpGet(theUrl) {
        var xmlHttp = null;

        xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", theUrl, false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }

    let obj = JSON.parse(httpGet(URI));

    if (TAG == 'Name') {
        let names = `<option value="">SELECT NAME</option>`;
        obj.forEach(rec => {
            names += `<option value="` + rec['PositionCode'] + `">` + rec['Name'] + `</option>`;
        });
        document.getElementById(TAG).innerHTML = names;
    }

    if (TAG == 'PC') {
        let PCs = `<option value="">SELECT POSITION</option>`;
        obj.forEach(rec => {
            PCs += `<option value="` + rec['PositionCode'] + `">` + rec['PositionCode'] + `</option>`;
        });
        document.getElementById(TAG).innerHTML = PCs;
    }

    if (TAG == 'CN') {
        let CNs = `<option value="">SELECT EmployeeCode</option>`;
        obj.forEach(rec => {
            CNs += `<option value="` + rec['PositionCode'] + `">` + rec['EmployeeCode'] + `</option>`;
        });
        document.getElementById(TAG).innerHTML = CNs;
    }

}