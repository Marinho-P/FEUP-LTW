document.addEventListener("DOMContentLoaded", function() {
    var brandElement = document.getElementById('brand');
    if(brandElement) {
    document.getElementById('brand').addEventListener('change', function() {
        var brandId = this.value;
        var xhr = new XMLHttpRequest();
        var modelDropdown = document.getElementById('model');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var models = JSON.parse(xhr.responseText);
                    modelDropdown.innerHTML = '<option selected value=""> -- select a model -- </option>';
                    models.forEach(function(model) {
                        modelDropdown.innerHTML += '<option value="' + model.modelId + '">' + model.name + '</option>';
                    });
                } else {
                    console.log('Error: ' + xhr.status);
                }
            }
        };
        if (brandId) {
            xhr.open('GET', '../actions/actionGetModelByBrand.php?brandId=' + brandId, true);
            xhr.send();
        } else {
            modelDropdown.innerHTML = '<option disabled selected value> -- choose a brand first -- </option>';
        }
    });
}
});