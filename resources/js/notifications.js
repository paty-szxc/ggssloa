import Swal from "sweetalert2";

const showNotification = (backgroundColor, icon, imageUrl, position, text, showCloseButton = true, showConfirmButton = false, isToast = true, timerDuration = 3500) => {
    const options = {
    customClass: {
        popup: 'custom-toast'
    },  
    didOpen: (toast) => {
        toast.style.backgroundColor = backgroundColor
    },
    icon: icon,
    // imageHeight: 210,
    imageUrl: imageUrl,
    position: position,
    showCloseButton: showCloseButton,
    showConfirmButton: showConfirmButton,
    confirmButtonColor: '#ee4b2b',
    text: text,
    timer: isToast ? timerDuration : null, //set timer for toast notifs only
    timerProgressBar: isToast, //enable progress bar for toast notifs only
    toast: isToast,
    width: '350px',
    };
    if(!isToast){
    options.allowOutsideClick = false
    }
    Swal.fire(options)
}
const notifyCustom = (backgroundColor, icon, imageUrl, position, text) => {
    showNotification(backgroundColor, icon, imageUrl, position, text)
}
const notifyError = (errMessage, timerDuration = 3500) => {
    showNotification('#FF4433', 'error', '', 'top-end', errMessage, false, false, true, timerDuration)
}
const notifyInfo = (infoMessage) => {
    showNotification('#3fc3ee', 'info', '', 'top-end', infoMessage, false, false, true)
}
const notifyNoData = (errMessage, icon) => {
    showNotification('#fa9147', icon, 'images/nodata.png', 'center', errMessage, true, false, false) //shows confirm button, not a toast, disallow outside click
}
const notifySuccess = (successMessage) => {
    showNotification('#03c04a', 'success', '', 'top-end', successMessage, false, false, true)
}
const notifyWarning = (warMessage) => {
    showNotification('#fcae1e', 'warning', '', 'top-end', warMessage, false, false, true)
}
const notifications = {
    notifyCustom,
    notifyError,
    notifyInfo,
    notifyNoData,
    notifySuccess,
    notifyWarning
}
export default notifications