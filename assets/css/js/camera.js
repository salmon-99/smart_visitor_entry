// Camera access utility
function startCamera(videoId) {
    const video = document.getElementById(videoId);
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => video.srcObject = stream)
        .catch(() => alert("Camera access denied"));
}