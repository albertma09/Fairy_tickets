
    
    export const addValues = () =>{
        var stars = document.querySelectorAll('.fa-star');

        var caretIcons = document.querySelectorAll('.caret-icon');

            caretIcons.forEach(function(caretIcon) {
                caretIcon.addEventListener('click', function() {
                    var value = parseInt(caretIcon.getAttribute('data-value'));
                    setValoracion(value);
                    setValoraciones(value, caretIcons);
                });
            });

        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                var value = parseInt(star.getAttribute('data-value'));
                console.log(value)
                setStars(value, stars);
                setStar(value);
            });
        });
    }
    


function setValoraciones(valor, caretIcons) {

    caretIcons.forEach(function(cara, index) {
        var caraValue = parseInt(cara.getAttribute('data-value'))
        if (caraValue == valor) {
            cara.classList.add("checked");
        } else {
            cara.classList.remove("checked");
        }
    });
}
function setValoracion(valor) {
    document.getElementById("face_rating").value = valor;
}

function setStar(valor) {
    document.getElementById("star_rating").value = valor;
}

function setStars(valor, estrellas) {
    estrellas.forEach(function(star, index) {
        var starValue = parseInt(star.getAttribute('data-value'))
        
        if (starValue <= valor) {
            
            star.classList.add("checked");
        } else {
            star.classList.remove("checked");
        }
    });
}