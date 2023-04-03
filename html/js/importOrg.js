document.getElementById('file-input')
    .addEventListener('change', readSingleFile, false);

function readSingleFile(e) {
    var reader = new FileReader();
    reader.onload = function () {

        var arrayBuffer = this.result,
            array = new Uint8Array(arrayBuffer),
            binaryString = String.fromCharCode.apply(null, array);


        var workbook = XLSX.read(binaryString, { type: "binary" });
        var first_sheet_name = workbook.SheetNames[0];
        var worksheet = workbook.Sheets[first_sheet_name];
        ExcelAsData = XLSX.utils.sheet_to_json(worksheet, { raw: true });

        var root = [];
        for (let index = 0; index < ExcelAsData.length; index++) {
            root[index] = [{ 'v': ExcelAsData[index]['Employee Position'], 'f': ExcelAsData[index]['Name & Surename as per Strength '] + ExcelAsData[index]['New Title'] }, ExcelAsData[index]['Report to Position'], ExcelAsData[index]['New Title']];
            // console.log(ExcelAsData[index]);
        }

        root_json = JSON.stringify(root);
        google.charts.load('current', {
            packages: ["orgchart"]
        });
        google.charts.setOnLoadCallback(drawChart);
    }
    reader.readAsArrayBuffer(e.target.files[0]);
}