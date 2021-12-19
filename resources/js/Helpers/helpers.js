const showError = (res) => {
    if (res.data.errors) {
        Object.keys(res.data.errors).map(function (key) {
            window.toast.current.show({severity: 'error', summary: res.data.message, detail: res.data.errors[key]});
        });
    } else {
        window.toast.current.show({severity: 'error', summary: res.data.message});
    }
}

const showSuccess = (res) => {
    window.toast.current.show({severity:'success', summary: res.data.message});
}

const showMessage = (message) => {
    window.toast.current.show({severity:'success', summary: message});
}

export { showError, showSuccess, showMessage };
