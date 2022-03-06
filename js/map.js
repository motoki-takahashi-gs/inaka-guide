
const doNothing = () => { }

const downloadUrl = (url, callback) => {
    const request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    }

    request.open('GET', url, true);
    request.send(null);
}








function initMap() {
    // initial display for maps
    const map = new google.maps.Map(document.getElementById('map'), {
        center: new google.maps.LatLng(37, 137),
        zoom: 5
    });

    // // when succeeded getting user location
    // const successGeo = (position) => {
    //     const pos = {
    //         lat: position.coords.latitude,
    //         lng: position.coords.longitude
    //     };
    //     map.setCenter(pos);
    // }

    // // when failed getting user location
    // const errorGeo = (error) => {
    //     let e = "";
    //     if (error.code == 1) {
    //         e = "Getting your location information is not allowed.";
    //     } else if (error.code == 2) {
    //         e = "Couldn't identify your location.";
    //     } else if (error.code == 3) {
    //         e = "Time out before getting your location.";
    //     }
    //     alert("Error: " + e);
    // }

    // // options for getting user location
    // const optionsGeo = {
    //     enableHighAccuracy: true,
    //     maximumAge: 20000,
    //     timeout: 7000
    // }

    // // start getting user location
    // navigator.geolocation.getCurrentPosition(successGeo, errorGeo, optionsGeo);

    const infoWindow = new google.maps.InfoWindow;

    downloadUrl('xml.php', function (data) {
        const xml = data.responseXML;
        const events = xml.documentElement.getElementsByTagName('event');

        // loop the number of events in the xml file
        Array.prototype.forEach.call(events, function (eventElem) {

            // get each value from the xml file
            const id = eventElem.getAttribute('id');
            const title = eventElem.getAttribute('title');
            const summary = eventElem.getAttribute('summary');
            const image = eventElem.getAttribute('main_image');
            const start_date = eventElem.getAttribute('start_date');
            const end_date = eventElem.getAttribute('end_date');
            const start_time = eventElem.getAttribute('start_time');
            const end_time = eventElem.getAttribute('end_time');
            const address = eventElem.getAttribute('address');
            const category = eventElem.getAttribute('category');
            const point = new google.maps.LatLng(
                parseFloat(eventElem.getAttribute('latitude')),
                parseFloat(eventElem.getAttribute('longitude'))
            );

            // the frame of an event itself
            const infoWinContent = document.createElement('div');
            infoWinContent.className = 'info-window';

            // anchor link to the event details
            const linkToDetails = document.createElement('a');
            linkToDetails.setAttribute('href', 'event.php?id=' + id);
            linkToDetails.setAttribute('target', '_blank');
            infoWinContent.appendChild(linkToDetails);

            // the main image
            const imageDiv = document.createElement('div');
            imageDiv.className = 'main-image';
            const imageElem = document.createElement('img');
            imageElem.src = image;
            imageDiv.appendChild(imageElem);
            linkToDetails.appendChild(imageDiv);

            // title
            const titleDiv = document.createElement('div');
            titleDiv.className = 'title';
            const titleH3 = document.createElement('h3');
            titleH3.textContent = title;
            titleDiv.appendChild(titleH3);
            linkToDetails.appendChild(titleDiv);

            // summary
            const summaryDiv = document.createElement('div');
            summaryDiv.className = 'summary';
            summaryDiv.textContent = summary;
            linkToDetails.appendChild(summaryDiv);

            // date
            const dateDiv = document.createElement('div');
            dateDiv.className = 'date';
            const dateSpan_1 = document.createElement('span');
            const dateIcon = document.createElement('i');
            dateIcon.className = 'far fa-calendar-alt';
            const dateSpan_2 = document.createElement('span');
            const startDate = start_date.replace(/-/g, '/');
            const endDate = end_date.replace(/-/g, '/');
            if (startDate == endDate) {
                dateSpan_2.textContent = startDate;
            } else {
                dateSpan_2.textContent = startDate + ' - ' + endDate;
            }
            dateSpan_1.appendChild(dateIcon);
            dateDiv.appendChild(dateSpan_1);
            dateDiv.appendChild(dateSpan_2);
            linkToDetails.appendChild(dateDiv);

            // time
            const timeDiv = document.createElement('div');
            timeDiv.className = 'time';
            const timeSpan_1 = document.createElement('span');
            const timeIcon = document.createElement('i');
            timeIcon.className = 'far fa-clock';
            const timeSpan_2 = document.createElement('span');
            const startTime = start_time.replace(/:00\b/, '');
            const endTime = end_time.replace(/:00\b/, '');
            timeSpan_2.textContent = startTime + ' - ' + endTime;
            timeSpan_1.appendChild(timeIcon);
            timeDiv.appendChild(timeSpan_1);
            timeDiv.appendChild(timeSpan_2);
            linkToDetails.appendChild(timeDiv);

            // address
            const addressDiv = document.createElement('div');
            addressDiv.className = 'address';
            const addressSpan_1 = document.createElement('span');
            const addressIcon = document.createElement('i');
            addressIcon.className = 'fas fa-map-marker-alt';
            const addressSpan_2 = document.createElement('span');
            addressSpan_2.textContent = address;
            addressSpan_1.appendChild(addressIcon);
            addressDiv.appendChild(addressSpan_1);
            addressDiv.appendChild(addressSpan_2);
            linkToDetails.appendChild(addressDiv);

            // categories
            const categoryDiv = document.createElement('div');
            categoryDiv.className = 'category';
            const categoryBtn = [];
            const catList = category.split(',');
            for (let i = 0; i < catList.length; i++) {
                categoryBtn[i] = document.createElement('button');
                categoryBtn[i].textContent = catList[i];
                categoryDiv.appendChild(categoryBtn[i]);
            }
            linkToDetails.appendChild(categoryDiv);

            // position of marker shown
            const marker = new google.maps.Marker({
                map: map,
                position: point
            });

            // when marker is clicked
            marker.addListener('click', function () {
                infoWindow.setContent(infoWinContent);
                infoWindow.open(map, marker);
            });
        });
    });
}