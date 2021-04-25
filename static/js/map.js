window.onload = function() {
    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map("map", {
            center: [47.242078, 39.720349],
            zoom: 13.3
        })
        
        fetch('http://a43810d97e04.ngrok.io/districts/', { 
            method: 'GET'
        }).then(response => 
            response.json()
        ).then(data => {
            data.forEach((element) => {
                let coords = JSON.parse(element.coords)

                let newPolygon = new ymaps.Polygon([coords], {hintContent: element.name}, {
                    fillColor: window.rating_colors[parseInt(element.rating)],
                    interactivityModel: 'default#transparent',
                    strokeWidth: 2,
                    strokeColor: '#FFFFFF',
                    opacity: 0.5
                });
                myMap.geoObjects.add(newPolygon);
            })
        });
    }
}