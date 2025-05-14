const dropArea = document.getElementById('drop-area');
const fileElem = document.getElementById('fileElem');

dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('highlight'); });
dropArea.addEventListener('dragleave', e => { dropArea.classList.remove('highlight'); });
dropArea.addEventListener('drop', e => {
    e.preventDefault(); dropArea.classList.remove('highlight');
    fileElem.files = e.dataTransfer.files;
});