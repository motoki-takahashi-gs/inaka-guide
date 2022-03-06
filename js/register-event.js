
/*
// マップを初期状態で表示
function initMap() {
    const map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: 36.2048,
            lng: 138.2529
        },
        zoom: 15
    });

    // 位置情報の取得に成功した時
    const successGeo = (position) => {
        const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        map.setCenter(pos);

        // マーカーを表示
        const marker = new google.maps.Marker({
            position: pos,
            map: map
        })
    }

    // 位置情報の取得に失敗した時
    const errorGeo = (error) => {
        let e = "";
        if (error.code == 1) {
            e = "位置情報が許可されていません";
        } else if (error.code == 2) {
            e = "現在位置を特定できません";
        } else if (error.code == 3) {
            e = "位置情報を取得する前にタイムアウトになりました";
        }
        alert("エラー：" + e);
    }

    // 位置情報のオプション
    const optionsGeo = {
        enableHighAccuracy: true,
        maximumAge: 20000,
        timeout: 7000
    }

    // 位置情報の取得を開始
    navigator.geolocation.getCurrentPosition(successGeo, errorGeo, optionsGeo);

    // 住所から経度と緯度を取得
    getLatLng(map);
}
*/

const registerEvent = () => {

    const customBtn = document.getElementById('custom_button');
    const realBtn = document.getElementById('real_button');

    // カスタムボタンをクリック
    customBtn.addEventListener('click', function () {
        realBtn.click();
    });

    // ファイルが選択されたらサムネイルを表示
    realBtn.addEventListener('change', function (e) {
        const file = e.target.files[0];
        const img = document.getElementById('main_image_thumbnail');
        // img.file = file; // 必要ないと思われる
        const reader = new FileReader();
        reader.onload = function (e) {
            // サムネイル画像を挿入
            img.src = e.target.result;
        }
        reader.readAsDataURL(file);
    });

    /* calendar to pick dates */

    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    let lastClickedCell = [];

    const months = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    const showCalendar = (month, year) => {

        // first day (e.g. Monday or Wednesday, etc.) in current month
        const firstDay = new Date(year, month).getDay();

        // number of dates in current month
        const daysInMonth = 32 - new Date(year, month, 32).getDate();

        // calendar body to be shown in table body
        const calendarBody = document.getElementById('calendar-body');

        // clear all previous cells
        calendarBody.innerHTML = '';

        // show the current month and year
        document.getElementById('month-and-year').textContent = months[month] + ' ' + year;

        // first date of month
        let date = 1;

        // loop 6 times as a calendar has 6 weeks (rows) to display
        for (let i = 0; i < 6; i++) {

            // create 1 row
            const row = document.createElement('tr');

            // loop 7 times as a week has 7 days
            for (let j = 0; j < 7; j++) {

                // when dates have to be blank in the first week
                if (i == 0 && j < firstDay) {
                    const cell = document.createElement('td');
                    const cellText = document.createTextNode('');
                    cell.classList.add('beforeFirstDay');
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                }

                // after reached the last date in the month
                else if (date > daysInMonth) {
                    break;
                }

                // dates to be shown in each cell
                else {
                    const cell = document.createElement('td');
                    const cellText = document.createTextNode(date);
                    cell.classList.add('selectable');
                    cell.appendChild(cellText);
                    row.appendChild(cell);
                    date++;
                }
            }
            // add a week to the calendar body
            calendarBody.appendChild(row);
        }
    }

    // show a calendar
    showCalendar(currentMonth, currentYear);

    // count the number of cell clicked
    let countClicked = 0;

    // date info of 1st and 2nd clicked cells
    let firstClickedDateISO, secondClickedDateISO, firstClickedDate, secondClickedDate, firstClickedDateInt, secondClickedDateInt, dateDifference;

    // each cell on calendar
    const selectableDatesArray = document.getElementsByClassName('selectable');

    // add color to dates selected
    const addStyle = (i) => {
        selectableDatesArray[i].style.backgroundColor = "#1e7900";
        selectableDatesArray[i].style.color = "#fff";
    }

    // add a click event listener to dates which users can book
    const selectDateRange = () => {

        for (let i = 0; i < selectableDatesArray.length; i++) {

            // when each date is clicked
            selectableDatesArray[i].onclick = function (e) {

                // count the number clicked
                countClicked++;

                // when the count is even number (2, 4, 6, etc.)
                if (countClicked % 2 == 0) {

                    // show the 2nd clicked date in the page as TO
                    document.getElementById('to-date').textContent
                        = currentYear + '/' + ('0' + (currentMonth + 1)).slice(-2) + '/' + ('0' + this.textContent).slice(-2);

                    // the clicked date info with ISO format
                    secondClickedDateISO = currentYear + '-' + ('0' + (currentMonth + 1)).slice(-2) + '-' + ('0' + this.textContent).slice(-2);

                    // set the date info of the 2nd clicked cell to hidden element
                    document.getElementById('end-date').value = secondClickedDateISO;

                    // set the date info of the 2nd clicked cell
                    secondClickedDate = new Date(secondClickedDateISO);

                    // add color to the clicked date cell
                    this.style.backgroundColor = "#1e7900";
                    this.style.color = "#fff";

                    // set the element of last clicked cell
                    lastClickedCell.push(this);

                    // the number of days between 1st and 2nd clicked cells
                    dateDifference = (secondClickedDate.getTime() - firstClickedDate.getTime()) / (1000 * 60 * 60 * 24);

                    firstClickedDateInt = parseInt(lastClickedCell[0].textContent);
                    secondClickedDateInt = parseInt(lastClickedCell[1].textContent);

                    // add color to the dates which are within the date range

                    // when the range reaches to the next month or later
                    if (dateDifference > secondClickedDateInt) {
                        for (let i = 0; i < secondClickedDateInt; i++) {
                            addStyle(i);
                        }
                    } else if (dateDifference < 0) {
                        // when the second clicked date is earlier than the first clicked date
                        this.click();
                    } else {
                        // when the range is only within this month
                        for (let i = firstClickedDateInt; i < secondClickedDateInt; i++) {
                            addStyle(i);
                        }
                    }

                    // when the count is odd number (1, 3, 5, etc.)
                } else {

                    // when a cell is already clicked before
                    if (lastClickedCell) {

                        // remove styles from cells with a color
                        for (let i = 0; i < selectableDatesArray.length; i++) {
                            selectableDatesArray[i].removeAttribute('style');
                        }

                        // remove the date info from TO DATE area
                        document.getElementById('to-date').textContent = "";

                        // remove the date info from hidden element
                        document.getElementById('end-date').value = "";

                        // empty the array of last clicked cells
                        lastClickedCell.length = 0;

                        // initialize the second clicked date info to not keep the style to cells as it's used if statement
                        secondClickedDateISO = "";
                    }

                    // show the first clicked date in the page as FROM
                    document.getElementById('from-date').textContent
                        = currentYear + '/' + ('0' + (currentMonth + 1)).slice(-2) + '/' + ('0' + this.textContent).slice(-2);

                    // the clicked date info with ISO format
                    firstClickedDateISO = currentYear + '-' + ('0' + (currentMonth + 1)).slice(-2) + '-' + ('0' + this.textContent).slice(-2);

                    // set the date info of the 1st clicked cell to hidden element
                    document.getElementById('start-date').value = firstClickedDateISO;

                    // set the date info of the 1st clicked cell
                    firstClickedDate = new Date(firstClickedDateISO);

                    // add color to the clicked date cell
                    this.style.backgroundColor = "#1e7900";
                    this.style.color = "#fff";

                    // set the element of last clicked cell
                    lastClickedCell.push(this);
                }
            }
        }
    }

    // show dates with color when showing previous or next calendar if date range is selected
    const showDateRange = () => {

        // when the date range is already selected
        if (firstClickedDateISO && secondClickedDateISO) {

            // when the 1st clicked date is within the calendar shown in the page
            if ((firstClickedDate.getFullYear() == currentYear) && (firstClickedDate.getMonth() == currentMonth)) {

                // when the selected date range is only within the same month
                if (
                    (firstClickedDate.getFullYear() == secondClickedDate.getFullYear())
                    && (firstClickedDate.getMonth() == secondClickedDate.getMonth())
                ) {
                    for (let i = firstClickedDateInt - 1; i < secondClickedDateInt; i++) {
                        addStyle(i);
                    }

                    // when the selected date range reaches the next month or later
                } else {
                    for (let i = firstClickedDateInt - 1; i < selectableDatesArray.length; i++) {
                        addStyle(i);
                    }
                }

                // when the 2nd clicked date is within the calendar shown in the page
            } else if ((secondClickedDate.getFullYear() == currentYear) && (secondClickedDate.getMonth() == currentMonth)) {
                for (let i = 0; i < secondClickedDateInt; i++) {
                    addStyle(i);
                }

                // when the current calendar shown is after the 1st clicked date and before the 2nd clicked date
            } else if (
                (new Date(currentYear, currentMonth).getTime() > firstClickedDate.getTime())
                && (new Date(currentYear, currentMonth).getTime() < secondClickedDate.getTime())
            ) {
                for (let i = 0; i < selectableDatesArray.length; i++)
                    addStyle(i);
            }

            // when only 1st date is selected
        } else if (firstClickedDateISO) {

            // when the 1st clicked date is within the calendar shown in the page
            if ((firstClickedDate.getFullYear() == currentYear) && (firstClickedDate.getMonth() == currentMonth)) {
                addStyle(firstClickedDate.getDate() - 1);
            }
        }
    }

    selectDateRange();

    showDateRange();

    // go to previous month
    const previousMonth = () => {
        currentYear = currentMonth == 0 ? currentYear - 1 : currentYear;
        currentMonth = currentMonth == 0 ? 11 : currentMonth - 1;
        showCalendar(currentMonth, currentYear);
        selectDateRange();
        showDateRange();
    }

    // go to next month
    const nextMonth = () => {
        currentYear = currentMonth == 11 ? currentYear + 1 : currentYear;
        currentMonth = (currentMonth + 1) % 12;
        showCalendar(currentMonth, currentYear);
        selectDateRange();
        showDateRange();
    }

    // when previous or next arrow is clicked
    document.getElementById('previous-month').addEventListener('click', previousMonth);
    document.getElementById('next-month').addEventListener('click', nextMonth);


    // 住所から座標を取得 (Geocoding)
    const getLatLng = () => {
        const geocoder = new google.maps.Geocoder();
        // 入力された住所
        const address = document.getElementById("address").value;
        geocoder.geocode({ 'address': address }, function (results, status) {
            if (status == 'OK') {
                // 地図の高さを指定
                document.getElementById('map').style.height = '150px';
                // 座標
                const pos = results[0].geometry.location;
                // 地図を表示
                const map = new google.maps.Map(document.getElementById('map'), {
                    center: pos,
                    zoom: 10
                });
                // 赤いマーカーを座標に表示
                const marker = new google.maps.Marker({
                    map: map,
                    position: pos
                });
                // 緯度と経度
                const latitude = pos.lat();
                const longitude = pos.lng();
                // 隠し要素に座標を挿入
                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;
                // コンソールに座標を表示
                console.log(address + ' の緯度 --> ' + latitude + ' および経度 --> ' + longitude);
            } else {
                // 座標を取得できない時
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    // 住所から Place ID を取得 (Find Place - ID only)
    const getPlaceId = () => {
        const address = document.getElementById("address").value;
        const div = document.getElementById('place_id');
        const request = {
            query: address,
            fields: ['place_id']
        };
        const service = new google.maps.places.PlacesService(div);

        service.findPlaceFromQuery(request, function (results, status) {
            if (status === google.maps.places.PlacesServiceStatus.OK) {
                // Place ID
                const placeId = results[0].place_id;
                // 隠し要素に Place ID を挿入
                document.getElementById('place_id').value = placeId;
                // コンソールに Place ID を表示
                console.log(address + ' の Place ID --> ' + placeId);
            }
        });
    }

    // 「住所を確認する」をクリック
    document.getElementById("confirm_address").addEventListener("click", function () {
        getLatLng();
        getPlaceId();
    });

}

registerEvent();