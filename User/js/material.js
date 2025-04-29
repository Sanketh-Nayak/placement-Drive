function toggleActiveTab(selectedTabButton) {
    const tabs = document.querySelectorAll('.active');
    let videoContent = document.getElementById('videoContent');
    let pdfContent = document.getElementById('pdfContent');

    if (selectedTabButton.innerText == 'PDF') {
        console.log("PDF CURRENT")
        videoContent.style.display = 'none';
        pdfContent.style.display = 'flex';
    } else {
        videoContent.style.display = 'flex';
        pdfContent.style.display = 'none';
    }
    
        tabs.forEach(tab => {
        tab.classList.remove('active');
        console.log(tab);
    });
    selectedTabButton.classList.add('active');

}
