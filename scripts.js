const dropContainer = document.querySelector('#drop-container')
const fileInput = document.querySelector('#file-input')
const filesList = document.querySelector('#files-list')

// On dragover
dropContainer.addEventListener('dragover', (event) => {
	event.preventDefault()
	return false
})

// On drop
dropContainer.addEventListener('drop', async (event) => {
	event.preventDefault()
    document.querySelector("#bto-submit").disabled = true
	fileInput.files = event.dataTransfer.files
	listFiles()

    let fileDataList = []
    for (let file of fileInput.files) {
        const extractedFile = await readFile(file)
        const fileData = await getText(extractedFile.target.result)
        fileData.filename = file.name
        fileDataList.push(fileData)
    }

    document.querySelector("#extra-data").value = JSON.stringify(fileDataList)
    document.querySelector("#bto-submit").disabled = false
	return false
})

// List the files
function listFiles() {
	let htmlList = ''
	Array.from(fileInput.files).forEach((file) => {
		htmlList += `<li>${file.name}</li>`
	})

	document.getElementById('files-list').innerHTML = htmlList
}

// Read the files
function readFile(file) {
	return new Promise((resolve, reject) => {
		const reader = new FileReader()
		reader.readAsArrayBuffer(file)
		reader.onload = (fileContent) => {
			resolve(fileContent)
		}
	})
}

// Gets text from file
function getText(data) {
    return new Promise((resolve, reject) => {
        pdfjsLib.GlobalWorkerOptions.workerSrc = './js/build/pdf.worker.js';
        pdfjsLib.getDocument(data).promise.then((pdf) => {
            pdf.getPage(1).then((page) => {
                page.getTextContent().then((textContent) => {
                    resolve({
                        danfeCode: getDanfeCode(textContent),
                        customerName: getCustomerName(textContent)
                    })
                })
            })
        })
    })
}

function getDanfeCode(pdfText) {
	return pdfText.items[29].str.replace('Nº	', '')
}

function getCustomerName(pdfText) {
	const fullName = pdfText.items[49].str
	let finalCustomerName = fullName.replace(/\t/g, '_')

	if (finalCustomerName.indexOf('(') !== -1) {
		finalCustomerName = finalCustomerName.substr(0, finalCustomerName.indexOf('(') - 1)
	}

	return finalCustomerName.toLowerCase()
}