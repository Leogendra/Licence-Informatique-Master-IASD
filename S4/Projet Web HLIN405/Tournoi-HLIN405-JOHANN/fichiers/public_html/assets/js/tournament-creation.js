/*
the goal of this script is to do input validation and input synchronisation on tournament creation form
in this form we have 4 elements that has to change the state of the others when their value is updated
when the select element with id "duration-set-method-selector" is updated the visibility of the corresponding div element
must be updated which is either "duration" or "ending-date"
and when the input elements with id "starting-date-input", "duration-input", "ending-date-input" get updated
the other 2 elements must be updated in a way that the time difference does not go above 30 days or below 7 days
and the set dates are not in the past
*/
//#define:
//##helper functions:
/*
this function is to reformat the date variable into a new Date object or to a string
we use this to convert date value got from datepicker to a workable UTC time format
this function is bi-directional and it can be run in reverse when the second argument is set to false
first parameter is treated as Date object if second parameter is true and string if not
processDate : function | date :: object/string , direction :: boolean -> string/object;
*/
function processDate(date, direction) {
    if (direction) {
        const year = date.getFullYear();
        let month = date.getMonth() + 1,
            day = date.getDate();
        month = month < 10 ? '0' + month.toString() : month;
        day = day < 10 ? '0' + day.toString() : day;
        return year + '-' + month + '-' + day;
    } else {
        return new Date(date);
    }
}
/*
this function is to calculate the time difference in days between two dates represented by two Date objects
timeDifference : function | date1 :: object , date2 :: object -> number;
*/
function timeDifference(date1, date2) {
    return (
        (processDate(date2, false).getTime() -
            processDate(date1, false).getTime()) /
        (1000 * 3600 * 24)
    );
}
//##setter functions:
/*
this function sets starting-date-input datepicker value
it  does this by looking at end date input value and substracting duration from that date and assigning result to starting date
setStartDate : function | endingDate_v :: string , startingDate_i :: object , duration_v :: number -> void;
*/
function setStartDate(
    endingDate_v,
    startingDate_i,
    endInscriptionsDate_i,
    duration_v
) {
    const startDate = processDate(endingDate_v, false);
    startDate.setTime(
        startDate.getTime() - 1000 * 3600 * 24 * (duration_v - 1)
    );
    startingDate_i.value = processDate(startDate, true);
    endInscriptionsDate_i.max = processDate(startDate, true);
    endInscriptionsDate_i.value =
        timeDifference(endInscriptionsDate_i.value, endInscriptionsDate_i.max) <
        0
            ? endInscriptionsDate_i.max
            : endInscriptionsDate_i.value;
}
/*
this function sets Duration to difference between days
if difference is bigger than max days allowed or smaller than min days allowed the duration will be set at the limit and a new ending/starting date will be calculated and assigned
this function is bi-directional too but its not reversed, when direction is set to false it will assume its being called from the ending-day-input onchange method
by default it assumes its being called from starting-date-input therefore it will attempt to set duration and if out of bounds it will set the duration by the apropriate limit
and set ending-day-input value by calculating new date from newly set starting-date-input and max/min duration
setDuration : function | startingDate_i :: object , endingDate_i :: object , duration_i :: object , direction :: boolean , minDuration :: number , maxDuration :: number -> void;
*/
function setDuration(
    startingDate_i,
    endInscriptionsDate_i,
    endingDate_i,
    duration_i,
    direction,
    minDuration,
    maxDuration
) {
    const tDiff = timeDifference(startingDate_i.value, endingDate_i.value) + 1;
    switch (true) {
        case tDiff <= maxDuration && tDiff >= minDuration:
            duration_i.value = tDiff;
            break;
        case tDiff > maxDuration:
            duration_i.value = maxDuration;
            if (direction) {
                setEndDate(
                    startingDate_i.value,
                    endingDate_i,
                    duration_i.value
                );
            } else {
                setStartDate(
                    endingDate_i.value,
                    startingDate_i,
                    endInscriptionsDate_i,
                    duration_i.value
                );
            }
            break;
        case tDiff < minDuration:
            duration_i.value = minDuration;
            if (direction) {
                setEndDate(
                    startingDate_i.value,
                    endingDate_i,
                    duration_i.value
                );
            } else {
                setStartDate(
                    endingDate_i.value,
                    startingDate_i,
                    endInscriptionsDate_i,
                    duration_i.value
                );
            }
            break;
        default:
            break;
    }
}
/*
this function sets ending-date-input datepicker value
it does this by looking at end date input value and adding duration to that date and assigning result to starting date
setEndDate : function | startingDate_v :: string , endingDate_i :: object , duration_v :: number -> void;
 */
function setEndDate(startingDate_v, endingDate_i, duration_v) {
    const endDate = processDate(startingDate_v, false);
    endDate.setTime(endDate.getTime() + 1000 * 3600 * 24 * (duration_v - 1));
    endingDate_i.value = processDate(endDate, true);
}
//##getter functions:
/*
this function gets all the needed html elements from the document
it returns them in an array
getElementsById : function | void -> [object , object , object , object, object, object];
 */
function getElementsById() {
    const durationSetMethodSelector = document.getElementById(
            'duration-set-method-selector'
        ),
        duration = document.getElementById('duration'),
        endingDate = document.getElementById('ending-date'),
        startingDateInput = document.getElementById('starting-date-input'),
        endInscriptionsDateInput = document.getElementById(
            'end-inscriptions-date-input'
        ),
        durationInput = document.getElementById('duration-input'),
        endingDateInput = document.getElementById('ending-date-input');
    return [
        durationSetMethodSelector,
        duration,
        endingDate,
        startingDateInput,
        endInscriptionsDateInput,
        durationInput,
        endingDateInput,
    ];
}
//##tournament creation form logic:
/*
this function swithes the visibility of its parameters between visible and invisible
onDurationSelectionSet : function | duration_d :: object , endingdate_d :: object -> void;
*/
function onDurationSelectionSet(duration_d, endingdate_d) {
    if (duration_d.style.display === 'block') {
        duration_d.style = 'display:none';
        endingdate_d.style = 'display:block';
    } else {
        endingdate_d.style = 'display:none';
        duration_d.style = 'display:block';
    }
}
/*
this function is called each time any of the 3 date related inputs changes
it knows the last updated element by looking at its caller and updates the other 2 inputs values
onDurationSet : function | caller :: string , startingDate_i :: object , endingDate_i :: object , duration_i :: object , minDuration :: number , maxDuration :: number -> void;
 */
function onDurationSet(
    caller,
    startingDate_i,
    endInscriptionsDate_i,
    endingDate_i,
    duration_i,
    minDuration,
    maxDuration
) {
    switch (caller) {
        case 'startingDate':
            startingDate_i.value ||
                setStartDate(
                    endingDate_i.value,
                    startingDate_i,
                    endInscriptionsDate_i,
                    duration_i.value
                );
            setDuration(
                startingDate_i,
                endInscriptionsDate_i,
                endingDate_i,
                duration_i,
                true,
                minDuration,
                maxDuration
            );
            endInscriptionsDate_i.max = startingDate_i.value;
            endInscriptionsDate_i.value =
                timeDifference(
                    endInscriptionsDate_i.value,
                    endInscriptionsDate_i.max
                ) < 0
                    ? endInscriptionsDate_i.max
                    : endInscriptionsDate_i.value;
            break;
        case 'duration':
            if (duration_i.value < minDuration) {
                duration_i.value = minDuration;
            } else if (duration_i.value > maxDuration) {
                duration_i.value = maxDuration;
            }
            setEndDate(startingDate_i.value, endingDate_i, duration_i.value);
            break;
        case 'endingDate':
            endingDate_i.value ||
                setEndDate(
                    startingDate_i.value,
                    endingDate_i,
                    duration_i.value
                );
            setDuration(
                startingDate_i,
                endInscriptionsDate_i,
                endingDate_i,
                duration_i,
                false,
                minDuration,
                maxDuration
            );
            break;
        default:
            break;
    }
}
/*
this function sets the initial state of the input fields, sets limits to min values of chosable dates and min max values of the duration field
it then constructs and assigns the logic to onchange triggers of apropriate elements
setupTournamentCreationForm : function | void -> void;
*/
function setupTournamentCreationForm() {
    const minDuration = 1,
        maxDuration = 30,
        [
            durationSetMethodSelector,
            duration,
            endingDate,
            startingDateInput,
            endInscriptionsDateInput,
            durationInput,
            endingDateInput,
        ] = getElementsById(),
        minDate = new Date();
    minDate.setDate(minDate.getDate() + 1);
    startingDateInput.min = processDate(minDate, true);
    endInscriptionsDateInput.max =
        endInscriptionsDateInput.max || startingDateInput.value || processDate(minDate, true);
    durationInput.min = minDuration;
    durationInput.max = maxDuration;
    endingDateInput.min = startingDateInput.value || processDate(minDate, true);
    startingDateInput.value =
        startingDateInput.value || processDate(minDate, true);
    endInscriptionsDateInput.value =
        endInscriptionsDateInput.value || processDate(minDate, true);
    durationInput.value = durationInput.value || minDuration;
    setEndDate(startingDateInput.value, endingDateInput, durationInput.value);
    duration.style = 'display:block';
    endingDate.style = 'display: none';
    durationSetMethodSelector.onchange = () => {
        return onDurationSelectionSet(duration, endingDate);
    };
    startingDateInput.onchange = () => {
        return onDurationSet(
            'startingDate',
            startingDateInput,
            endInscriptionsDateInput,
            endingDateInput,
            durationInput,
            minDuration,
            maxDuration
        );
    };
    endInscriptionsDateInput.onchange = () => {
        endInscriptionsDateInput.value =
            endInscriptionsDateInput.value || startingDateInput.value;
    };
    durationInput.onchange = () => {
        return onDurationSet(
            'duration',
            startingDateInput,
            endInscriptionsDateInput,
            endingDateInput,
            durationInput,
            minDuration,
            maxDuration
        );
    };
    endingDateInput.onchange = () => {
        return onDurationSet(
            'endingDate',
            startingDateInput,
            endInscriptionsDateInput,
            endingDateInput,
            durationInput,
            minDuration,
            maxDuration
        );
    };
}
//#run:
setupTournamentCreationForm();
