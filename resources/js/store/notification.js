import Vue from 'vue';


function notificationSuccess({title, text = ''}) {
    Vue.notify({
        type: 'success',
        title: title,
        text: text,
    });
}

function notificationError({title, text = ''}) {
    Vue.notify({
        type: 'error',
        title: title,
        text: text,
        duration: -1,
    });
}
function notificationWarning({title, text = ''}) {
    Vue.notify({
        type: 'warn',
        title: title,
        text: text,
        duration: -1,
    });
}

function notificationFromErrorResponse(response) {

    notificationError({
        title: 'There were some problems saving this Tile.',
        text: formatText(response),
    });
}

function formatText(response) {
    if (!response) {
        return '';
    }

    let errors = response.data.errors;

    let text = '';
    if (errors) {
        Object.keys(errors).forEach(key => {
            let value = errors[key];

            if (Array.isArray(value)) {
                value = value.join('<br>');
            }
            text += key + ': ' + value + '<br>';
        });
    }
    return text;
}

export {
    notificationSuccess,
    notificationError,
    notificationWarning,
    notificationFromErrorResponse,
};
