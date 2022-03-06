
/* Search options */
const searchOptions = () => {

    const searchModal = document.getElementById('search-modal');

    // when users want to launch a search window
    const startSearch = document.getElementsByClassName('start-search');
    for (let i = 0; i < startSearch.length; i++) {
        startSearch[i].onclick = () => {
            // switch to show or hide the modal
            searchModal.classList.toggle('modal-active');
        }
    }

    // when the close mark at search options is clicked
    const closeBtnSearchOptions = document.querySelector('#search-modal .close');
    closeBtnSearchOptions.onclick = () => {
        // switch to show or hide the modal
        searchModal.classList.toggle('modal-active');
    }

    // Show results button
    const showResultsBtn = document.querySelector('.search-options .show-results button');

    /* Select destinations */

    const destinationsModal = document.getElementById('destinations-modal');
    // each region
    const regions = document.querySelectorAll('.regions > div > div');
    // instruction to select prefecture(s)
    const instruction = document.querySelector('.prefectures h3');
    // prefectures nested by div with region name
    const nestedPrefectures = document.querySelectorAll('.prefectures > div');

    // when Destinations is clicked
    const destinationsBtn = document.querySelector('.destinations button');
    destinationsBtn.onclick = () => {
        destinationsModal.classList.toggle('modal-active');
    }

    // when the close mark at Destinations is clicked
    const closeBtnDestinations = document.querySelector('#destinations-modal .close');
    closeBtnDestinations.onclick = () => {
        destinationsModal.classList.toggle('modal-active');
    }

    for (let i = 0; i < regions.length; i++) {

        // when one of regions is clicked
        regions[i].onclick = function (e) {

            // remove color from an already clicked region
            for (let i = 0; i < regions.length; i++) {
                // except for the region just being clicked (not necessarily required)
                if (this != regions[i]) {
                    regions[i].removeAttribute('style');
                }
            }

            // hide all prefectures already shown
            for (let i = 0; i < nestedPrefectures.length; i++) {
                nestedPrefectures[i].removeAttribute('style');
            }

            const regionName = e.target.id;
            const regionNameElem = document.getElementsByClassName(regionName)[0];

            // give color only to clicked region
            this.style.backgroundColor = '#4169E1';
            this.style.color = '#fff';

            // show the instruction
            instruction.style.display = 'block';

            // show only the clicked region's prefectures
            regionNameElem.style.display = 'flex';
        }
    }

    // each prefecture
    const prefectures = document.querySelectorAll('.prefectures > div > div');
    const selectedPrefArray = [];

    for (let i = 0; i < prefectures.length; i++) {

        // when one of prefectures is clicked
        prefectures[i].onclick = function (e) {

            // the clicked prefecture name
            const selectedPrefName = e.target.textContent;

            // when the prefecture's been already selected
            if (this.hasAttribute('style')) {
                // remove color from the clicked prefecture
                this.removeAttribute('style');
                // remove the prefecture name from array
                const indexNum = selectedPrefArray.indexOf(selectedPrefName);
                selectedPrefArray.splice(indexNum, 1);
            } else {
                // give color to a clicked prefecture
                this.style.backgroundColor = '#4169E1';
                this.style.color = '#fff';
                // add the clicked prefecture name to array
                selectedPrefArray.push(selectedPrefName);
            }

            // show Apply button only when prefecture(s) is selected
            if (selectedPrefArray.length > 0) {
                applyBtnDestinations.style.display = 'block';
            } else {
                applyBtnDestinations.style.display = 'none';
            }
        }
    }

    // Apply button at Destinations
    const applyBtnDestinations = document.querySelector('.select-destinations .apply-btn button');

    // when Apply button at Destinations is clicked
    applyBtnDestinations.onclick = function () {

        // close Destinations modal
        closeBtnDestinations.click();

        // give color to Destinations button
        destinationsBtn.style.backgroundColor = '#4169E1';
        destinationsBtn.style.color = '#fff';

        // show the selected prefectures
        document.querySelector('.destinations > p').textContent = selectedPrefArray.join(' / ');

        // set the selected prefectures to the value of input element
        document.querySelector('.destinations > input[type="hidden"]').value = selectedPrefArray;

        // change the type attribute of Show results button from button to submit
        showResultsBtn.type = 'submit';
    }

    /* Select dates */

    const datesModal = document.getElementById('dates-modal');
    const datesBtn = document.querySelector('.dates button');

    // when Dates is clicked
    datesBtn.onclick = () => {
        datesModal.classList.toggle('modal-active');
    }

    // when the close mark at Dates is clicked
    const closeBtnDates = document.querySelector('#dates-modal .close');
    closeBtnDates.onclick = function () {
        datesModal.classList.toggle('modal-active');
    }

    // each Dates option
    const datesOptions = document.querySelectorAll('.dates-options > div > div');

    // declare variables to be used globally
    let today, tomorrow, thisWeek, nextWeek, thisMonth, nextMonth,
        start_date_searched, end_date_searched, selectedDate, selectedDateType;

    // common part of selecting dates (this / next week and month)
    const selectDatesProcess = (start_date_searched, end_date_searched, timeDifference) => {

        start_date_searched.setMinutes(start_date_searched.getMinutes() - timeDifference);
        end_date_searched.setMinutes(end_date_searched.getMinutes() - timeDifference);

        start_date_searched = start_date_searched.toISOString().slice(0, 10);
        end_date_searched = end_date_searched.toISOString().slice(0, 10);

        const data = {
            start: start_date_searched,
            end: end_date_searched,
            range: start_date_searched.replace(/-/g, '/') + ' - ' + end_date_searched.replace(/-/g, '/')
        }
        return data;
    }

    for (let i = 0; i < datesOptions.length; i++) {

        // when one of Dates options is clicked
        datesOptions[i].onclick = function () {

            // remove color from all dates options
            for (let i = 0; i < datesOptions.length; i++) {
                datesOptions[i].removeAttribute('style');
            }

            // give color to the one clicked
            this.style.backgroundColor = '#4169E1';
            this.style.color = '#fff';

            // date of today
            today = new Date();

            // it sets -540 in JST as JST is 9 hours earlier than UTC
            const timeDifference = today.getTimezoneOffset();

            // when Today is clicked
            if (this.id == 'today') {
                // minus time difference in advance (+540 minutes in JST)
                today.setMinutes(today.getMinutes() - timeDifference);
                // toISOString() subtracts 540 minutes in JST as it's UTC resulting in offset
                today = today.toISOString();
                today = today.slice(0, 10);

                start_date_searched = today;
                end_date_searched = today;
                today = today.replace(/-/g, '/');
                selectedDateType = 'today';
            }

            // when Tomorrow is clicked
            if (this.id == 'tomorrow') {
                // date of tomorrow
                tomorrow = new Date();
                // plus 1 day as tomorrow is next day of today
                tomorrow.setDate(today.getDate() + 1);
                tomorrow.setMinutes(tomorrow.getMinutes() - timeDifference);
                tomorrow = tomorrow.toISOString();
                tomorrow = tomorrow.slice(0, 10);

                start_date_searched = tomorrow;
                end_date_searched = tomorrow;
                tomorrow = tomorrow.replace(/-/g, '/');
                selectedDateType = 'tomorrow';
            }

            // when This week is clicked
            if (this.id == 'this-week') {

                start_date_searched = today.getDate() - today.getDay();
                end_date_searched = start_date_searched + 6;

                start_date_searched = new Date(today.setDate(start_date_searched));
                end_date_searched = new Date(today.setDate(end_date_searched));

                const data = selectDatesProcess(start_date_searched, end_date_searched, timeDifference);

                start_date_searched = data.start;
                end_date_searched = data.end;

                thisWeek = data.range;
                selectedDateType = 'this-week';
            }

            // when Next week is clicked
            if (this.id == 'next-week') {
                start_date_searched = today.getDate() - today.getDay() + 7;
                end_date_searched = start_date_searched + 6;

                start_date_searched = new Date(today.setDate(start_date_searched));
                end_date_searched = new Date(today.setDate(end_date_searched));

                const data = selectDatesProcess(start_date_searched, end_date_searched, timeDifference);

                start_date_searched = data.start;
                end_date_searched = data.end;

                nextWeek = data.range;
                selectedDateType = 'next-week';
            }

            // when This month is clicked
            if (this.id == 'this-month') {

                const year = today.getFullYear();
                const month = today.getMonth();

                start_date_searched = new Date(year, month, 1);
                end_date_searched = new Date(year, month + 1, 0);

                const data = selectDatesProcess(start_date_searched, end_date_searched, timeDifference);

                start_date_searched = data.start;
                end_date_searched = data.end;

                thisMonth = data.range;
                selectedDateType = 'this-month';
            }

            // when Next month is clicked
            if (this.id == 'next-month') {

                const year = today.getFullYear();
                const month = today.getMonth();

                start_date_searched = new Date(year, month + 1, 1);
                end_date_searched = new Date(year, month + 2, 0);

                const data = selectDatesProcess(start_date_searched, end_date_searched, timeDifference);

                start_date_searched = data.start;
                end_date_searched = data.end;

                nextMonth = data.range;
                selectedDateType = 'next-month';
            }

            // show Apply button only when one of Dates options is clicked
            if (today) {
                applyBtnDates.style.display = 'block';
            }
        }
    }

    // Apply button at Dates
    const applyBtnDates = document.querySelector('.select-dates .apply-btn button');

    // when Apply button at Dates is clicked
    applyBtnDates.onclick = function () {

        // close Dates modal
        closeBtnDates.click();

        // give color to Dates button
        datesBtn.style.backgroundColor = '#4169E1';
        datesBtn.style.color = '#fff';

        // show the selected dates
        switch (selectedDateType) {
            case 'today':
                selectedDate = today;
                break;
            case 'tomorrow':
                selectedDate = tomorrow;
                break;
            case 'this-week':
                selectedDate = thisWeek;
                break;
            case 'next-week':
                selectedDate = nextWeek;
                break;
            case 'this-month':
                selectedDate = thisMonth;
                break;
            case 'next-month':
                selectedDate = nextMonth;
        }
        document.querySelector('.dates > p').textContent = selectedDate;

        // set the selected dates to the value of input element as array
        document.querySelector('.dates > input[type="hidden"]').value = [start_date_searched, end_date_searched];

        // change the type attribute of Show results button from button to submit
        showResultsBtn.type = 'submit';
    }

    /* Select activities */

    const activitiesModal = document.getElementById('activities-modal');
    const activitiesBtn = document.querySelector('.activities button');

    // when Activities is clicked
    activitiesBtn.onclick = () => {
        activitiesModal.classList.toggle('modal-active');
    }

    // when the close mark at Activities is clicked
    const closeBtnActivities = document.querySelector('#activities-modal .close');
    closeBtnActivities.onclick = function () {
        activitiesModal.classList.toggle('modal-active');
    }

    // each Activities option
    const activitiesOptions = document.querySelectorAll('.activities-options > div > div');
    const selectedActNameArray = [];
    const selectedActIdArray = [];

    for (let i = 0; i < activitiesOptions.length; i++) {

        // when one of Activities options is clicked
        activitiesOptions[i].onclick = function (e) {

            // the clicked activity name
            const selectedActName = e.target.textContent;

            // the clicked activity (category) id
            const selectedActId = e.target.getAttribute('value');

            // when the activity's been already selected
            if (this.hasAttribute('style')) {
                // remove color from the clicked activity
                this.removeAttribute('style');
                // remove the activity name from array
                const indexNum = selectedActNameArray.indexOf(selectedActName);
                selectedActNameArray.splice(indexNum, 1);
                // remove the activity (category) id from array
                selectedActIdArray.splice(indexNum, 1);
            } else {
                // give color to a clicked activity
                this.style.backgroundColor = '#4169E1';
                this.style.color = '#fff';
                // add the clicked activity name to array
                selectedActNameArray.push(selectedActName);
                // add the clicked activity (category) id to array
                selectedActIdArray.push(selectedActId);
            }

            // show Apply button only when activity is selected
            if (selectedActNameArray.length > 0) {
                applyBtnActivities.style.display = 'block';
            } else {
                applyBtnActivities.style.display = 'none';
            }
        }
    }

    // Apply button at Activities
    const applyBtnActivities = document.querySelector('.select-activities .apply-btn button');

    // when Apply button at Activities is clicked
    applyBtnActivities.onclick = function () {

        // close Activities modal
        closeBtnActivities.click();

        // give color to Activities button
        activitiesBtn.style.backgroundColor = '#4169E1';
        activitiesBtn.style.color = '#fff';

        // show the selected activities
        document.querySelector('.activities > p').textContent = selectedActNameArray.join(' / ');

        // set the selected activities to the value of input element
        document.querySelector('.activities > input[type="hidden"]').value = selectedActIdArray;

        // change the type attribute of Show results button from button to submit
        showResultsBtn.type = 'submit';
    }

}

searchOptions();